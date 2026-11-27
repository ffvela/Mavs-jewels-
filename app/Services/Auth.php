<?php
declare(strict_types=1);

final class Auth
{
    public const MINUTOS_INACTIVIDAD = 30;

    /** Exige sesión de la administradora y la cierra tras 30 minutos sin actividad. */
    public static function requerirSesion(): void {
        $ultima = $_SESSION['ultima_actividad'] ?? 0;
        if (empty($_SESSION['admin_id']) || time() - $ultima > self::MINUTOS_INACTIVIDAD * 60) {
            unset($_SESSION['admin_id'], $_SESSION['ultima_actividad']);
            redirigir('admin/login');
        }
        $_SESSION['ultima_actividad'] = time();
    }

    public static function idAdmin(): int {
        return (int)($_SESSION['admin_id'] ?? 0);
    }
}
