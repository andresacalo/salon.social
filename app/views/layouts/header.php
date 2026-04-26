<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salón Social</title>
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
</head>
<body>
<header class="topbar">
    <div class="brand">🏰 Salón Social</div>
    <nav>
        <?php if (current_user()): ?>
            <a href="<?php echo route_to('dashboard'); ?>">📊 Dashboard</a>
            <a href="<?php echo route_to('reservas'); ?>">📅 Mis reservas</a>
            <?php if (current_user()['role'] === 'admin'): ?>
                <a href="<?php echo route_to('admin_reservas'); ?>">✅ Admin</a>
                <a href="<?php echo route_to('notificaciones'); ?>">📧 Notificaciones</a>
                <a href="<?php echo route_to('inventario'); ?>">📦 Inventario</a>
                <a href="<?php echo route_to('usuarios'); ?>">👥 Usuarios</a>
            <?php elseif (current_user()['role'] === 'supervisor'): ?>
                <a href="<?php echo route_to('inventario'); ?>">📦 Inventario</a>
            <?php endif; ?>
            <form method="post" action="<?php echo route_to('logout'); ?>" style="display:inline">
                <button class="link-btn" type="submit">🚪 Salir</button>
            </form>
        <?php else: ?>
            <a href="<?php echo route_to('login'); ?>">🔐 Ingresar</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">
<?php if (!empty($flash)): ?>
    <div class="alert <?php echo $flash['type']; ?>"><?php echo htmlspecialchars($flash['message']); ?></div>
<?php endif; ?>
