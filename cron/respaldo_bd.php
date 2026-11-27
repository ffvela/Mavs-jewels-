<?php
/**
 * Respaldo propio diario de la base de datos (Tabla 2.16 del informe).
 * Guarda un volcado comprimido en una carpeta privada, fuera de public/, y conserva los últimos 7.
 * La copia externa semanal (base de datos e imágenes) se sube aparte al Google Drive de la clienta.
 */
declare(strict_types=1);
if (PHP_SAPI !== 'cli') exit;
// En cPanel > Cron Jobs, una vez al día (por ejemplo a las 03:30):  php /home/USUARIO/mavs/cron/respaldo_bd.php
require __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createImmutable(dirname(__DIR__))->load();
date_default_timezone_set('America/Santiago');

const CONSERVAR = 7;
$carpeta = $_ENV['RESPALDO_DIR'] ?? dirname(__DIR__) . '/respaldos';
if (!is_dir($carpeta) && !mkdir($carpeta, 0700, true)) exit("No se pudo crear la carpeta de respaldos.\n");

// La contraseña va en un archivo temporal de opciones para que no aparezca en la lista de procesos
$opciones = tempnam(sys_get_temp_dir(), 'mavs');
file_put_contents($opciones, "[client]\nuser=\"{$_ENV['DB_USER']}\"\npassword=\"{$_ENV['DB_PASS']}\"\nhost=\"{$_ENV['DB_HOST']}\"\nport=\"" . ($_ENV['DB_PORT'] ?? '3306') . "\"\n");
chmod($opciones, 0600);

$archivo = $carpeta . '/mavs_' . date('Y-m-d_His') . '.sql.gz';
$comando = sprintf('mysqldump --defaults-extra-file=%s --single-transaction --routines %s | gzip > %s',
    escapeshellarg($opciones), escapeshellarg($_ENV['DB_NAME']), escapeshellarg($archivo));
exec($comando . ' 2>&1', $salida, $codigo);
unlink($opciones);

if ($codigo !== 0 || !is_file($archivo) || filesize($archivo) < 100) {
    @unlink($archivo);
    error_log('Respaldo fallido: ' . implode(' ', $salida));
    exit("Respaldo fallido.\n");
}

// Conserva solo los últimos 7 respaldos
$respaldos = glob($carpeta . '/mavs_*.sql.gz');
rsort($respaldos);
foreach (array_slice($respaldos, CONSERVAR) as $viejo) unlink($viejo);
echo date('Y-m-d H:i') . ' · respaldo creado: ' . basename($archivo) . ' (' . round(filesize($archivo) / 1024) . " KB)\n";
