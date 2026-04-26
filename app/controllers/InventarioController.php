<?php
require_once __DIR__ . '/../models/Inventario.php';

class InventarioController
{
    public function crearItem(): void
    {
        $this->requireRole(['admin','supervisor']);
        $name = trim($_POST['name'] ?? '');
        $unit = trim($_POST['unit'] ?? 'unidad');
        $notes = trim($_POST['notes'] ?? '');
        Inventario::create($name, $unit, $notes);
        $_SESSION['flash'] = ['type'=>'success','message'=>'Ítem creado'];
        header('Location: ' . route_to('inventario'));
    }

    public function movimiento(): void
    {
        $this->requireRole(['admin','supervisor']);
        $item = (int)($_POST['item_id'] ?? 0);
        $qty = (int)($_POST['qty'] ?? 0);
        $kind = $_POST['kind'] ?? 'entrada';
        $reason = trim($_POST['reason'] ?? '');
        try {
            Inventario::move($item, $qty, $kind, $reason, $_SESSION['user']['id']);
            $_SESSION['flash'] = ['type'=>'success','message'=>'Movimiento registrado'];
        } catch (RuntimeException $e) {
            $_SESSION['flash'] = ['type'=>'error','message'=>$e->getMessage()];
        }
        header('Location: ' . route_to('inventario'));
    }

    private function requireRole(array $roles): void
    {
        $u = $_SESSION['user'] ?? null;
        if (!$u || !in_array($u['role'], $roles, true)) { http_response_code(403); exit('No autorizado'); }
    }
}
?>
