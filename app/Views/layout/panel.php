<?php $avisoActual = aviso(); $seccion ??= ''; ?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($titulo ?? 'Panel · Mavs Jewelry') ?></title>
  <meta name="robots" content="noindex">
  <link href="https://fonts.googleapis.com/css2?family=Anton&amp;family=IBM+Plex+Mono:wght@400;500&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= url('css/mavs.css') ?>">
  <link rel="stylesheet" href="<?= url('css/panel.css') ?>">
</head>
<body class="panel-body">
  <header class="panel-cab">
    <a class="panel-logo" href="<?= url('admin/productos') ?>"><img src="<?= url('img/logo-mavs.png') ?>" alt="Mavs Jewelry" width="60" height="44"><span>Panel</span></a>
    <nav class="panel-nav" aria-label="Secciones del panel">
      <a href="<?= url('admin/productos') ?>"<?= $seccion === 'productos' ? ' aria-current="page"' : '' ?>>Productos</a>
      <a href="<?= url('admin/pedidos') ?>"<?= $seccion === 'pedidos' ? ' aria-current="page"' : '' ?>>Pedidos</a>
    </nav>
    <div class="panel-sesion">
      <a href="<?= url() ?>" target="_blank">Ver sitio</a>
      <form method="post" action="<?= url('admin/logout') ?>"><?= csrf_campo() ?><button type="submit" class="enlace-boton">Cerrar sesión</button></form>
    </div>
  </header>
  <main class="panel-main">
    <?php if (isset($porPreparar)): ?>
      <section class="indicadores" aria-label="Resumen">
        <div><span>Por preparar</span><strong><?= (int)$porPreparar ?></strong></div>
        <div><span>Piezas publicadas</span><strong><?= (int)($conteo['publicado'] ?? 0) ?></strong></div>
        <div><span>Reservadas ahora</span><strong><?= (int)($conteo['reservado'] ?? 0) ?></strong></div>
        <div><span>Clics en WhatsApp este mes</span><strong><?= (int)$clics ?></strong></div>
      </section>
    <?php endif; ?>
    <?php if ($avisoActual): ?><p class="aviso <?= e($avisoActual['tipo']) ?>" role="status"><?= e($avisoActual['texto']) ?></p><?php endif; ?>
    <?php require $vista; ?>
  </main>
</body>
</html>
