<?php
declare(strict_types=1);

final class Administradora
{
    public const MAX_INTENTOS = 5;
    public const MINUTOS_BLOQUEO = 15;

    public static function buscarPorUsuario(string $usuario): ?array {
        $st = Database::conexion()->prepare("SELECT * FROM administradora WHERE usuario = ?");
        $st->execute([$usuario]);
        return $st->fetch() ?: null;
    }

    public static function registrarAccesoExitoso(int $id): void {
        Database::conexion()->prepare(
            "UPDATE administradora SET intentos_fallidos = 0, bloqueado_hasta = NULL, ultimo_acceso = NOW()
              WHERE id_admin = ?")->execute([$id]);
    }

    /** Suma un intento fallido; al quinto bloquea el ingreso por 15 minutos. */
    public static function registrarIntentoFallido(int $id): void {
        Database::conexion()->prepare(
            "UPDATE administradora
                SET intentos_fallidos = intentos_fallidos + 1,
                    bloqueado_hasta = IF(intentos_fallidos >= " . self::MAX_INTENTOS . ",
                                         NOW() + INTERVAL " . self::MINUTOS_BLOQUEO . " MINUTE, NULL),
                    intentos_fallidos = IF(intentos_fallidos >= " . self::MAX_INTENTOS . ", 0, intentos_fallidos)
              WHERE id_admin = ?")->execute([$id]);
    }

    public static function crear(string $usuario, string $correo, string $contrasena): void {
        Database::conexion()->prepare(
            "INSERT INTO administradora (usuario, correo, hash_contrasena, intentos_fallidos) VALUES (?, ?, ?, 0)")
            ->execute([$usuario, $correo, password_hash($contrasena, PASSWORD_DEFAULT)]);
    }
}
