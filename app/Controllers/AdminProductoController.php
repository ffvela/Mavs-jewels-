<?php
declare(strict_types=1);

/** Panel: gestión de productos y stock (CU-09, CU-10, RF-16 a RF-18). */
final class AdminProductoController
{
    public function index(): void {
        $estado = (string)($_GET['estado'] ?? '');
        $buscar = trim((string)($_GET['q'] ?? ''));
        vista('admin/productos', [
            'titulo' => 'Productos · Panel', 'seccion' => 'productos',
            'productos' => Producto::listarPanel($estado, $buscar),
            'conteo' => Producto::contarPorEstado(), 'estado' => $estado, 'buscar' => $buscar,
            'porPreparar' => Pedido::contarPorPreparar(), 'clics' => Contenido::clicsDelMes(),
        ], 'panel');
    }

    public function editar(array $errores = [], ?array $datos = null): void {
        $id = (int)($_GET['id'] ?? 0);
        $p = $datos ?? ($id ? Producto::buscar($id) : null) ?? [
            'id_producto' => 0, 'nombre' => '', 'descripcion' => '', 'materiales' => '', 'precio' => '',
            'stock' => 1, 'es_unica' => 1, 'estado' => 'borrador', 'id_tecnica' => 0, 'id_tipo' => 0, 'piedras' => [],
        ];
        vista('admin/producto_form', [
            'titulo' => ($p['id_producto'] ? 'Editar' : 'Nueva') . ' pieza · Panel', 'seccion' => 'productos',
            'p' => $p, 'errores' => $errores, 'tecnicas' => Producto::tecnicas(), 'tipos' => Producto::tipos(),
            'piedras' => Contenido::piedras(),
            'fotos' => !empty($p['id_producto']) ? Producto::fotos((int)$p['id_producto']) : [],
        ], 'panel');
    }

    public function guardar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirigir('admin/productos');
        csrf_validar();
        $d = [
            'id_producto' => (int)($_POST['id_producto'] ?? 0),
            'nombre'      => trim(strip_tags((string)($_POST['nombre'] ?? ''))),
            'descripcion' => trim((string)($_POST['descripcion'] ?? '')),
            'materiales'  => trim((string)($_POST['materiales'] ?? '')),
            'precio'      => (int)($_POST['precio'] ?? 0),
            'stock'       => (int)($_POST['stock'] ?? 0),
            'es_unica'    => empty($_POST['es_unica']) ? 0 : 1,
            'estado'      => (string)($_POST['estado'] ?? 'borrador'),
            'id_tecnica'  => (int)($_POST['id_tecnica'] ?? 0),
            'id_tipo'     => (int)($_POST['id_tipo'] ?? 0),
            'piedras'     => array_map('intval', (array)($_POST['piedras'] ?? [])),
        ];
        $e = [];   // Tabla 4.4
        if (mb_strlen($d['nombre']) < 3 || mb_strlen($d['nombre']) > 120) $e['nombre'] = 'Entre 3 y 120 caracteres.';
        if ($d['precio'] < 1000 || $d['precio'] > 1000000) $e['precio'] = 'Entre $1.000 y $1.000.000.';
        if ($d['stock'] < 0 || $d['stock'] > 99 || ($d['es_unica'] && $d['stock'] > 1)) $e['stock'] = 'De 0 a 99; si es pieza única, 0 o 1.';
        if (mb_strlen($d['materiales']) > 255) $e['materiales'] = 'Máximo 255 caracteres.';
        if (mb_strlen($d['descripcion']) > 2000) $e['descripcion'] = 'Máximo 2.000 caracteres.';
        if (!in_array($d['estado'], ['borrador', 'publicado', 'oculto'], true)) $e['estado'] = 'Estado no válido.';
        if (!in_array($d['id_tecnica'], array_map('intval', array_column(Producto::tecnicas(), 'id_tecnica')), true)) $e['id_tecnica'] = 'Elige una técnica.';
        if (!in_array($d['id_tipo'], array_map('intval', array_column(Producto::tipos(), 'id_tipo')), true)) $e['id_tipo'] = 'Elige un tipo.';
        $foto = $_FILES['foto'] ?? null;
        $hayFoto = $foto && $foto['error'] !== UPLOAD_ERR_NO_FILE;
        if ($hayFoto && !ImagenService::esValida($foto)) {
            $e['foto'] = 'La foto debe ser JPG, PNG o WebP de hasta 8 MB.';
        }
        if (!$d['id_producto'] && !$hayFoto) $e['foto'] = 'Sube al menos una foto.';
        if ($e) {
            $_GET['id'] = $d['id_producto'];
            $this->editar($e, $d);
            return;
        }
        $id = Producto::guardar($d, $d['piedras']);
        if ($hayFoto) {
            Producto::agregarFoto($id, ImagenService::guardar($foto['tmp_name'], $id), $d['nombre']);
        }
        aviso('Pieza guardada.');
        redirigir('admin/productos');
    }

    /** Publicar, ocultar o marcar vendida (venta en feria). */
    public function cambiarEstado(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirigir('admin/productos');
        csrf_validar();
        $nuevo = (string)($_POST['estado'] ?? '');
        if (!in_array($nuevo, ['publicado', 'oculto', 'vendido'], true)) redirigir('admin/productos');
        if (Producto::cambiarEstado((int)($_POST['id'] ?? 0), $nuevo)) {
            aviso($nuevo === 'vendido' ? 'La pieza quedó como vendida y dejó de mostrarse en el catálogo.' : 'Estado actualizado.');
        } else {
            aviso('La pieza está reservada por un pago en curso. Podrás cambiarla cuando termine la reserva (máximo 15 minutos).', 'error');
        }
        redirigir('admin/productos');
    }
}
