<?php
declare(strict_types=1);

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;

final class MercadoPagoService
{
    public function crearPreferencia(array $pedido, array $items): string {
        MercadoPagoConfig::setAccessToken($_ENV['MP_ACCESS_TOKEN']);

        $preferencia = (new PreferenceClient())->create([
            'items' => array_map(fn($i) => [
                'title'       => $i['nombre'],
                'quantity'    => (int)$i['cantidad'],
                'unit_price'  => (int)$i['precio_unitario'],
                'currency_id' => 'CLP',
            ], $items),
            'external_reference' => $pedido['codigo'],          // MJ-2026-000045
            'notification_url'   => $_ENV['APP_URL'] . '/pago/webhook',
            'back_urls' => [
                'success' => $_ENV['APP_URL'] . '/pago/resultado',
                'pending' => $_ENV['APP_URL'] . '/pago/resultado',
                'failure' => $_ENV['APP_URL'] . '/pago/resultado',
            ],
            'auto_return' => 'approved',
            'expires' => true,             // el enlace vence junto con la reserva (RF-21)
            'expiration_date_to' => date('c', time() + 15 * 60),
        ]);
        return $preferencia->init_point;   // URL a la que se redirige al comprador
    }

    /**
     * Valida la firma x-signature de la notificación (RF-22). Mercado Pago firma con HMAC-SHA256
     * el texto "id:{data.id};request-id:{x-request-id};ts:{ts};" usando la clave secreta de Webhooks.
     */
    public static function firmaValida(string $dataId): bool {
        $firma = $_SERVER['HTTP_X_SIGNATURE'] ?? '';
        $idSolicitud = $_SERVER['HTTP_X_REQUEST_ID'] ?? '';
        $partes = [];
        foreach (explode(',', $firma) as $par) {
            [$clave, $valor] = array_map('trim', explode('=', $par, 2)) + [1 => ''];
            $partes[$clave] = $valor;
        }
        if (empty($partes['ts']) || empty($partes['v1']) || empty($_ENV['MP_WEBHOOK_SECRET'])) {
            return false;
        }
        $id = ctype_alnum($dataId) ? strtolower($dataId) : $dataId;
        $manifiesto = 'id:' . $id . ';request-id:' . $idSolicitud . ';ts:' . $partes['ts'] . ';';
        $esperada = hash_hmac('sha256', $manifiesto, $_ENV['MP_WEBHOOK_SECRET']);
        return hash_equals($esperada, $partes['v1']);
    }
}
