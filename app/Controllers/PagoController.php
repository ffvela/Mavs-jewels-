<?php
declare(strict_types=1);

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Payment\PaymentRefundClient;

final class PagoController
{
    /** Notificación de Mercado Pago (RF-22). Siempre consulta el pago a la API antes de confirmar. */
    public function webhook(): void {
        $datos = json_decode(file_get_contents('php://input'), true) ?? [];
        $tipo   = $datos['type'] ?? ($_GET['type'] ?? '');
        $idPago = $datos['data']['id'] ?? ($_GET['data_id'] ?? null);

        if ($tipo === 'payment' && $idPago) {
            // validar la firma x-signature de la notificación antes de continuar (RF-22)
            if (!MercadoPagoService::firmaValida((string)($_GET['data_id'] ?? $idPago))) {
                http_response_code(401);
                return;
            }
            MercadoPagoConfig::setAccessToken($_ENV['MP_ACCESS_TOKEN']);
            $pago   = (new PaymentClient())->get((int)$idPago);   // consulta a la API
            $pedido = Pedido::buscarPorCodigo((string)$pago->external_reference);

            if ($pedido && $pago->status === 'approved'
                && (int)$pago->transaction_amount === (int)$pedido['total']) {
                if (Pedido::confirmarPago((int)$pedido['id_pedido'], (string)$pago->id)) {
                    (new MailService())->avisarVenta($pedido);        // CU-12
                } elseif ($pedido['estado'] === 'expirado') {       // pago tardío (CU-05, flujo 5c)
                    if (Pedido::confirmarPago((int)$pedido['id_pedido'], (string)$pago->id, 'expirado')) {
                        (new MailService())->avisarVenta($pedido);    // la pieza seguía disponible
                    } else {                                          // la pieza ya se vendió
                        (new PaymentRefundClient())->refund((int)$pago->id, (float)$pago->transaction_amount);
                        Pedido::marcarReembolsado((int)$pedido['id_pedido'], (string)$pago->id);
                        (new MailService())->avisarReembolso($pedido); // a la compradora y la propietaria
                    }
                }
            }
        }
        if (isset($pedido, $pago) && in_array($pago->status, ['rejected', 'cancelled'], true)) {
            Pedido::rechazar((int)$pedido['id_pedido']); // pedido rechazado y pieza disponible
            (new MailService())->avisarRechazo($pedido);
        }
        http_response_code(200);   // Mercado Pago reintenta si no recibe 200
    }

    /**
     * Página de retorno desde Mercado Pago. Solo informa: el estado real del pedido
     * lo fija el webhook, nunca los parámetros de esta URL.
     */
    public function resultado(): void {
        $codigo = (string)($_GET['external_reference'] ?? ($_SESSION['ultimo_pedido'] ?? ''));
        $pedido = $codigo !== '' ? Pedido::buscarPorCodigo($codigo) : null;
        $estadoMp = (string)($_GET['status'] ?? $_GET['collection_status'] ?? '');
        vista('pago_resultado', [
            'titulo' => 'Resultado del pago · Mavs Jewelry', 'pagina' => 'carrito',
            'pedido' => $pedido, 'estadoMp' => $estadoMp,
        ]);
    }
}
