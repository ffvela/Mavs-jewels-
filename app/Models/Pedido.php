<?php
declare(strict_types=1);

final class Pedido
{
    public const ESTADOS = ['pendiente', 'pagado', 'rechazado', 'expirado', 'en_preparacion', 'enviado', 'reembolsado'];
    public const MINUTOS_RESERVA = 15;

    /**
     * Registra el pedido como "pendiente de pago" y reserva sus piezas en una sola transacción.
     * Cada pieza se reserva con una operación atómica que solo tiene éxito si sigue publicada (RF-21):
     * si dos personas intentan comprar la misma pieza, solo la primera la obtiene.
     * Devuelve el pedido creado, o null si alguna pieza ya no está disponible.
     */
    public static function crear(array $d, array $items): ?array {
        $db = Database::conexion();
        $db->beginTransaction();
        try {
            $total = array_sum(array_map(fn($i) => (int)$i['precio'], $items));
            $db->prepare("INSERT INTO pedido (codigo, fecha_creacion, nombre_cliente, rut, correo, telefono, direccion,
                                              comuna, region, total, estado, acepta_politicas, acepta_privacidad, conoce_ferias)
                          VALUES (NULL, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente', 1, 1, ?)")
               ->execute([$d['nombre'], $d['rut'], $d['correo'], $d['telefono'], $d['direccion'],
                          $d['comuna'], $d['region'], $total, $d['ferias'] === 'Sí' ? 1 : 0]);
            $id = (int)$db->lastInsertId();
            $codigo = sprintf('MJ-%s-%06d', date('Y'), $id);           // sección 4.2.7
            $db->prepare("UPDATE pedido SET codigo = ? WHERE id_pedido = ?")->execute([$codigo, $id]);

            $detalle = $db->prepare("INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario)
                                     VALUES (?, ?, 1, ?)");
            $reserva = $db->prepare(
                "UPDATE producto
                    SET estado = IF(es_unica = 1, 'reservado', estado),
                        reservado_hasta = IF(es_unica = 1, NOW() + INTERVAL " . self::MINUTOS_RESERVA . " MINUTE, reservado_hasta)
                  WHERE id_producto = ? AND estado = 'publicado' AND stock >= 1");
            foreach ($items as $i) {
                $reserva->execute([(int)$i['id_producto']]);
                if ($reserva->rowCount() === 0) {        // otra persona la está comprando o ya se vendió
                    $db->rollBack();
                    return null;
                }
                $detalle->execute([$id, (int)$i['id_producto'], (int)$i['precio']]);
            }
            $db->prepare("INSERT INTO historial_estado (id_pedido, estado_anterior, estado_nuevo, fecha)
                          VALUES (?, NULL, 'pendiente', NOW())")->execute([$id]);
            $db->commit();
            return self::buscar($id);
        } catch (Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function buscar(int $id): ?array {
        $st = Database::conexion()->prepare("SELECT * FROM pedido WHERE id_pedido = ?");
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public static function buscarPorCodigo(string $codigo): ?array {
        $st = Database::conexion()->prepare("SELECT * FROM pedido WHERE codigo = ?");
        $st->execute([$codigo]);
        return $st->fetch() ?: null;
    }

    public static function items(int $idPedido): array {
        $st = Database::conexion()->prepare(
            "SELECT d.cantidad, d.precio_unitario, p.id_producto, p.nombre, p.codigo
               FROM detalle_pedido d JOIN producto p ON p.id_producto = d.id_producto
              WHERE d.id_pedido = ?");
        $st->execute([$idPedido]);
        return $st->fetchAll();
    }

    public static function contarPiezas(int $idPedido): int {
        $st = Database::conexion()->prepare("SELECT COUNT(*) FROM detalle_pedido WHERE id_pedido = ?");
        $st->execute([$idPedido]);
        return (int)$st->fetchColumn();
    }

    public static function confirmarPago(int $idPedido, string $idTransaccion,
                                         string $desde = 'pendiente'): bool {
        $db = Database::conexion();
        $db->beginTransaction();
        try {
            $st = $db->prepare("UPDATE pedido SET estado = 'pagado', fecha_pago = NOW()
                                 WHERE id_pedido = ? AND estado = ?");
            $st->execute([$idPedido, $desde]);
            if ($st->rowCount() === 0) {          // ya estaba pagado: no repetir
                $db->rollBack();
                return false;
            }
            $db->prepare("INSERT INTO pago (id_pedido, id_transaccion_mp, estado, monto, fecha)
                          SELECT id_pedido, ?, 'approved', total, NOW()
                            FROM pedido WHERE id_pedido = ?")
               ->execute([$idTransaccion, $idPedido]);
            $st = $db->prepare("UPDATE producto p JOIN detalle_pedido d ON d.id_producto = p.id_producto
                                   SET p.stock = p.stock - d.cantidad
                                 WHERE d.id_pedido = ? AND p.stock >= d.cantidad
                                   AND (p.estado <> 'reservado' OR ? = 'pendiente')"); // reservada por otra compra
            $st->execute([$idPedido, $desde]);
            if ($st->rowCount() < self::contarPiezas($idPedido)) { // alguna pieza ya se vendió
                $db->rollBack();
                return false;
            }
            $db->prepare("UPDATE producto SET estado = 'vendido'
                           WHERE stock = 0 AND id_producto IN
                                 (SELECT id_producto FROM detalle_pedido WHERE id_pedido = ?)")
               ->execute([$idPedido]);
            $db->prepare("INSERT INTO historial_estado (id_pedido, estado_anterior, estado_nuevo, fecha)
                          VALUES (?, ?, 'pagado', NOW())")
               ->execute([$idPedido, $desde]);
            $db->commit();
            return true;
        } catch (Throwable $e) {
            $db->rollBack();
            error_log('Error al confirmar pedido ' . $idPedido . ': ' . $e->getMessage());
            throw $e;
        }
    }

    /** Pago rechazado o cancelado: el pedido pasa a "rechazado" y sus piezas vuelven a estar disponibles. */
    public static function rechazar(int $idPedido): void {
        self::cerrarSinPago($idPedido, 'rechazado');
    }

    /** Lo usa la tarea programada cuando vence la reserva sin pago (RF-23). */
    public static function expirar(int $idPedido): void {
        self::cerrarSinPago($idPedido, 'expirado');
    }

    private static function cerrarSinPago(int $idPedido, string $estado): void {
        $db = Database::conexion();
        $db->beginTransaction();
        try {
            $st = $db->prepare("UPDATE pedido SET estado = ? WHERE id_pedido = ? AND estado = 'pendiente'");
            $st->execute([$estado, $idPedido]);
            if ($st->rowCount() === 1) {
                $db->prepare("UPDATE producto p JOIN detalle_pedido d ON d.id_producto = p.id_producto
                                 SET p.estado = 'publicado', p.reservado_hasta = NULL
                               WHERE d.id_pedido = ? AND p.estado = 'reservado'")
                   ->execute([$idPedido]);
                $db->prepare("INSERT INTO historial_estado (id_pedido, estado_anterior, estado_nuevo, fecha)
                              VALUES (?, 'pendiente', ?, NOW())")->execute([$idPedido, $estado]);
            }
            $db->commit();
        } catch (Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /** Pago aprobado que llegó tarde y cuya pieza ya se vendió: se reembolsa (CU-05, flujo 5c). */
    public static function marcarReembolsado(int $idPedido, string $idTransaccion): void {
        $db = Database::conexion();
        $db->beginTransaction();
        try {
            $db->prepare("UPDATE pedido SET estado = 'reembolsado' WHERE id_pedido = ? AND estado = 'expirado'")
               ->execute([$idPedido]);
            $db->prepare("INSERT INTO pago (id_pedido, id_transaccion_mp, estado, monto, fecha)
                          SELECT id_pedido, ?, 'refunded', total, NOW() FROM pedido WHERE id_pedido = ?")
               ->execute([$idTransaccion, $idPedido]);
            $db->prepare("INSERT INTO historial_estado (id_pedido, estado_anterior, estado_nuevo, fecha)
                          VALUES (?, 'expirado', 'reembolsado', NOW())")->execute([$idPedido]);
            $db->commit();
        } catch (Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /** Pedidos con la reserva vencida y sin pago (más de 15 minutos). */
    public static function pendientesVencidos(): array {
        return Database::conexion()->query(
            "SELECT id_pedido FROM pedido
              WHERE estado = 'pendiente'
                AND fecha_creacion < NOW() - INTERVAL " . self::MINUTOS_RESERVA . " MINUTE")->fetchAll();
    }

    /* ---------- Panel ---------- */

    public static function listarPanel(string $estado = ''): array {
        $sql = "SELECT id_pedido, codigo, fecha_creacion, nombre_cliente, comuna, region, total, estado FROM pedido";
        $params = [];
        if (in_array($estado, self::ESTADOS, true)) { $sql .= " WHERE estado = ?"; $params[] = $estado; }
        $st = Database::conexion()->prepare($sql . " ORDER BY fecha_creacion DESC LIMIT 200");
        $st->execute($params);
        return $st->fetchAll();
    }

    public static function contarPorPreparar(): int {
        return (int)Database::conexion()
            ->query("SELECT COUNT(*) FROM pedido WHERE estado IN ('pagado', 'en_preparacion')")->fetchColumn();
    }

    /** Cambio de estado manual desde el panel: pagado → en preparación → enviado (RF-20). */
    public static function cambiarEstado(int $idPedido, string $nuevo, int $idAdmin): bool {
        $permitidos = ['pagado' => ['en_preparacion', 'enviado'], 'en_preparacion' => ['enviado']];
        $db = Database::conexion();
        $actual = self::buscar($idPedido)['estado'] ?? '';
        if (!in_array($nuevo, $permitidos[$actual] ?? [], true)) return false;
        $db->beginTransaction();
        $db->prepare("UPDATE pedido SET estado = ? WHERE id_pedido = ?")->execute([$nuevo, $idPedido]);
        $db->prepare("INSERT INTO historial_estado (id_pedido, estado_anterior, estado_nuevo, fecha, id_admin)
                      VALUES (?, ?, ?, NOW(), ?)")->execute([$idPedido, $actual, $nuevo, $idAdmin]);
        $db->commit();
        return true;
    }

    /**
     * Reembolso por retracto o garantía registrado por la administradora. La devolución del dinero
     * se hace en Mercado Pago; si ella lo decide, las piezas vuelven a estar disponibles.
     */
    public static function registrarReembolso(int $idPedido, int $idAdmin, bool $reponer): bool {
        $db = Database::conexion();
        $actual = self::buscar($idPedido)['estado'] ?? '';
        if (!in_array($actual, ['pagado', 'en_preparacion', 'enviado'], true)) return false;
        $db->beginTransaction();
        try {
            $db->prepare("UPDATE pedido SET estado = 'reembolsado' WHERE id_pedido = ?")->execute([$idPedido]);
            if ($reponer) {
                $db->prepare("UPDATE producto p JOIN detalle_pedido d ON d.id_producto = p.id_producto
                                 SET p.stock = p.stock + d.cantidad, p.estado = 'publicado'
                               WHERE d.id_pedido = ?")->execute([$idPedido]);
            }
            $db->prepare("INSERT INTO historial_estado (id_pedido, estado_anterior, estado_nuevo, fecha, id_admin)
                          VALUES (?, ?, 'reembolsado', NOW(), ?)")->execute([$idPedido, $actual, $idAdmin]);
            $db->commit();
            return true;
        } catch (Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function historial(int $idPedido): array {
        $st = Database::conexion()->prepare(
            "SELECT estado_anterior, estado_nuevo, fecha FROM historial_estado WHERE id_pedido = ? ORDER BY fecha");
        $st->execute([$idPedido]);
        return $st->fetchAll();
    }
}
