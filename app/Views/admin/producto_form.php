<?php
$err = fn(string $k) => isset($errores[$k]) ? '<span class="error">' . e($errores[$k]) . '</span>' : '';
$cls = fn(string $k) => isset($errores[$k]) ? ' con-error' : '';
?>
<form class="form-pieza tarjeta" method="post" action="<?= url('admin/productos/guardar') ?>" enctype="multipart/form-data" novalidate>
  <div class="vista-cab"><h2><?= $p['id_producto'] ? 'Editar pieza' : 'Nueva pieza' ?></h2><a class="accion" href="<?= url('admin/productos') ?>">Volver</a></div>
  <?= csrf_campo() ?>
  <input type="hidden" name="id_producto" value="<?= (int)$p['id_producto'] ?>">
  <?php if ($fotos): ?><div class="fotos-actuales"><?php foreach ($fotos as $f): ?><img src="/<?= e($f['ruta']) ?>" alt="" width="80" height="100"><?php endforeach; ?></div><?php endif; ?>
  <div class="campo fotos-campo<?= $cls('foto') ?>">
    <label for="foto"><?= $fotos ? 'Agregar otra foto' : 'Fotografía' ?></label>
    <input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp" capture="environment">
    <small>Desde la cámara o la galería. JPG, PNG o WebP de hasta 8 MB; el sitio la ajusta a 1200 px.</small><?= $err('foto') ?>
  </div>
  <div class="campos-2">
    <div class="campo<?= $cls('nombre') ?>"><label for="nombre">Nombre</label><input id="nombre" name="nombre" maxlength="120" value="<?= e($p['nombre']) ?>" required><?= $err('nombre') ?></div>
    <div class="campo<?= $cls('precio') ?>"><label for="precio">Precio (CLP)</label><input id="precio" name="precio" type="number" min="1000" max="1000000" step="100" value="<?= e($p['precio']) ?>" required><?= $err('precio') ?></div>
    <div class="campo<?= $cls('id_tecnica') ?>"><label for="id_tecnica">Técnica</label><select id="id_tecnica" name="id_tecnica">
      <?php foreach ($tecnicas as $t): ?><option value="<?= (int)$t['id_tecnica'] ?>"<?= (int)$p['id_tecnica'] === (int)$t['id_tecnica'] ? ' selected' : '' ?>><?= e($t['nombre']) ?></option><?php endforeach; ?>
    </select><?= $err('id_tecnica') ?></div>
    <div class="campo<?= $cls('id_tipo') ?>"><label for="id_tipo">Tipo</label><select id="id_tipo" name="id_tipo">
      <?php foreach ($tipos as $t): ?><option value="<?= (int)$t['id_tipo'] ?>"<?= (int)$p['id_tipo'] === (int)$t['id_tipo'] ? ' selected' : '' ?>><?= e($t['nombre']) ?></option><?php endforeach; ?>
    </select><?= $err('id_tipo') ?></div>
    <div class="campo<?= $cls('stock') ?>"><label for="stock">Stock</label><input id="stock" name="stock" type="number" min="0" max="99" value="<?= (int)$p['stock'] ?>"><?= $err('stock') ?></div>
    <div class="campo<?= $cls('estado') ?>"><label for="estado">Estado</label><select id="estado" name="estado">
      <?php foreach (['borrador' => 'Borrador', 'publicado' => 'Publicado', 'oculto' => 'Oculto'] as $k => $t): ?><option value="<?= $k ?>"<?= $p['estado'] === $k ? ' selected' : '' ?>><?= $t ?></option><?php endforeach; ?>
    </select><?= $err('estado') ?></div>
  </div>
  <label class="casilla-linea"><input type="checkbox" name="es_unica" value="1"<?= $p['es_unica'] ? ' checked' : '' ?>> Pieza única (stock 0 o 1)</label>
  <div class="campo<?= $cls('materiales') ?>"><label for="materiales">Materiales</label><input id="materiales" name="materiales" maxlength="255" value="<?= e($p['materiales']) ?>"><?= $err('materiales') ?></div>
  <fieldset class="piedras-campo"><legend>Piedras</legend>
    <?php foreach ($piedras as $pi): ?><label><input type="checkbox" name="piedras[]" value="<?= (int)$pi['id_piedra'] ?>"<?= in_array((int)$pi['id_piedra'], $p['piedras'], true) ? ' checked' : '' ?>> <?= e($pi['nombre']) ?></label><?php endforeach; ?>
  </fieldset>
  <div class="campo<?= $cls('descripcion') ?>"><label for="descripcion">Descripción</label><textarea id="descripcion" name="descripcion" rows="3" maxlength="2000"><?= e($p['descripcion']) ?></textarea><?= $err('descripcion') ?></div>
  <div class="acciones"><button class="btn btn-noche" type="submit">Guardar pieza</button><a class="btn btn-linea" href="<?= url('admin/productos') ?>">Cancelar</a></div>
</form>
