<?php
$nombres = ['pendiente' => 'Pendiente de pago', 'pagado' => 'Pagado', 'rechazado' => 'Rechazado', 'expirado' => 'Expirado',
            'en_preparacion' => 'En preparación', 'enviado' => 'Enviado', 'reembolsado' => 'Reembolsado'];
?>
<div class="vista-cab"><h1>Pedidos</h1></div>
<div class="barra-panel"><div class="chips" role="group" aria-label="Filtrar pedidos por estado">
  <a class="chip" href="<?= url('admin/pedidos') ?>" aria-pressed="<?= $estado === '' ? 'true' : 'false' ?>">Todos</a>
  <?php foreach ($nombres as $k => $t): ?><a class="chip" href="<?= url('admin/pedidos') ?>?estado=<?= $k ?>" aria-pressed="<?= $estado === $k ? 'true' : 'false' ?>"><?= $t ?></a><?php endforeach; ?>
</div></div>
<div class="pedidos-grid">
  <ul class="lista-panel lista-pedidos">
    <?php foreach ($pedidos as $pd): ?>
      <li><a class="fila-pedido" href="<?= url('admin/pedidos') ?>?<?= http_build_query(['estado' => $estado, 'id' => $pd['id_pedido']]) ?>"<?= $detalle && (int)$detalle['id_pedido'] === (int)$pd['id_pedido'] ? ' aria-current="true"' : '' ?>>
        <span class="codigo"><?= e($pd['codigo']) ?></span><strong><?= e($pd['nombre_cliente']) ?></strong><span class="sub"><?= date('d-m-Y H:i', strtotime($pd['fecha_creacion'])) ?></span>
        <span class="estado e-<?= e($pd['estado']) ?>"><?= $nombres[$pd['estado']] ?></span><span class="precio"><?= precio($pd['total']) ?></span></a></li>
    <?php endforeach; ?>
    <?php if (!$pedidos): ?><li class="vacio">No hay pedidos en este estado.</li><?php endif; ?>
  </ul>
  <?php if ($detalle): ?>
    <article class="detalle-pedido tarjeta">
      <span class="codigo"><?= e($detalle['codigo']) ?></span><h2><?= e($detalle['nombre_cliente']) ?></h2>
      <span class="estado e-<?= e($detalle['estado']) ?>"><?= $nombres[$detalle['estado']] ?></span>
      <dl>
        <dt>Fecha</dt><dd><?= date('d-m-Y H:i', strtotime($detalle['fecha_creacion'])) ?></dd>
        <dt>RUT</dt><dd><?= e($detalle['rut']) ?></dd>
        <dt>Contacto</dt><dd><?= e($detalle['correo']) ?> · <?= e($detalle['telefono']) ?></dd>
        <dt>Despacho</dt><dd>Starken por pagar · <?= e($detalle['direccion']) ?>, <?= e($detalle['comuna']) ?>, <?= e($detalle['region']) ?></dd>
        <dt>¿Nos conocía de ferias?</dt><dd><?= $detalle['conoce_ferias'] === null ? '—' : ($detalle['conoce_ferias'] ? 'Sí' : 'No') ?></dd>
        <dt>Total</dt><dd><?= precio($detalle['total']) ?></dd>
      </dl>
      <h3>Piezas</h3>
      <ul class="piezas-pedido"><?php foreach ($items as $it): ?><li><?= e($it['codigo']) ?> · <?= e($it['nombre']) ?> · <?= precio($it['precio_unitario']) ?></li><?php endforeach; ?></ul>
      <?php if (in_array($detalle['estado'], ['pagado', 'en_preparacion'], true)): ?>
        <form method="post" action="<?= url('admin/pedidos/estado') ?>" class="campo"><?= csrf_campo() ?><input type="hidden" name="id" value="<?= (int)$detalle['id_pedido'] ?>">
          <label for="cambio-estado">Cambiar estado</label>
          <select id="cambio-estado" name="estado"><?php if ($detalle['estado'] === 'pagado'): ?><option value="en_preparacion">En preparación</option><?php endif; ?><option value="enviado">Enviado</option></select>
          <button class="btn btn-noche" type="submit">Guardar estado</button></form>
      <?php endif; ?>
      <?php if (in_array($detalle['estado'], ['pagado', 'en_preparacion', 'enviado'], true)): ?>
        <details class="form-reembolso"><summary>Registrar reembolso</summary>
          <form method="post" action="<?= url('admin/pedidos/reembolso') ?>"><?= csrf_campo() ?><input type="hidden" name="id" value="<?= (int)$detalle['id_pedido'] ?>">
            <label class="casilla-linea"><input type="checkbox" name="reponer" value="1"> Volver a publicar las piezas</label>
            <button class="btn btn-noche" type="submit">Confirmar reembolso</button>
            <small>Retracto (10 días) o garantía legal (6 meses). La devolución del dinero se hace en Mercado Pago.</small></form>
        </details>
      <?php endif; ?>
      <h3>Historial</h3>
      <ul class="piezas-pedido"><?php foreach ($historial as $h): ?><li><?= date('d-m H:i', strtotime($h['fecha'])) ?> · <?= e($nombres[$h['estado_nuevo']] ?? $h['estado_nuevo']) ?></li><?php endforeach; ?></ul>
    </article>
  <?php endif; ?>
</div>
