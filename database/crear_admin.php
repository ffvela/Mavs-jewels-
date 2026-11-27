<?php
/**
 * Crea la cuenta de la administradora. Uso (desde la raíz del proyecto):
 *   php database/crear_admin.php usuario correo
 * La contraseña se pide por teclado y se guarda solo como hash (password_hash).
 */
declare(strict_types=1);
if (PHP_SAPI !== 'cli') exit("Solo desde la línea de comandos.\n");
require __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createImmutable(dirname(__DIR__))->load();

[$_, $usuario, $correo] = $argv + [null, '', ''];
if ($usuario === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) exit("Uso: php database/crear_admin.php usuario correo\n");
echo 'Contraseña (mínimo 10 caracteres): ';
system('stty -echo'); $clave = trim((string)fgets(STDIN)); system('stty echo'); echo "\n";
if (mb_strlen($clave) < 10) exit("La contraseña debe tener al menos 10 caracteres.\n");
Administradora::crear($usuario, $correo, $clave);
echo "Cuenta '$usuario' creada.\n";
