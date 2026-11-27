<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ingresar al panel · Mavs Jewelry</title>
  <meta name="robots" content="noindex">
  <link href="https://fonts.googleapis.com/css2?family=Anton&amp;family=IBM+Plex+Mono:wght@400;500&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/mavs.css">
  <link rel="stylesheet" href="/css/panel.css">
</head>
<body class="panel-body ingreso-body">
  <main class="ingreso">
    <div class="ingreso-marca"><img src="/img/logo-mavs.png" alt="Mavs Jewelry" width="120" height="89"><p>Panel de administración</p></div>
    <form class="ingreso-form" method="post" action="/admin/login">
      <h1>Ingresar</h1>
      <?= csrf_campo() ?>
      <div class="campo"><label for="usuario">Usuario</label><input id="usuario" name="usuario" autocomplete="username" required value="<?= e($_POST['usuario'] ?? '') ?>"></div>
      <div class="campo"><label for="contrasena">Contraseña</label><input id="contrasena" name="contrasena" type="password" autocomplete="current-password" required></div>
      <?php if (!empty($error)): ?><p class="aviso error" role="alert"><?= e($error) ?></p><?php endif; ?>
      <button class="btn btn-noche btn-ancho" type="submit">Ingresar</button>
      <small>Por seguridad, después de 5 intentos fallidos el ingreso se bloquea por 15 minutos.</small>
    </form>
    <a class="volver-sitio" href="/">Volver al sitio</a>
  </main>
</body>
</html>
