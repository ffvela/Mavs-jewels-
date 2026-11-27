<?php
declare(strict_types=1);

/** Fotos de productos subidas desde el panel (Tabla 4.4): se validan, se ajustan a 1200 px y se guardan en WebP. */
final class ImagenService
{
    public const MAX_BYTES = 8 * 1024 * 1024;
    public const TIPOS = ['image/jpeg', 'image/png', 'image/webp'];
    private const ANCHO = 1200;
    private const CARPETA = 'uploads/productos/';

    /** Revisa el archivo subido; el tipo se verifica por su contenido, no por la extensión. */
    public static function esValida(array $archivo): bool {
        return $archivo['error'] === UPLOAD_ERR_OK
            && $archivo['size'] <= self::MAX_BYTES
            && in_array((new finfo(FILEINFO_MIME_TYPE))->file($archivo['tmp_name']), self::TIPOS, true);
    }

    /** Guarda la foto en public/uploads/productos y devuelve su ruta relativa a public/. */
    public static function guardar(string $origen, int $idProducto): string {
        $img = imagecreatefromstring((string)file_get_contents($origen));
        if ($img === false) throw new RuntimeException('Imagen no válida');
        if (imagesx($img) > self::ANCHO) $img = imagescale($img, self::ANCHO);
        $ruta = self::CARPETA . $idProducto . '-' . bin2hex(random_bytes(4)) . '.webp';
        imagewebp($img, __DIR__ . '/../../public/' . $ruta, 82);
        imagedestroy($img);
        return $ruta;
    }
}
