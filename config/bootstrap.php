<?php
declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createImmutable(dirname(__DIR__))->load();

date_default_timezone_set('America/Santiago');

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => true,       // la cookie solo viaja por HTTPS
    'httponly' => true,       // JavaScript no puede leerla
    'samesite' => 'Lax',      // no se envía en peticiones de otros sitios
]);
session_start();

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function csrf_campo(): string {
    return '<input type="hidden" name="csrf" value="'
         . htmlspecialchars($_SESSION['csrf']) . '">';
}

function csrf_validar(): void {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        http_response_code(419);
        exit('La solicitud no es válida. Recarga la página e inténtalo de nuevo.');
    }
}

/* ---------- Ayudas para las vistas ---------- */

/** Escapa un texto para mostrarlo en HTML (prevención de XSS). */
function e(mixed $texto): string {
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}

/** Ruta interna del sitio: url('catalogo') → /catalogo */
function url(string $ruta = ''): string {
    return '/' . ltrim($ruta, '/');
}

/** Formato de precio chileno: 18000 → $18.000 */
function precio(int|string $monto): string {
    return '$' . number_format((int)$monto, 0, ',', '.');
}

/** Enlace de WhatsApp con mensaje prellenado. */
function whatsapp(string $mensaje): string {
    return 'https://wa.me/' . $_ENV['WHATSAPP'] . '?text=' . rawurlencode($mensaje);
}

/** Muestra una vista dentro de la cabecera y el pie del sitio. */
function vista(string $__nombre, array $__datos = [], string $__layout = 'publico'): void {
    extract($__datos, EXTR_SKIP);          // las variables de la vista no pueden pisar estos nombres internos
    $vista = __DIR__ . '/../app/Views/' . $__nombre . '.php';
    require __DIR__ . '/../app/Views/layout/' . $__layout . '.php';
}

function redirigir(string $ruta): never {
    header('Location: ' . url($ruta));
    exit;
}

/** Mensaje de una sola vez entre una solicitud y la siguiente. */
function aviso(?string $texto = null, string $tipo = 'ok'): ?array {
    if ($texto !== null) {
        $_SESSION['aviso'] = ['texto' => $texto, 'tipo' => $tipo];
        return null;
    }
    $a = $_SESSION['aviso'] ?? null;
    unset($_SESSION['aviso']);
    return $a;
}

/** Dibuja una piedra con CSS a partir de su nombre (página Piedras y ficha de producto). */
function gema(string $nombre, string $clase = ''): string {
    static $colores = null;
    $colores ??= (require __DIR__ . '/guia_piedras.php')['colores'];
    $c = $colores[$nombre] ?? ['#eeeeee', '#999999'];
    $fondo = !empty($c[2])
        ? "linear-gradient(115deg,{$c[1]} 0%,{$c[0]} 28%,{$c[1]} 46%,{$c[0]} 62%,{$c[1]} 88%)"
        : "radial-gradient(circle at 32% 28%,#fff 0%,{$c[0]} 22%,{$c[1]} 100%)";
    return '<span class="gema-css ' . e($clase) . '" style="background:' . $fondo . '" aria-hidden="true"></span>';
}

/** Enlace a WhatsApp que pasa por /whatsapp para contar el clic (RF-25). */
function enlace_whatsapp(string $mensaje, string $desde): string {
    return url('whatsapp') . '?' . http_build_query(['desde' => $desde, 'm' => $mensaje]);
}
