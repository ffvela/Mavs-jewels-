<?php
declare(strict_types=1);

final class AuthController
{
    public function login(): void {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_validar();
            $admin = Administradora::buscarPorUsuario(trim($_POST['usuario'] ?? ''));

            if ($admin && $admin['bloqueado_hasta'] && strtotime($admin['bloqueado_hasta']) > time()) {
                $error = 'Cuenta bloqueada temporalmente. Intenta en unos minutos.';
            } elseif ($admin && password_verify($_POST['contrasena'] ?? '', $admin['hash_contrasena'])) {
                session_regenerate_id(true);
                $_SESSION['admin_id'] = (int)$admin['id_admin'];
                $_SESSION['ultima_actividad'] = time();
                Administradora::registrarAccesoExitoso((int)$admin['id_admin']);
                header('Location: /admin/productos');
                exit;
            } else {
                if ($admin) Administradora::registrarIntentoFallido((int)$admin['id_admin']); // bloquea al 5.º intento
                $error = 'Usuario o contraseña incorrectos.';
            }
        }
        require __DIR__ . '/../Views/admin/login.php';
    }

    public function logout(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_validar();
            $_SESSION = [];
            session_regenerate_id(true);
        }
        redirigir('admin/login');
    }
}
