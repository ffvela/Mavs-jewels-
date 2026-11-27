<?php
$nombres = ['publicado' => 'Publicado', 'reservado' => 'Reservado', 'borrador' => 'Borrador', 'oculto' => 'Oculto', 'vendido' => 'Vendido'];
$fondos = ['#dfdbe9', '#e7d4ec', '#f8dce9', '#efc8dc', '#f1e3c8'];
?>
<div class="vista-cab"><h1>Productos</h1><a class="btn btn-noche" href="<?= url('admin/productos/editar') ?>">Nueva pieza</a></div>
<form class="barra-panel" method="get" action="<?= url('admin/productos') ?>">
  <label class="buscar"><span class="oculto">Buscar</span><input type="search" name="q" value="<?= e($buscar) ?>" placeholder="Buscar por nombre o código"></label>
  <div class="chips" role="group" aria-label="Filtrar por estado">
    <a class="chip" href="<?= url('admin/productos') ?>" aria-pressed="<?= $estado === '' ? 'true' : 'false' ?>">Todos<span class="n"><?= array_sum($conteo) ?></span></a>
    <?php foreach ($nombres as $clave => $texto): ?>
      <a class="chip" href="<?= url('admin/productos') ?>?estado=<?= $clave ?>" aria-pressed="<?= $estado === $clave ? 'true' : 'false' ?>"><?= $texto ?><span class="n"><?= (int)($conteo[$clave] ?? 0) ?></span></a>
    <?php endforeach; ?>
  </div>
</form>
<ul class="lista-panel">
  <?php foreach ($productos as $p): ?>
    <li class="fila-producto">
      <span class="mini" style="background:<?= $fondos[(int)$p['id_producto'] % 5] ?>"><img src="/<?= e($p['foto'] ?? 'img/logo-mavs-negro.png') ?>" alt=""></span>
      <div class="info"><span class="codigo"><?= e($p['codigo']) ?></span><h3><?= e($p['nombre']) ?></h3>
        <span class="sub"><?= e($p['tecnica_nombre']) ?> · <?= e($p['tipo']) ?> · <?= precio($p['precio']) ?> · stock <?= (int)$p['stock'] ?></span></div>
      <span class="estado e-<?= e($p['estado']) ?>"><?= $nombres[$p['estado']] ?><?php if ($p['estado'] === 'reservado' && $p['reservado_hasta']): ?> · hasta <?= date('H:i', strtotime($p['reservado_hasta'])) ?><?php endif; ?></span>
      <div class="acc">
        <a class="accion" href="<?= url('admin/productos/editar') ?>?id=<?= (int)$p['id_producto'] ?>">Editar</a>
        <?php if ($p['estado'] !== 'vendido'): ?>
          <form method="post" action="<?= url('admin/productos/estado') ?>"><?= csrf_campo() ?><input type="hidden" name="id" value="<?= (int)$p['id_producto'] ?>">
            <input type="hidden" name="estado" value="<?= $p['estado'] === 'oculto' ? 'publicado' : 'oculto' ?>">
            <button type="submit"<?= $p['estado'] === 'reservado' ? ' disabled' : '' ?>><?= $p['estado'] === 'oculto' ? 'Publicar' : 'Ocultar' ?></button></form>
          <form method="post" action="<?= url('admin/productos/estado') ?>"><?= csrf_campo() ?><input type="hidden" name="id" value="<?= (int)$p['id_producto'] ?>">
            <input type="hidden" name="estado" value="vendido"><button type="submit" class="fuerte">Marcar vendida</button></form>
        <?php endif; ?>
      </div>
    </li>
  <?php endforeach; ?>
  <?php if (!$productos): ?><li class="vacio">No hay piezas con ese filtro.</li><?php endif; ?>
</ul>
