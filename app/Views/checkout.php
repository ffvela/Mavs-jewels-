<?php
$total = array_sum(array_column($items, 'precio'));
$v = fn(string $k) => e($datos[$k] ?? '');
$err = fn(string $k) => isset($errores[$k]) ? '<span class="error">' . e($errores[$k]) . '</span>' : '';
$cls = fn(string $k) => isset($errores[$k]) ? ' con-error' : '';
$rm = ($datos['region'] ?? '') === 'Metropolitana de Santiago';   // valores de ejemplo hasta tener la cotización de Starken
?>
<section class="banda banda-blanca ficha-banda">
  <div class="contenedor">
    <nav class="migas oscuras" aria-label="Ruta"><a href="<?= url() ?>">Inicio</a> › <a href="<?= url('carrito') ?>">Carrito</a> › Datos de despacho</nav>
    <h1 class="titulo-pagina">Datos de despacho</h1>
    <ol class="pasos-compra" aria-label="Pasos de la compra"><li>Carrito</li><li aria-current="step">Datos de despacho</li><li>Pago en Mercado Pago</li></ol>
    <?php if ($errores): ?><p class="aviso error" role="alert">Revisa <?= count($errores) === 1 ? '1 campo marcado' : count($errores) . ' campos marcados' ?> antes de pagar. Tus datos se conservan.</p><?php endif; ?>
    <div class="despacho-grid">
      <form class="tarjeta" id="form-despacho" method="post" action="<?= url('checkout/confirmar') ?>" novalidate>
        <?= csrf_campo() ?>
        <h2>¿A quién se lo enviamos?</h2>
        <div class="campos-2">
          <div class="campo<?= $cls('nombre') ?>"><label for="nombre">Nombre completo</label><input id="nombre" name="nombre" value="<?= $v('nombre') ?>" autocomplete="name" maxlength="120" required><?= $err('nombre') ?></div>
          <div class="campo<?= $cls('rut') ?>"><label for="rut">RUT</label><input id="rut" name="rut" value="<?= $v('rut') ?>" placeholder="12.345.678-9" maxlength="12" required><?= $err('rut') ?></div>
          <div class="campo<?= $cls('correo') ?>"><label for="correo">Correo</label><input id="correo" name="correo" type="email" value="<?= $v('correo') ?>" autocomplete="email" maxlength="150" required><?= $err('correo') ?></div>
          <div class="campo<?= $cls('telefono') ?>"><label for="telefono">Teléfono</label><input id="telefono" name="telefono" type="tel" value="<?= $v('telefono') ?>" placeholder="+56 9 1234 5678" maxlength="15" required><?= $err('telefono') ?></div>
        </div>
        <h2>Dirección de despacho</h2>
        <div class="campo<?= $cls('direccion') ?>"><label for="direccion">Calle y número</label><input id="direccion" name="direccion" value="<?= $v('direccion') ?>" autocomplete="street-address" maxlength="200" required><?= $err('direccion') ?></div>
        <div class="campos-2">
          <div class="campo<?= $cls('comuna') ?>"><label for="comuna">Comuna</label><input id="comuna" name="comuna" value="<?= $v('comuna') ?>" maxlength="60" required><?= $err('comuna') ?></div>
          <div class="campo<?= $cls('region') ?>"><label for="region">Región</label><select id="region" name="region" required>
            <option value="">Elige tu región</option>
            <?php foreach ($regiones as $r): ?><option<?= ($datos['region'] ?? '') === $r ? ' selected' : '' ?>><?= e($r) ?></option><?php endforeach; ?>
          </select><?= $err('region') ?></div>
        </div>
        <div class="estimado">
          <div><span>Costo estimado Starken</span><strong><?= $rm ? '$3.500 a $5.000' : '$4.500 a $7.500' ?></strong></div>
          <div><span>Plazo estimado</span><strong><?= $rm ? '1 a 3 días hábiles' : '2 a 5 días hábiles' ?></strong></div>
          <p>El despacho es por pagar: lo pagas a Starken al recibir. Puedes calcular el valor exacto en el <a href="https://www.starken.cl/" target="_blank" rel="noopener">cotizador de Starken</a>.</p>
        </div>
        <fieldset class="opciones-radio<?= $cls('ferias') ?>"><legend>¿Nos conocías de ferias?</legend>
          <label><input type="radio" name="ferias" value="Sí"<?= ($datos['ferias'] ?? '') === 'Sí' ? ' checked' : '' ?>> Sí</label>
          <label><input type="radio" name="ferias" value="No"<?= ($datos['ferias'] ?? '') === 'No' ? ' checked' : '' ?>> No</label><?= $err('ferias') ?>
        </fieldset>
        <div class="casilla<?= $cls('politicas') ?>"><label><input type="checkbox" name="politicas" value="1"<?= !empty($datos['politicas']) ? ' checked' : '' ?>><span>Acepto las <a href="<?= url('contacto') ?>#politicas" target="_blank">políticas de venta</a> (garantía legal de 6 meses, retracto de 10 días y despacho por pagar).</span></label><?= $err('politicas') ?></div>
        <div class="casilla<?= $cls('privacidad') ?>"><label><input type="checkbox" name="privacidad" value="1"<?= !empty($datos['privacidad']) ? ' checked' : '' ?>><span>Acepto la <a href="<?= url('contacto') ?>#privacidad" target="_blank">política de privacidad</a> y el uso de mis datos para esta compra.</span></label><?= $err('privacidad') ?></div>
        <button class="btn btn-noche btn-ancho" type="submit">Pagar con tarjeta</button>
      </form>
      <aside class="resumen" aria-label="Tu pedido">
        <h2>Tu pedido</h2>
        <ul class="resumen-items">
          <?php foreach ($items as $i): ?>
            <li><span class="mini-foto" style="--fondo:var(--t-ciruela)"><img src="/<?= e($i['foto']) ?>" alt=""></span><span><?= e($i['nombre']) ?></span><span class="precio"><?= precio($i['precio']) ?></span></li>
          <?php endforeach; ?>
        </ul>
        <div class="total"><span>Total a pagar hoy</span><div class="precio"><?= precio($total) ?></div></div>
        <small>Despacho Starken por pagar al recibir. Al pagar, tus piezas quedan reservadas por 15 minutos.</small>
      </aside>
    </div>
  </div>
</section>
