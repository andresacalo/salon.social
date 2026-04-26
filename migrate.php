<?php
require_once 'app/models/Conexion.php';

$db = Conexion::get();

$queries = [
    "ALTER TABLE users ADD COLUMN phone VARCHAR(20) DEFAULT NULL",
    "ALTER TABLE users ADD COLUMN whatsapp VARCHAR(20) DEFAULT NULL"
];

foreach ($queries as $query) {
    try {
        $db->query($query);
        echo "✅ Ejecutado: $query\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "⚠️  Ya existe: $query\n";
        } else {
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
    }
}

echo "\n✅ Migracion completada\n";
?>
