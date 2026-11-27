<?php
$estado = $pedido['estado'] ?? '';
[$titulo2, $texto] = match (true) {
    in_array($estado, ['pagado', 'en_preparacion', 'enviado'], true) => ['¡Gracias por tu compra!', 'Recibimos tu pago. Te enviamos la confirmación por correo y te avisaremos cuando tu pedido salga por Starken.'],
    $estado === 'rechazado' || $estadoMp === 'rejected' || $estadoMp === 'failure' => ['Tu pago no se completó', 'Mercado Pago no aprobó el pago. Las piezas volvieron al catálogo; puedes intentarlo de nuevo con otra tarjeta.'],
    $estado === 'expirado' => ['La reserva venció', 'Pasaron más de 15 minutos sin pago y las piezas volvieron al catálogo. Puedes agregarlas de nuevo si siguen disponibles.'],
    $estado === 'reembolsado' => ['Devolvimos tu pago', 'Tu pago llegó cuando la pieza ya se había vendido. Solicitamos la devolución completa en Mercado Pago.'],
    default => ['Estamos confirmando tu pago', 'Mercado Pago nos avisará en unos minutos. Te escribiremos al correo apenas se confirme.'],
};
?>
<section class="banda banda-blanca ficha-banda">
  <div class="contenedor llamado">
    <h1 class="titulo-pagina"><?= e($titulo2) ?></h1>
    <p><?= e($texto) ?></p>
    <?php if ($pedido): ?><p>Código de tu pedido: <strong><?= e($pedido['codigo']) ?></strong></p><?php endif; ?>
    <div class="acciones"><a class="btn btn-noche" href="<?= url('catalogo') ?>">Seguir mirando</a>
      <a class="btn btn-linea" href="<?= e(enlace_whatsapp('Hola, tengo una consulta por mi pedido ' . ($pedido['codigo'] ?? '') . '.', 'resultado-pago')) ?>">Consultar por WhatsApp</a></div>
  </div>
</section>
