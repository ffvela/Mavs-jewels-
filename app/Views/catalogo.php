<?php
$actual = array_values(array_filter($tecnicas, fn($t) => $t['slug'] === $filtros['tecnica']))[0];
$enlace = fn(array $cambios) => url('catalogo') . '?' . http_build_query(array_filter(array_merge($filtros, $cambios)));
?>
<section class="hero-full medio">
  <img src="/img/hero-azul.webp" alt="Aros de mostacilla azul y perla junto a un cuarzo rosado" width="2000" height="1653">
  <div class="contenedor">
    <nav class="migas" aria-label="Ruta"><a href="<?= url() ?>">Inicio</a> › Catálogo</nav>
    <h1>Catálogo</h1>
    <p class="bajada">Todas las piezas tienen precio. Cuando una pieza única se vende, deja de mostrarse.</p>
  </div>
</section>

<div class="subbarra"><div class="contenedor">
  <div class="lista-tec" role="group" aria-label="Técnica">
    <?php foreach ($tecnicas as $t): ?>
      <a class="opcion-tec" href="<?= $enlace(['tecnica' => $t['slug'], 'tipo' => 0]) ?>" aria-pressed="<?= $t['slug'] === $filtros['tecnica'] ? 'true' : 'false' ?>"><?= e($t['nombre']) ?></a>
    <?php endforeach; ?>
  </div>
</div></div>

<section class="banda banda-blanca" style="padding-top:44px">
  <div class="contenedor">
    <div class="barra-resultados">
      <div>
        <h2 class="titulo-grilla" style="margin:0"><?= e($actual['nombre']) ?></h2>
        <span class="cuenta"><strong><?= count($productos) ?></strong> <?= count($productos) === 1 ? 'pieza' : 'piezas' ?></span>
      </div>
    </div>
    <div class="filtro" style="margin-bottom:14px"><div class="chips" role="group" aria-label="Tipo de pieza">
      <a class="chip" href="<?= $enlace(['tipo' => 0]) ?>" aria-pressed="<?= $filtros['tipo'] ? 'false' : 'true' ?>">Todos</a>
      <?php foreach ($tipos as $t): ?>
        <a class="chip" href="<?= $enlace(['tipo' => $t['id_tipo']]) ?>" aria-pressed="<?= (int)$t['id_tipo'] === $filtros['tipo'] ? 'true' : 'false' ?>"><?= e($t['nombre']) ?></a>
      <?php endforeach; ?>
    </div></div>
    <div class="filtro" style="margin-bottom:34px"><div class="chips" role="group" aria-label="Precio">
      <?php foreach (CatalogoController::RANGOS as $valor => $texto): ?>
        <a class="chip" href="<?= $enlace(['precio_max' => $valor]) ?>" aria-pressed="<?= (string)$filtros['precio_max'] === (string)$valor ? 'true' : 'false' ?>"><?= e($texto) ?></a>
      <?php endforeach; ?>
    </div></div>
    <div class="grilla-productos">
      <?php foreach ($productos as $p) { $p['tecnica_nombre'] = $actual['nombre']; require __DIR__ . '/parciales/tarjeta.php'; } ?>
      <?php if (!$productos): ?>
        <div class="vacio">No hay piezas con esos filtros. Prueba con otro tipo o precio, o <a href="<?= url('personalizadas') ?>">pide una pieza personalizada</a>.</div>
      <?php endif; ?>
    </div>
  </div>
</section>
