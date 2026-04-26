<?php
session_start();
require_once __DIR__ . '/../app/models/Conexion.php';
require_once __DIR__ . '/../app/models/Usuario.php';
require_once __DIR__ . '/../app/models/Reserva.php';
require_once __DIR__ . '/../app/models/Inventario.php';
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/services/Notificaciones.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/UsuarioController.php';
require_once __DIR__ . '/../app/controllers/ReservaController.php';
require_once __DIR__ . '/../app/controllers/InventarioController.php';
require_once __DIR__ . '/../app/controllers/ReporteController.php';
require_once __DIR__ . '/../app/controllers/NotificacionesController.php';

Usuario::seedAdmin();

$routes = require __DIR__ . '/../routes/web.php';
$r = $_GET['r'] ?? 'dashboard';
$route = $routes[$r] ?? $routes['dashboard'];

// auth checks
$user = current_user();
if (($route['auth'] ?? false) && !$user) {
    header('Location: ' . route_to('login')); exit;
}
if (isset($route['role'])) {
    if (!$user || !in_array($user['role'], $route['role'], true)) { http_response_code(403); exit('No autorizado'); }
}

// handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($route['post'])) {
    [$ctrl, $method] = $route['post'];
    (new $ctrl)->$method();
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($route['get'])) {
    [$ctrl, $method] = $route['get'];
    (new $ctrl)->$method();
    exit;
}

// gather data for views
$stats = Reserva::stats();
$proximas = Reserva::upcoming();
$reservas = [];
$items = [];
$movs = [];
$usuarios = [];

switch ($r) {
    case 'reservas':
        $reservas = Reserva::forUser($user['id']);
        break;
    case 'admin_reservas':
        $reservas = Reserva::all();
        break;
    case 'inventario':
        $items = Inventario::all();
        $movs = Inventario::movements();
        break;
    case 'usuarios':
        $usuarios = Usuario::all();
        break;
}

$flash = flash();
$view = $route['view'] ?? 'dashboard/index';
view($view, compact('user','flash','stats','proximas','reservas','items','movs','usuarios'));
?>
