<?php
declare(strict_types=1);

final class CatalogoController
{
    /** Rangos de precio del filtro (RF-03). */
    public const RANGOS = ['' => 'Todos los precios', '10000' => 'Hasta $10.000', '18000' => 'Hasta $18.000'];

    public function index(): void {
        $tecnicas = Producto::tecnicas();
        $slugs = array_column($tecnicas, 'slug');
        $filtros = [
            'tecnica'    => in_array($_GET['tecnica'] ?? '', $slugs, true) ? $_GET['tecnica'] : $slugs[0],
            'tipo'       => (int)($_GET['tipo'] ?? 0),
            'precio_max' => array_key_exists($_GET['precio_max'] ?? '', self::RANGOS) ? ($_GET['precio_max'] ?? '') : '',
        ];
        vista('catalogo', [
            'titulo'    => 'Catálogo · Mavs Jewelry',
            'pagina'    => 'catalogo',
            'tecnicas'  => $tecnicas,
            'tipos'     => Producto::tipos(),
            'filtros'   => $filtros,
            'productos' => Producto::listar($filtros),
        ]);
    }

    public function ficha(): void {
        $p = Producto::ficha((int)($_GET['id'] ?? 0));
        if (!$p) {
            http_response_code(404);
            vista('errores/no_encontrada', ['titulo' => 'Pieza no disponible']);
            return;
        }
        vista('producto', ['titulo' => $p['nombre'] . ' · Mavs Jewelry', 'pagina' => 'catalogo', 'p' => $p]);
    }
}
