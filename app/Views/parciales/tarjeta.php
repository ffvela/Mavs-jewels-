<?php
/** Tarjeta de producto. Espera $p con id_producto, nombre, precio y foto. El color pastel se alterna por pieza. */
$fondos = ['var(--t-noche)', 'var(--t-ciruela)', 'var(--t-rosa)', 'var(--t-rosa-oscuro)', 'var(--t-oro)'];
$fondo = $fondos[(int)$p['id_producto'] % 5];
?>
<article class="producto">
  <a class="foto" href="<?= url('producto') ?>?id=<?= (int)$p['id_producto'] ?>" style="--fondo:<?= $fondo ?>" aria-label="Ver ficha de <?= e($p['nombre']) ?>">
    <?php if (!empty($p['es_unica'])): ?><span class="etiqueta">Pieza única</span><?php endif; ?>
    <span class="marco"><img src="/<?= e($p['foto'] ?? 'img/logo-mavs-negro.png') ?>" alt="<?= e($p['nombre']) ?>" loading="lazy" width="640" height="800"></span>
    <span><span class="nombre"><?= e($p['nombre']) ?></span>
    <?php if (!empty($p['tecnica_nombre'])): ?><span class="sub"><?= e($p['tecnica_nombre']) ?></span><?php endif; ?></span>
  </a>
  <div class="pie"><span class="precio"><?= precio($p['precio']) ?></span>
    <form method="post" action="<?= url('carrito/agregar') ?>"><?= csrf_campo() ?><input type="hidden" name="id" value="<?= (int)$p['id_producto'] ?>"><button class="agregar" type="submit">Agregar</button></form>
  </div>
</article>
