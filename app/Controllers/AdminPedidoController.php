<?php
declare(strict_types=1);

/** Panel: seguimiento de pedidos y reembolsos (CU-11, RF-20). */
final class AdminPedidoController
{
    public function index(): void {
        $estado = (string)($_GET['estado'] ?? '');
        $pedidos = Pedido::listarPanel($estado);
        $id = (int)($_GET['id'] ?? ($pedidos[0]['id_pedido'] ?? 0));
        $detalle = $id ? Pedido::buscar($id) : null;
        vista('admin/pedidos', [
            'titulo' => 'Pedidos · Panel', 'seccion' => 'pedidos',
            'pedidos' => $pedidos, 'estado' => $estado, 'detalle' => $detalle,
            'items' => $detalle ? Pedido::items($id) : [], 'historial' => $detalle ? Pedido::historial($id) : [],
            'porPreparar' => Pedido::contarPorPreparar(), 'clics' => Contenido::clicsDelMes(),
            'conteo' => Producto::contarPorEstado(),
        ], 'panel');
    }

    public function cambiarEstado(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirigir('admin/pedidos');
        csrf_validar();
        $id = (int)($_POST['id'] ?? 0);
        $ok = Pedido::cambiarEstado($id, (string)($_POST['estado'] ?? ''), Auth::idAdmin());
        aviso($ok ? 'Estado del pedido actualizado.' : 'Ese cambio de estado no está permitido.', $ok ? 'ok' : 'error');
        redirigir('admin/pedidos?id=' . $id);
    }

    public function reembolso(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirigir('admin/pedidos');
        csrf_validar();
        $id = (int)($_POST['id'] ?? 0);
        $ok = Pedido::registrarReembolso($id, Auth::idAdmin(), !empty($_POST['reponer']));
        aviso($ok ? 'Reembolso registrado. Recuerda hacer la devolución en Mercado Pago.' : 'Este pedido no admite reembolso.', $ok ? 'ok' : 'error');
        redirigir('admin/pedidos?id=' . $id);
    }
}
