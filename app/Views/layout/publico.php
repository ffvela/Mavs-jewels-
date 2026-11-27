<?php
$pagina ??= '';
$enCarrito = count($_SESSION['carrito'] ?? []);
$menu = ['' => ['Inicio', 'inicio'], 'catalogo' => ['Catálogo', 'catalogo'], 'personalizadas' => ['Personalizadas', 'personalizadas'],
         'piedras' => ['Piedras', 'piedras'], 'nosotros' => ['Nosotros', 'nosotros'], 'contacto' => ['Contacto', 'contacto']];
$estrella = '<svg class="estrella" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 0c.7 6.4 5.6 11.3 12 12-6.4.7-11.3 5.6-12 12-.7-6.4-5.6-11.3-12-12C6.4 11.3 11.3 6.4 12 0z"/></svg>';
$avisoActual = aviso();
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($titulo ?? 'Mavs Jewelry') ?></title>
  <meta name="description" content="Joyería artesanal hecha a mano en Santiago: plata con piedras naturales, mostacilla Miyuki y alambrismo.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Anton&amp;family=IBM+Plex+Mono:wght@400;500&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= url('css/mavs.css') ?>">
</head>
<body>
  <div class="franja"><div class="contenedor">
    <span><?= $estrella ?>100% hecho a mano</span><span><?= $estrella ?>Envíos por Starken</span><span><?= $estrella ?>Pago con tarjeta</span>
  </div></div>
  <header class="cabecera"><div class="contenedor">
    <button class="icono-btn menu-btn" type="button" id="abrir-menu" aria-label="Abrir menú" aria-expanded="false" aria-controls="nav-principal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
    </button>
    <nav class="nav" id="nav-principal" aria-label="Navegación principal">
      <?php foreach ($menu as $ruta => [$texto, $clave]): ?>
        <a href="<?= url($ruta) ?>"<?= $pagina === $clave ? ' aria-current="page"' : '' ?>><?= $texto ?></a>
      <?php endforeach; ?>
    </nav>
    <a class="marca" href="<?= url() ?>" aria-label="Mavs Jewelry, inicio"><img src="<?= url('img/logo-mavs-negro.png') ?>" alt="Mavs Jewelry" width="68" height="50"></a>
    <div class="cab-acciones">
      <a class="icono-btn" href="https://www.instagram.com/mavs_jewelry/" target="_blank" rel="noopener" aria-label="Instagram">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r=".8" fill="currentColor"/></svg></a>
      <a class="icono-btn" href="<?= url('contacto') ?>" aria-label="Contacto">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></a>
      <a class="icono-btn" href="<?= url('carrito') ?>" aria-label="Carrito, <?= $enCarrito ?> piezas">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M5 8h14l-1 12H6L5 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
        <?php if ($enCarrito): ?><span class="contador"><?= $enCarrito ?></span><?php endif; ?></a>
    </div>
  </div></header>

  <main>
    <?php if ($avisoActual): ?>
      <div class="contenedor"><p class="aviso <?= e($avisoActual['tipo']) ?>" role="status"><?= e($avisoActual['texto']) ?></p></div>
    <?php endif; ?>
    <?php require $vista; ?>
  </main>

  <footer class="pie-sitio"><div class="contenedor">
    <div class="columnas">
      <div><img class="logo-pie" src="<?= url('img/logo-mavs.png') ?>" alt="Mavs Jewelry" width="100" height="74">
        <p>Joyería artesanal hecha a mano en Santiago por María Angélica Venegas. Plata, piedras naturales, mostacilla Miyuki y alambrismo.</p></div>
      <div><h4>Tienda</h4><ul>
        <li><a href="<?= url('catalogo?tecnica=plata') ?>">Plata con piedras</a></li>
        <li><a href="<?= url('catalogo?tecnica=miyuki') ?>">Miyuki y alambrismo</a></li>
        <li><a href="<?= url('personalizadas') ?>">Piezas personalizadas</a></li>
        <li><a href="<?= url('piedras') ?>">Significado de las piedras</a></li></ul></div>
      <div><h4>Compra segura</h4><ul>
        <li><a href="<?= url('contacto') ?>#politicas">Garantía legal de 6 meses</a></li>
        <li><a href="<?= url('contacto') ?>#politicas">Envíos y pago con tarjeta</a></li>
        <li><a href="<?= url('contacto') ?>#politicas">Retracto de 10 días</a></li>
        <li><a href="<?= url('contacto') ?>#privacidad">Privacidad</a></li>
        <li><a href="<?= url() ?>#donde">Dónde encontrarnos</a></li></ul></div>
      <div><h4>Conversemos</h4><ul>
        <li><a href="<?= e(enlace_whatsapp('Hola María Angélica, vi tu página y quiero hacer una consulta.', 'pie')) ?>">WhatsApp +56 9 8923 4239</a></li>
        <li><a href="https://www.instagram.com/mavs_jewelry/" target="_blank" rel="noopener">Instagram @mavs_jewelry</a></li>
        <li><a href="https://www.tiktok.com/@mavs_jewerly" target="_blank" rel="noopener">TikTok @mavs_jewerly</a></li>
        <li><a href="https://www.facebook.com/profile.php?id=61570840502174" target="_blank" rel="noopener">Facebook Mavs Jewelry</a></li></ul></div>
    </div>
    <p class="firma" aria-hidden="true">Mavs <span>Jewelry</span></p>
    <div class="legal"><span>Mavs Jewelry · María Angélica Venegas · RUT [por completar] · mavenegas.s.mv@gmail.com · Santiago, Región Metropolitana</span><span>Boleta electrónica en cada compra</span></div>
  </div></footer>
  <a class="wsp-flotante" href="<?= e(enlace_whatsapp('Hola María Angélica, vi tu página y quiero hacer una consulta.', $pagina ?: 'sitio')) ?>" aria-label="Escribir por WhatsApp">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2c-1.5 0-3-.4-4.3-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.2-.4.2-.4.7-1.3.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.4.1-.7.3-.2.3-.9.9-.9 2.2s.9 2.5 1 2.7c.1.2 1.8 2.8 4.4 3.9 1.6.7 2.3.8 3.1.6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2-.1-.1-.3-.2-.5-.3z"/></svg><span>Escríbeme</span></a>
  <script src="<?= url('js/sitio.js') ?>"></script>
</body>
</html>
