<?php

$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';
$DB_NAME = getenv('DB_NAME') ?: 'salon_social';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS);
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo 'Error de conexi�n: ' . $mysqli->connect_error;
    exit;
}

$mysqli->set_charset('utf8mb4');
$mysqli->query("SET time_zone = '-05:00'");

$schemaSql = file_get_contents(__DIR__ . '/../schema.sql');
if (!$schemaSql) {
    http_response_code(500);
    echo 'No se pudo cargar schema.sql';
    exit;
}

if (!$mysqli->multi_query($schemaSql)) {
    http_response_code(500);
    echo 'No se pudo crear la base de datos: ' . $mysqli->error;
    exit;
}
while ($mysqli->more_results() && $mysqli->next_result()) { /* flush */ }

$mysqli->select_db($DB_NAME);
if ($mysqli->errno) {
    http_response_code(500);
    echo 'No se pudo seleccionar DB: ' . $mysqli->error;
    exit;
}

function db(): mysqli {
    static $instance = null;
    if ($instance === null) {
        global $mysqli;
        $instance = $mysqli;
    }
    return $instance;
}
?>
