<?php
declare(strict_types=1);

/** Páginas de contenido: personalizadas, piedras, nosotros y contacto. */
final class PaginaController
{
    public function personalizadas(): void {
        vista('personalizadas', ['titulo' => 'Personalizadas · Mavs Jewelry', 'pagina' => 'personalizadas']);
    }

    public function piedras(): void {
        vista('piedras', [
            'titulo'  => 'Piedras · Mavs Jewelry',
            'pagina'  => 'piedras',
            'piedras' => Contenido::piedras(),
            'guia'    => require __DIR__ . '/../../config/guia_piedras.php',
        ]);
    }

    public function nosotros(): void {
        vista('nosotros', ['titulo' => 'Nosotros · Mavs Jewelry', 'pagina' => 'nosotros']);
    }

    public function contacto(): void {
        vista('contacto', ['titulo' => 'Contacto · Mavs Jewelry', 'pagina' => 'contacto']);
    }

    /** Registra el clic (RF-25) y lleva a WhatsApp con el mensaje prellenado. */
    public function whatsapp(): void {
        $origen = (string)($_GET['desde'] ?? 'sitio');
        $mensaje = (string)($_GET['m'] ?? 'Hola María Angélica, vi tu página y quiero hacer una consulta.');
        if (!empty($_GET['nombre'])) {        // formulario de contacto
            $mensaje = 'Hola María Angélica, soy ' . trim((string)$_GET['nombre']) . ".\nMotivo: "
                     . trim((string)($_GET['motivo'] ?? 'Consulta')) . ".\n\n" . $mensaje;
        }
        try {
            Contenido::registrarClic($origen);
        } catch (Throwable $e) {
            error_log('No se pudo registrar el clic de WhatsApp: ' . $e->getMessage());
        }
        header('Location: ' . whatsapp(mb_substr($mensaje, 0, 500)));
        exit;
    }
}
