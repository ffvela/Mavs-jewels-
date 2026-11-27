<?php
declare(strict_types=1);

final class InicioController
{
    public function index(): void {
        vista('inicio', [
            'titulo'      => 'Mavs Jewelry · Joyas hechas a mano',
            'pagina'      => 'inicio',
            'destacados'  => Producto::destacados(7),
            'puntos'      => Contenido::puntosDeVenta(),
            'testimonios' => Contenido::testimonios(),
        ]);
    }
}
