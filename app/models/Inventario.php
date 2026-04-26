<?php
require_once __DIR__ . '/Conexion.php';

class Inventario
{
    public static function create(string $name, string $unit, string $notes): void
    {
        $db = Conexion::get();
        $stmt = $db->prepare('INSERT INTO inventario_items (nombre, unidad, notas) VALUES (?,?,?)');
        $stmt->bind_param('sss', $name, $unit, $notes);
        $stmt->execute();
    }

    public static function move(int $itemId, int $qty, string $kind, string $reason, int $userId): void
    {
        $db = Conexion::get();
        $stmt = $db->prepare('INSERT INTO inventario_movimientos (item_id, cantidad, tipo, motivo, usuario_id) VALUES (?,?,?,?,?)');
        $stmt->bind_param('iissi', $itemId, $qty, $kind, $reason, $userId);
        $stmt->execute();
    }

    public static function all(): array
    {
        return Conexion::get()->query('SELECT * FROM inventario_items ORDER BY nombre')->fetch_all(MYSQLI_ASSOC);
    }

    public static function movements(): array
    {
        return Conexion::get()->query('SELECT m.*, i.nombre AS item_name, u.name AS user_name FROM inventario_movimientos m JOIN inventario_items i ON i.id=m.item_id LEFT JOIN users u ON u.id=m.usuario_id ORDER BY m.creado_el DESC LIMIT 20')->fetch_all(MYSQLI_ASSOC);
    }
}
?>
