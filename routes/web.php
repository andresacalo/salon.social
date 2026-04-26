<?php
return [
    // Login: mostrar formulario y procesar credenciales
    'login' => [
        'view' => 'auth/login',
        'post' => ['AuthController','login'],
    ],
    'dashboard' => ['view' => 'dashboard/index', 'auth' => true],
    'reservas' => ['view' => 'reservas/listar', 'auth' => true],
    'reservas_crear' => ['post' => ['ReservaController','crear'], 'auth' => true],
    'reservas_estado' => ['post' => ['ReservaController','cambiarEstado'], 'role' => ['admin']],
    'admin_reservas' => ['view' => 'reservas/aprobar', 'role' => ['admin']],
    'inventario' => ['view' => 'inventario/listar', 'role' => ['admin','supervisor']],
    'inventario_crear' => ['post' => ['InventarioController','crearItem'], 'role' => ['admin','supervisor']],
    'inventario_mov' => ['post' => ['InventarioController','movimiento'], 'role' => ['admin','supervisor']],
    'usuarios' => ['view' => 'usuarios/listar', 'role' => ['admin']],
    'usuarios_crear' => ['post' => ['UsuarioController','crear'], 'role' => ['admin']],
    'usuarios_toggle' => ['post' => ['UsuarioController','toggle'], 'role' => ['admin']],
    'notificaciones' => ['get' => ['NotificacionesController','index'], 'role' => ['admin']],
    'notificaciones_enviar' => ['post' => ['NotificacionesController','enviar'], 'role' => ['admin']],
    'reporte_csv' => ['get' => ['ReporteController','csvReservas'], 'role' => ['admin','supervisor']],
    'logout' => ['post' => ['AuthController','logout'], 'auth' => true],
];
?>
