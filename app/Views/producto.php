<?php
$fondos = ['var(--t-noche)', 'var(--t-ciruela)', 'var(--t-rosa)', 'var(--t-rosa-oscuro)', 'var(--t-oro)'];
$foto0 = $p['fotos'][0]['ruta'] ?? 'img/logo-mavs-negro.png';
$disponible = $p['estado'] === 'publicado' && (int)$p['stock'] > 0;
?>
<section class="banda banda-blanca ficha-banda">
  <div class="contenedor">
    <nav class="migas oscuras" aria-label="Ruta"><a href="<?= url() ?>">Inicio</a> › <a href="<?= url('catalogo') ?>">Catálogo</a> › <a href="<?= url('catalogo') ?>?tecnica=<?= e($p['tecnica']) ?>"><?= e($p['tecnica_nombre']) ?></a></nav>
  </div>
  <div class="contenedor ficha-pagina">
    <div class="galeria" style="--fondo:<?= $fondos[(int)$p['id_producto'] % 5] ?>">
      <?php if ($p['es_unica']): ?><span class="etiqueta-ficha">Pieza única</span><?php endif; ?>
      <img id="foto-principal" src="/<?= e($foto0) ?>" alt="<?= e($p['nombre']) ?>" width="1200" height="1500">
      <?php if (count($p['fotos']) > 1): ?>
        <div class="miniaturas">
          <?php foreach ($p['fotos'] as $n => $f): ?>
            <button type="button" data-foto="/<?= e($f['ruta']) ?>" aria-label="Ver foto <?= $n + 1 ?>"<?= $n === 0 ? ' aria-current="true"' : '' ?>><img src="/<?= e($f['ruta']) ?>" alt=""></button>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="ficha-datos">
      <span class="rotulo"><?= e($p['tecnica_nombre']) ?> · <?= e($p['tipo']) ?></span>
      <h1><?= e($p['nombre']) ?></h1>
      <div class="precio grande"><?= precio($p['precio']) ?></div>
      <p><?= nl2br(e($p['descripcion'])) ?></p>
      <span class="hecho-mano">✦ 100% hecha a mano por María Angélica</span>
      <div class="acciones">
        <?php if ($disponible): ?>
          <form method="post" action="<?= url('carrito/agregar') ?>"><?= csrf_campo() ?><input type="hidden" name="id" value="<?= (int)$p['id_producto'] ?>"><button class="btn btn-noche" type="submit">Agregar al carrito</button></form>
        <?php else: ?>
          <button class="btn btn-noche" type="button" disabled>Se está vendiendo en este momento</button>
        <?php endif; ?>
        <a class="btn btn-linea" href="<?= e(enlace_whatsapp('Hola, vi la pieza “' . $p['nombre'] . '” (' . precio($p['precio']) . ') en la página de Mavs Jewelry y quiero consultar por ella.', 'ficha-' . $p['codigo'])) ?>">Consultar por WhatsApp</a>
      </div>
      <dl class="detalles">
        <dt>Código</dt><dd><?= e($p['codigo']) ?></dd>
        <dt>Materiales</dt><dd><?= e($p['materiales']) ?></dd>
        <dt>Disponibilidad</dt><dd><?= $p['es_unica'] ? 'Pieza única, 1 disponible' : 'Disponible' ?></dd>
      </dl>
      <?php if ($p['piedras']): ?>
        <div class="significados">
          <?php foreach ($p['piedras'] as $pi): ?>
            <div><?= gema($pi['nombre'], 'mini') ?><div><h3><?= e($pi['nombre']) ?></h3><p><?= e($pi['significado']) ?></p></div></div>
          <?php endforeach; ?>
          <a class="enlace-politicas" href="<?= url('piedras') ?>#significados">Significado de todas las piedras</a>
        </div>
      <?php endif; ?>
      <ul class="garantias">
        <li><strong>Garantía legal de 6 meses</strong> por fallas de fabricación.</li>
        <li><strong>Retracto de 10 días</strong> desde que la recibes.</li>
        <li><strong>Despacho por Starken</strong>, por pagar al recibir.</li>
      </ul>
      <a class="enlace-politicas" href="<?= url('contacto') ?>#politicas">Ver políticas de venta</a>
    </div>
  </div>
</section>
