<?php
declare(strict_types=1);

/** Carrito en la sesión. Cada pieza única se agrega una sola vez (RF-04). */
final class CarritoController
{
    public function ver(): void {
        $items = Producto::paraCarrito($_SESSION['carrito'] ?? []);
        $_SESSION['carrito'] = array_map('intval', array_column($items, 'id_producto')); // quita las ya vendidas
        vista('carrito', ['titulo' => 'Carrito · Mavs Jewelry', 'pagina' => 'carrito', 'items' => $items]);
    }

    public function agregar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirigir('carrito');
        csrf_validar();
        $id = (int)($_POST['id'] ?? 0);
        if (Producto::paraCarrito([$id])) {
            $_SESSION['carrito'] = array_values(array_unique(array_merge($_SESSION['carrito'] ?? [], [$id])));
            aviso('Agregaste la pieza a tu carrito.');
        } else {
            aviso('Esta pieza ya no está disponible.', 'error');
        }
        redirigir('carrito');
    }

    public function quitar(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_validar();
            $id = (int)($_POST['id'] ?? 0);
            $_SESSION['carrito'] = array_values(array_filter($_SESSION['carrito'] ?? [], fn($x) => $x !== $id));
        }
        redirigir('carrito');
    }
}
