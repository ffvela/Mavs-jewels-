<?php $total = array_sum(array_column($items, 'precio')); ?>
<section class="banda banda-blanca ficha-banda">
  <div class="contenedor">
    <nav class="migas oscuras" aria-label="Ruta"><a href="<?= url() ?>">Inicio</a> › Carrito</nav>
    <h1 class="titulo-pagina">Tu carrito</h1>
    <?php if (!$items): ?>
      <div class="vacio">Tu carrito está vacío. <a href="<?= url('catalogo') ?>">Ver el catálogo</a></div>
    <?php else: ?>
      <div class="carrito-pagina">
        <div class="lista-carrito">
          <?php foreach ($items as $i): ?>
            <article class="linea-carrito">
              <a class="mini-foto" href="<?= url('producto') ?>?id=<?= (int)$i['id_producto'] ?>" style="--fondo:var(--t-ciruela)"><img src="/<?= e($i['foto']) ?>" alt="<?= e($i['nombre']) ?>"></a>
              <div><span class="tec"><?= e($i['tecnica_nombre']) ?></span><h3><?= e($i['nombre']) ?></h3>
                <span class="cantidad"><?= $i['es_unica'] ? 'Pieza única · 1 unidad' : '1 unidad' ?></span>
                <form method="post" action="<?= url('carrito/quitar') ?>"><?= csrf_campo() ?><input type="hidden" name="id" value="<?= (int)$i['id_producto'] ?>"><button class="quitar" type="submit">Quitar</button></form></div>
              <span class="precio"><?= precio($i['precio']) ?></span>
            </article>
          <?php endforeach; ?>
        </div>
        <aside class="resumen" aria-label="Resumen de la compra">
          <h2>Resumen</h2>
          <dl><dt><?= count($items) ?> <?= count($items) === 1 ? 'pieza' : 'piezas' ?></dt><dd><?= precio($total) ?></dd>
              <dt>Despacho Starken</dt><dd>Por pagar al recibir</dd></dl>
          <div class="total"><span>Total a pagar hoy</span><div class="precio"><?= precio($total) ?></div></div>
          <a class="btn btn-noche" href="<?= url('checkout') ?>">Continuar con la compra</a>
          <small>Pago con tarjeta de crédito o débito en Mercado Pago. Cada pieza única se reserva para ti por 15 minutos al pasar al pago.</small>
        </aside>
      </div>
    <?php endif; ?>
  </div>
</section>
