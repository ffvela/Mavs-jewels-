<?php
declare(strict_types=1);

/** Contenidos editables del sitio: punto de venta, testimonios, piedras y clics de WhatsApp. */
final class Contenido
{
    public static function puntosDeVenta(): array {
        return Database::conexion()->query(
            "SELECT * FROM punto_venta WHERE visible = 1 AND fecha_fin >= CURDATE() ORDER BY fecha_inicio LIMIT 4")->fetchAll();
    }

    public static function testimonios(): array {
        return Database::conexion()->query(
            "SELECT * FROM testimonio WHERE visible = 1 ORDER BY id_testimonio LIMIT 3")->fetchAll();
    }

    public static function piedras(): array {
        return Database::conexion()->query("SELECT * FROM piedra ORDER BY id_piedra")->fetchAll();
    }

    /** Registra un clic en el botón de WhatsApp (RF-25). */
    public static function registrarClic(string $pagina): void {
        Database::conexion()->prepare("INSERT INTO clic_whatsapp (pagina, fecha) VALUES (?, NOW())")
            ->execute([mb_substr($pagina, 0, 120)]);
    }

    public static function clicsDelMes(): int {
        return (int)Database::conexion()->query(
            "SELECT COUNT(*) FROM clic_whatsapp
              WHERE fecha >= DATE_FORMAT(CURDATE(), '%Y-%m-01')")->fetchColumn();
    }
}
