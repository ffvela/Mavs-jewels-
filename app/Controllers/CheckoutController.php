<?php
declare(strict_types=1);

/** Datos de despacho, registro del pedido, reserva de piezas y paso a Mercado Pago (CU-04, CU-05). */
final class CheckoutController
{
    public const REGIONES = ['Arica y Parinacota', 'Tarapacá', 'Antofagasta', 'Atacama', 'Coquimbo', 'Valparaíso',
        'Metropolitana de Santiago', "Libertador General Bernardo O'Higgins", 'Maule', 'Ñuble', 'Biobío', 'La Araucanía',
        'Los Ríos', 'Los Lagos', 'Aysén del General Carlos Ibáñez del Campo', 'Magallanes y de la Antártica Chilena'];

    public function formulario(array $errores = [], array $datos = []): void {
        $items = Producto::paraCarrito($_SESSION['carrito'] ?? []);
        if (!$items) redirigir('carrito');
        vista('checkout', [
            'titulo' => 'Datos de despacho · Mavs Jewelry', 'pagina' => 'carrito',
            'items' => $items, 'errores' => $errores, 'datos' => $datos, 'regiones' => self::REGIONES,
        ]);
    }

    public function confirmar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirigir('checkout');
        csrf_validar();
        $d = array_map(fn($v) => is_string($v) ? trim($v) : $v, $_POST);
        $errores = self::validar($d);
        if ($errores) {
            $this->formulario($errores, $d);       // conserva lo ingresado
            return;
        }
        $items = Producto::paraCarrito($_SESSION['carrito'] ?? []);
        if (!$items || count($items) !== count($_SESSION['carrito'] ?? [])) {
            aviso('Alguna pieza de tu carrito ya no está disponible. Revisa tu carrito.', 'error');
            redirigir('carrito');
        }
        $d['rut'] = self::normalizarRut($d['rut']);
        $pedido = Pedido::crear($d, $items);
        if ($pedido === null) {
            aviso('Esta pieza se está vendiendo en este momento. Si no se completa el pago, volverá al catálogo en unos minutos.', 'error');
            redirigir('carrito');
        }
        try {
            $url = (new MercadoPagoService())->crearPreferencia($pedido, Pedido::items((int)$pedido['id_pedido']));
        } catch (Throwable $e) {
            error_log('No se pudo crear la preferencia del pedido ' . $pedido['codigo'] . ': ' . $e->getMessage());
            Pedido::rechazar((int)$pedido['id_pedido']);   // libera las piezas
            aviso('No pudimos conectar con Mercado Pago. Intenta de nuevo en unos minutos.', 'error');
            redirigir('carrito');
        }
        $_SESSION['ultimo_pedido'] = $pedido['codigo'];
        $_SESSION['carrito'] = [];
        header('Location: ' . $url);
        exit;
    }

    /** Validación en el servidor según la Tabla 4.4 (especificación de entradas). */
    public static function validar(array $d): array {
        $e = [];
        if (!preg_match("/^[\p{L}' -]{3,120}$/u", $d['nombre'] ?? '')) $e['nombre'] = 'Escribe tu nombre completo (solo letras).';
        if (!self::rutValido($d['rut'] ?? '')) $e['rut'] = 'Revisa tu RUT: formato 12.345.678-9 y dígito verificador.';
        if (!filter_var($d['correo'] ?? '', FILTER_VALIDATE_EMAIL)) $e['correo'] = 'Escribe un correo válido.';
        $fono = preg_replace('/\D/', '', $d['telefono'] ?? '');
        if (!preg_match('/^(56)?9\d{8}$/', $fono)) $e['telefono'] = 'Escribe un celular de 9 dígitos que empiece con 9.';
        if (mb_strlen($d['direccion'] ?? '') < 5 || mb_strlen($d['direccion']) > 200) $e['direccion'] = 'Escribe la calle y el número.';
        if (mb_strlen($d['comuna'] ?? '') < 3 || mb_strlen($d['comuna']) > 60) $e['comuna'] = 'Escribe tu comuna.';
        if (!in_array($d['region'] ?? '', self::REGIONES, true)) $e['region'] = 'Elige tu región de la lista.';
        if (!in_array($d['ferias'] ?? '', ['Sí', 'No'], true)) $e['ferias'] = 'Cuéntanos si nos conocías de ferias.';
        if (empty($d['politicas'])) $e['politicas'] = 'Debes aceptar las políticas de venta para comprar.';
        if (empty($d['privacidad'])) $e['privacidad'] = 'Debes aceptar la política de privacidad para comprar.';
        return $e;
    }

    /** RUT chileno con dígito verificador (módulo 11). */
    public static function rutValido(string $rut): bool {
        $r = strtoupper(preg_replace('/[.\s]/', '', $rut));
        if (!preg_match('/^(\d{7,8})-([\dK])$/', $r, $m)) return false;
        $suma = 0; $mul = 2;
        for ($i = strlen($m[1]) - 1; $i >= 0; $i--) { $suma += (int)$m[1][$i] * $mul; $mul = $mul === 7 ? 2 : $mul + 1; }
        $dv = 11 - ($suma % 11);
        return $m[2] === ($dv === 11 ? '0' : ($dv === 10 ? 'K' : (string)$dv));
    }

    private static function normalizarRut(string $rut): string {
        return strtoupper(preg_replace('/[.\s]/', '', $rut));
    }
}
