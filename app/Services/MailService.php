<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

/** Correos del sitio por SMTP del hosting (CU-12, RF-09). Un error de correo nunca interrumpe la venta. */
final class MailService
{
    public function avisarVenta(array $pedido): void {
        $detalle = $this->detalle($pedido);
        $this->enviar($_ENV['CORREO_PROPIETARIA'], 'Nueva venta ' . $pedido['codigo'],
            "Tienes una venta pagada en el sitio.\n\n" . $detalle .
            "\nDespacho Starken por pagar a: {$pedido['nombre_cliente']}, {$pedido['direccion']}, " .
            "{$pedido['comuna']}, {$pedido['region']}. Teléfono {$pedido['telefono']}.\n\nRecuerda emitir la boleta electrónica.");
        $this->enviar($pedido['correo'], 'Confirmamos tu compra ' . $pedido['codigo'],
            "Hola {$pedido['nombre_cliente']}, recibimos tu pago. Gracias por tu compra en Mavs Jewelry.\n\n" . $detalle .
            "\nTe enviaremos tu pedido por Starken (por pagar al recibir) y te avisaremos cuando salga.\n" .
            "Tienes garantía legal de 6 meses y 10 días para retractarte desde que lo recibes.");
    }

    public function avisarReembolso(array $pedido): void {
        $texto = "El pago del pedido {$pedido['codigo']} llegó después de que venciera la reserva y la pieza ya se había vendido. " .
                 "Solicitamos la devolución completa del pago en Mercado Pago.";
        $this->enviar($pedido['correo'], 'Devolución de tu pago ' . $pedido['codigo'],
            "Hola {$pedido['nombre_cliente']}, lo sentimos mucho. " . $texto .
            "\nSi quieres una pieza parecida, escríbenos por WhatsApp.");
        $this->enviar($_ENV['CORREO_PROPIETARIA'], 'Pago reembolsado ' . $pedido['codigo'], $texto);
    }

    public function avisarRechazo(array $pedido): void {
        $this->enviar($pedido['correo'], 'Tu pago no se completó ' . $pedido['codigo'],
            "Hola {$pedido['nombre_cliente']}, Mercado Pago rechazó el pago del pedido {$pedido['codigo']}. " .
            "Las piezas volvieron al catálogo: puedes intentarlo de nuevo con otra tarjeta.\n" . $_ENV['APP_URL'] . "/carrito");
    }

    private function detalle(array $pedido): string {
        $lineas = array_map(fn($i) => '- ' . $i['nombre'] . ': ' . precio($i['precio_unitario']),
                            Pedido::items((int)$pedido['id_pedido']));
        return implode("\n", $lineas) . "\nTotal: " . precio($pedido['total']) . "\n";
    }

    private function enviar(string $para, string $asunto, string $texto): void {
        try {
            $m = new PHPMailer(true);
            $m->isSMTP();
            $m->Host = $_ENV['SMTP_HOST'];
            $m->Port = (int)$_ENV['SMTP_PORT'];
            $m->SMTPAuth = true;
            $m->Username = $_ENV['SMTP_USER'];
            $m->Password = $_ENV['SMTP_PASS'];
            $m->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $m->CharSet = 'UTF-8';
            $m->setFrom($_ENV['CORREO_REMITENTE'], 'Mavs Jewelry');
            $m->addAddress($para);
            $m->Subject = $asunto;
            $m->Body = $texto;
            $m->send();
        } catch (MailException $e) {
            error_log('No se pudo enviar el correo "' . $asunto . '": ' . $e->getMessage());
        }
    }
}
