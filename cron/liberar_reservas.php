<?php
/**
 * Tarea programada (cron) cada 5 minutos: libera las reservas vencidas (RF-23).
 * Los pedidos pendientes de pago por más de 15 minutos pasan a "expirado" y sus piezas
 * vuelven al catálogo.
 */
// En cPanel > Cron Jobs, cada 5 minutos:  php /home/USUARIO/mavs/cron/liberar_reservas.php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') exit;
require __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createImmutable(dirname(__DIR__))->load();
date_default_timezone_set('America/Santiago');

$vencidos = Pedido::pendientesVencidos();
foreach ($vencidos as $p) {
    Pedido::expirar((int)$p['id_pedido']);
}
// Piezas reservadas cuya reserva venció sin pedido pendiente (por ejemplo, un error al crear la preferencia)
$sueltas = Database::conexion()->exec(
    "UPDATE producto SET estado = 'publicado', reservado_hasta = NULL
      WHERE estado = 'reservado' AND reservado_hasta < NOW() - INTERVAL 1 MINUTE");
echo date('Y-m-d H:i') . ' · pedidos expirados: ' . count($vencidos) . ' · piezas liberadas sin pedido: ' . $sueltas . "\n";
