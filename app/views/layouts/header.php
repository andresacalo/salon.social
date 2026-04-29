<!DOCTYPE html>
<html lang="<?php echo app_locale(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(t('brand')); ?></title>
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
</head>
<body>
<header class="topbar">
    <div class="brand" data-i18n="brand">🏰 Salón Social</div>
    <nav>
        <?php if (current_user()): ?>
            <a href="<?php echo route_to('dashboard'); ?>" data-i18n="dashboard">📊 Dashboard</a>
            <a href="<?php echo route_to('reservas'); ?>" data-i18n="myReservations">📅 Mis reservas</a>
            <?php if (current_user()['role'] === 'admin'): ?>
                <a href="<?php echo route_to('admin_reservas'); ?>" data-i18n="admin">✅ Admin</a>
                <a href="<?php echo route_to('notificaciones'); ?>" data-i18n="notifications">📧 Notificaciones</a>
                <a href="<?php echo route_to('inventario'); ?>" data-i18n="inventory">📦 Inventario</a>
                <a href="<?php echo route_to('usuarios'); ?>" data-i18n="users">👥 Usuarios</a>
            <?php elseif (current_user()['role'] === 'supervisor'): ?>
                <a href="<?php echo route_to('inventario'); ?>" data-i18n="inventory">📦 Inventario</a>
            <?php endif; ?>
            <form method="post" action="<?php echo route_to('logout'); ?>" style="display:inline">
                <button class="link-btn" type="submit" data-i18n="logout">🚪 Salir</button>
            </form>
        <?php else: ?>
            <a href="<?php echo route_to('login'); ?>" data-i18n="login">🔐 Ingresar</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">
<?php if (!empty($flash)): ?>
    <div class="alert <?php echo $flash['type']; ?>"><?php echo htmlspecialchars($flash['message']); ?></div>
<?php endif; ?>
