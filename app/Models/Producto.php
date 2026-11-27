<?php
declare(strict_types=1);

final class Producto
{
    public const ESTADOS = ['borrador', 'publicado', 'oculto', 'reservado', 'vendido'];

    public static function listar(array $f): array {
        $sql = "SELECT p.id_producto, p.codigo, p.nombre, p.precio,
                       (SELECT ruta FROM imagen_producto i
                         WHERE i.id_producto = p.id_producto
                         ORDER BY i.orden LIMIT 1) AS foto
                  FROM producto p
                  JOIN tecnica t ON t.id_tecnica = p.id_tecnica
                 WHERE p.estado = 'publicado' AND p.stock > 0
                   AND t.slug = :tecnica";
        $params = [':tecnica' => $f['tecnica']];

        if (!empty($f['tipo']))       { $sql .= " AND p.id_tipo = :tipo";       $params[':tipo'] = (int)$f['tipo']; }
        if (!empty($f['precio_max'])) { $sql .= " AND p.precio <= :pmax";       $params[':pmax'] = (int)$f['precio_max']; }

        $sql .= " ORDER BY p.creado_en DESC";
        $st = Database::conexion()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Últimas piezas publicadas, para el inicio. */
    public static function destacados(int $cantidad = 7): array {
        $st = Database::conexion()->prepare(
            "SELECT p.id_producto, p.codigo, p.nombre, p.precio, p.es_unica, t.slug AS tecnica, t.nombre AS tecnica_nombre,
                    (SELECT ruta FROM imagen_producto i WHERE i.id_producto = p.id_producto ORDER BY i.orden LIMIT 1) AS foto
               FROM producto p JOIN tecnica t ON t.id_tecnica = p.id_tecnica
              WHERE p.estado = 'publicado' AND p.stock > 0
              ORDER BY p.creado_en DESC LIMIT " . max(1, $cantidad));
        $st->execute();
        return $st->fetchAll();
    }

    /** Ficha completa de una pieza publicada, con fotos y piedras. */
    public static function ficha(int $id): ?array {
        $db = Database::conexion();
        $st = $db->prepare(
            "SELECT p.*, t.nombre AS tecnica_nombre, t.slug AS tecnica, tp.nombre AS tipo
               FROM producto p
               JOIN tecnica t ON t.id_tecnica = p.id_tecnica
               JOIN tipo_pieza tp ON tp.id_tipo = p.id_tipo
              WHERE p.id_producto = ? AND p.estado IN ('publicado', 'reservado')");
        $st->execute([$id]);
        $p = $st->fetch();
        if (!$p) return null;
        $p['fotos'] = self::fotos($id);
        $st = $db->prepare(
            "SELECT pi.nombre, pi.significado FROM producto_piedra pp
               JOIN piedra pi ON pi.id_piedra = pp.id_piedra WHERE pp.id_producto = ? ORDER BY pi.nombre");
        $st->execute([$id]);
        $p['piedras'] = $st->fetchAll();
        return $p;
    }

    public static function fotos(int $id): array {
        $st = Database::conexion()->prepare(
            "SELECT ruta, texto_alt FROM imagen_producto WHERE id_producto = ? ORDER BY orden");
        $st->execute([$id]);
        return $st->fetchAll();
    }

    /** Datos mínimos para el carrito: solo piezas que se pueden comprar ahora. */
    public static function paraCarrito(array $ids): array {
        if (!$ids) return [];
        $marcas = implode(',', array_fill(0, count($ids), '?'));
        $st = Database::conexion()->prepare(
            "SELECT p.id_producto, p.nombre, p.precio, p.es_unica, p.estado, t.nombre AS tecnica_nombre,
                    (SELECT ruta FROM imagen_producto i WHERE i.id_producto = p.id_producto ORDER BY i.orden LIMIT 1) AS foto
               FROM producto p JOIN tecnica t ON t.id_tecnica = p.id_tecnica
              WHERE p.id_producto IN ($marcas) AND p.estado = 'publicado' AND p.stock > 0");
        $st->execute(array_map('intval', $ids));
        return $st->fetchAll();
    }

    public static function tecnicas(): array {
        return Database::conexion()->query("SELECT * FROM tecnica ORDER BY id_tecnica")->fetchAll();
    }

    public static function tipos(): array {
        return Database::conexion()->query("SELECT * FROM tipo_pieza ORDER BY nombre")->fetchAll();
    }

    /* ---------- Panel ---------- */

    public static function listarPanel(string $estado = '', string $buscar = ''): array {
        $sql = "SELECT p.id_producto, p.codigo, p.nombre, p.precio, p.stock, p.estado, p.reservado_hasta,
                       t.nombre AS tecnica_nombre, tp.nombre AS tipo,
                       (SELECT ruta FROM imagen_producto i WHERE i.id_producto = p.id_producto ORDER BY i.orden LIMIT 1) AS foto
                  FROM producto p
                  JOIN tecnica t ON t.id_tecnica = p.id_tecnica
                  JOIN tipo_pieza tp ON tp.id_tipo = p.id_tipo
                 WHERE 1 = 1";
        $params = [];
        if (in_array($estado, self::ESTADOS, true)) { $sql .= " AND p.estado = :estado"; $params[':estado'] = $estado; }
        if ($buscar !== '') { $sql .= " AND (p.nombre LIKE :b OR p.codigo LIKE :b)"; $params[':b'] = '%' . $buscar . '%'; }
        $sql .= " ORDER BY p.creado_en DESC";
        $st = Database::conexion()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public static function contarPorEstado(): array {
        $filas = Database::conexion()->query("SELECT estado, COUNT(*) AS n FROM producto GROUP BY estado")->fetchAll();
        return array_column($filas, 'n', 'estado');
    }

    public static function buscar(int $id): ?array {
        $st = Database::conexion()->prepare("SELECT * FROM producto WHERE id_producto = ?");
        $st->execute([$id]);
        $p = $st->fetch();
        if (!$p) return null;
        $st = Database::conexion()->prepare("SELECT id_piedra FROM producto_piedra WHERE id_producto = ?");
        $st->execute([$id]);
        $p['piedras'] = array_map('intval', array_column($st->fetchAll(), 'id_piedra'));
        return $p;
    }

    /** Crea o actualiza una pieza. El código sigue el formato TT-TIP-NNNN (sección 4.2.7). */
    public static function guardar(array $d, array $piedras): int {
        $db = Database::conexion();
        $db->beginTransaction();
        try {
            if (!empty($d['id_producto'])) {
                $id = (int)$d['id_producto'];
                $db->prepare("UPDATE producto SET nombre = ?, descripcion = ?, materiales = ?, precio = ?, stock = ?,
                                     es_unica = ?, estado = ?, id_tecnica = ?, id_tipo = ?, actualizado_en = NOW()
                               WHERE id_producto = ?")
                   ->execute([$d['nombre'], $d['descripcion'], $d['materiales'], $d['precio'], $d['stock'],
                              $d['es_unica'], $d['estado'], $d['id_tecnica'], $d['id_tipo'], $id]);
            } else {
                $db->prepare("INSERT INTO producto (codigo, nombre, descripcion, materiales, precio, stock, es_unica,
                                                    estado, id_tecnica, id_tipo, creado_en, actualizado_en)
                              VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())")
                   ->execute([$d['nombre'], $d['descripcion'], $d['materiales'], $d['precio'], $d['stock'],
                              $d['es_unica'], $d['estado'], $d['id_tecnica'], $d['id_tipo']]);
                $id = (int)$db->lastInsertId();
                $st = $db->prepare("SELECT t.slug, tp.slug AS tipo FROM tecnica t, tipo_pieza tp
                                     WHERE t.id_tecnica = ? AND tp.id_tipo = ?");
                $st->execute([$d['id_tecnica'], $d['id_tipo']]);
                $c = $st->fetch();
                $codigo = sprintf('%s-%s-%04d', $c['slug'] === 'plata' ? 'PL' : 'MA',
                                  strtoupper(substr($c['tipo'], 0, 3)), $id);
                $db->prepare("UPDATE producto SET codigo = ? WHERE id_producto = ?")->execute([$codigo, $id]);
            }
            $db->prepare("DELETE FROM producto_piedra WHERE id_producto = ?")->execute([$id]);
            $ins = $db->prepare("INSERT INTO producto_piedra (id_producto, id_piedra) VALUES (?, ?)");
            foreach ($piedras as $idPiedra) $ins->execute([$id, (int)$idPiedra]);
            $db->commit();
            return $id;
        } catch (Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function agregarFoto(int $id, string $ruta, string $alt): void {
        $db = Database::conexion();
        $st = $db->prepare("SELECT COALESCE(MAX(orden), 0) + 1 FROM imagen_producto WHERE id_producto = ?");
        $st->execute([$id]);
        $db->prepare("INSERT INTO imagen_producto (id_producto, ruta, texto_alt, orden) VALUES (?, ?, ?, ?)")
           ->execute([$id, $ruta, $alt, (int)$st->fetchColumn()]);
    }

    /**
     * Cambia el estado desde el panel. Una pieza reservada por un pago en curso
     * no puede marcarse como vendida ni ocultarse hasta que la reserva termine (RF-18).
     */
    public static function cambiarEstado(int $id, string $nuevo): bool {
        $set = $nuevo === 'vendido' ? "estado = 'vendido', stock = 0" : "estado = :nuevo";
        $st = Database::conexion()->prepare(
            "UPDATE producto SET $set, actualizado_en = NOW()
              WHERE id_producto = :id AND estado <> 'reservado'");
        $params = [':id' => $id];
        if ($nuevo !== 'vendido') $params[':nuevo'] = $nuevo;
        $st->execute($params);
        return $st->rowCount() === 1;
    }
}
