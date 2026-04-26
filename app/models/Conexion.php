<?php
class Conexion
{
    private static ?mysqli $db = null;

    public static function get(): mysqli
    {
        if (self::$db) return self::$db;

        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';
        $name = getenv('DB_NAME') ?: 'salon_social';

        $db = new mysqli($host, $user, $pass);
        if ($db->connect_errno) {
            http_response_code(500);
            exit('Error de conexion: ' . $db->connect_error);
        }
        $db->set_charset('utf8mb4');
        $db->query("SET time_zone = '-05:00'");

        // Ruta correcta: carpeta database esta al mismo nivel que app
        $schemaPath = realpath(__DIR__ . '/../../database/salon_social.sql');
        if (!$schemaPath || !is_readable($schemaPath)) {
            http_response_code(500);
            exit('No se pudo cargar el schema en ' . (__DIR__ . '/../../database/salon_social.sql'));
        }
        $schema = file_get_contents($schemaPath);
        if ($schema === false) {
            http_response_code(500);
            exit('No se pudo leer el schema');
        }
        if (!$db->multi_query($schema)) {
            http_response_code(500);
            exit('No se pudo crear la base de datos: ' . $db->error);
        }
        while ($db->more_results() && $db->next_result()) { /* flush */ }

        $db->select_db($name);
        if ($db->errno) {
            http_response_code(500);
            exit('No se pudo seleccionar DB: ' . $db->error);
        }
        self::$db = $db;
        return $db;
    }
}
?>
