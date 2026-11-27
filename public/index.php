<?php
require __DIR__ . '/../config/bootstrap.php';

$ruta = trim($_GET['r'] ?? 'inicio', '/') ?: 'inicio';
$rutas = [
    'inicio'             => [InicioController::class,   'index'],
    'catalogo'           => [CatalogoController::class, 'index'],
    'producto'           => [CatalogoController::class, 'ficha'],
    'personalizadas'     => [PaginaController::class,   'personalizadas'],
    'piedras'            => [PaginaController::class,   'piedras'],
    'nosotros'           => [PaginaController::class,   'nosotros'],
    'contacto'           => [PaginaController::class,   'contacto'],
    'whatsapp'           => [PaginaController::class,   'whatsapp'],
    'carrito'            => [CarritoController::class, 'ver'],
    'carrito/agregar'    => [CarritoController::class, 'agregar'],
    'carrito/quitar'     => [CarritoController::class, 'quitar'],
    'checkout'           => [CheckoutController::class, 'formulario'],
    'checkout/confirmar' => [CheckoutController::class, 'confirmar'],
    'pago/webhook'       => [PagoController::class,     'webhook'],
    'pago/resultado'     => [PagoController::class,     'resultado'],
    'admin/login'        => [AuthController::class,     'login'],
    'admin/logout'       => [AuthController::class,     'logout'],
    'admin/productos'    => [AdminProductoController::class, 'index'],
    'admin/productos/editar'  => [AdminProductoController::class, 'editar'],
    'admin/productos/guardar' => [AdminProductoController::class, 'guardar'],
    'admin/productos/estado'  => [AdminProductoController::class, 'cambiarEstado'],
    'admin/pedidos'      => [AdminPedidoController::class,   'index'],
    'admin/pedidos/estado'    => [AdminPedidoController::class,   'cambiarEstado'],
    'admin/pedidos/reembolso' => [AdminPedidoController::class,   'reembolso'],
];

if (!isset($rutas[$ruta])) {
    http_response_code(404);
    require __DIR__ . '/../app/Views/errores/404.php';
    exit;
}

if (str_starts_with($ruta, 'admin/') && $ruta !== 'admin/login') {
    Auth::requerirSesion();          // redirige al login si no hay sesión
}
[$clase, $metodo] = $rutas[$ruta];
(new $clase())->$metodo();
