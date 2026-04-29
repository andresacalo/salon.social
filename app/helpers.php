<?php
function base_path(): string {
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $dir = rtrim(str_replace('\\','/', dirname($script)), '/');
    // Si el front controller vive en /public, recorta ese segmento para no exponerlo en las URLs
    if (substr($dir, -7) === '/public') {
        $dir = rtrim(substr($dir, 0, -7), '/');
    }
    return ($dir === '' || $dir === '/') ? '' : $dir;
}

function route_to(string $name): string {
    $base = base_path();
    return ($base ? $base : '') . '/?r=' . $name;
}

function asset(string $path): string {
    $base = base_path();
    return ($base ? $base : '') . '/' . ltrim($path, '/');
}

function view(string $path, array $data = []) {
    extract($data);
    include __DIR__ . '/views/layouts/header.php';
    include __DIR__ . '/views/' . $path . '.php';
    include __DIR__ . '/views/layouts/footer.php';
}

function flash(?string $type = null, ?string $message = null) {
    if ($type !== null) {
        $_SESSION['flash'] = ['type'=>$type,'message'=>$message];
    }
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

function current_user() {
    return $_SESSION['user'] ?? null;
}

function app_locale(): string {
    return $_COOKIE['salon_lang'] ?? 'es';
}

function translations(): array {
    return [
        'es' => [
            'brand' => '🏰 Salón Social',
            'dashboard' => '📊 Dashboard',
            'myReservations' => '📅 Mis reservas',
            'admin' => '✅ Admin',
            'notifications' => '📧 Notificaciones',
            'inventory' => '📦 Inventario',
            'users' => '👥 Usuarios',
            'logout' => '🚪 Salir',
            'login' => '🔐 Ingresar',
            'welcome' => '🏰 Bienvenido',
            'loginSubtitle' => 'Accede al Sistema de Reservas',
            'email' => '📧 Correo Electrónico',
            'password' => '🔐 Contraseña',
            'submitLogin' => '🔓 Entrar',
            'emailPlaceholder' => 'tu@correo.com',
            'passwordPlaceholder' => 'Tu contraseña',
            'footer' => '✨ Salon Social - Sistema de Reservas 2024',
            'siteCopyright' => 'Sistema Salón Social © 2024',
            'dashboardStatsTitle' => '📊 Estado de Reservas',
            'upcomingReservationsTitle' => '🚨 Próximas Reservas (Semáforo)',
            'noUpcoming' => '😴 Nada próximo por el momento.',
            'date' => '📆 Fecha',
            'requester' => '👤 Solicitante',
            'status' => '✅ Estado',
            'semaphore' => '⏱️ Semáforo',
            ' days' => ' días',
            'newReservationTitle' => '📅 Nueva Reserva',
            'error' => '⚠️ Error:',
            'eventDate' => '📆 Fecha del Evento',
            'startTime' => '🕐 Hora de Inicio',
            'endTime' => '🕑 Hora de Fin',
            'eventTitle' => '🎯 Título del Evento',
            'attendees' => '👥 Número de Asistentes',
            'phone' => '📱 Teléfono/Celular',
            'notes' => '📝 Notas Adicionales',
            'bookButton' => '✅ Reservar',
            'myReservationsTitle' => '📋 Mis Reservas',
            'schedule' => '🕐 Horario',
            'approveReservationsTitle' => '✅ Aprobar Reservas',
            'downloadCsv' => '📊 Descargar Reporte CSV',
            'actions' => '⚙️ Acciones',
            'approveButton' => '✅ Aprobar',
            'rejectButton' => '❌ Rechazar',
            'inventoryTitle' => 'Inventario',
            'createItemTitle' => 'Crear ítem',
            'nameLabel' => 'Nombre',
            'unitLabel' => 'Unidad',
            'notesLabel' => 'Notas',
            'saveButton' => 'Guardar',
            'movementTitle' => 'Movimiento',
            'itemLabel' => 'Ítem',
            'quantityLabel' => 'Cantidad',
            'typeLabel' => 'Tipo',
            'reasonLabel' => 'Motivo',
            'registerButton' => 'Registrar',
            'recentMovementsTitle' => 'Últimos movimientos',
            'item' => 'Ítem',
            'qty' => 'Cant.',
            'user' => 'Usuario',
            'entry' => 'Entrada',
            'exit' => 'Salida',
            'usersSystemTitle' => '👥 Usuarios del Sistema',
            'name' => 'Nombre',
            'rol' => 'Rol',
            'state' => 'Estado',
            'action' => 'Acción',
            'createUserTitle' => '➕ Crear Usuario',
            'fullName' => '👤 Nombre Completo',
            'roleLabel' => '🎭 Rol',
            'createUserButton' => '✅ Crear Usuario',
            'resident' => '👤 Residente',
            'supervisor' => '⭐ Supervisor',
            'adminRole' => '👑 Admin',
            'active' => '✓ Activo',
            'inactive' => '✗ Inactivo',
            'channelSelect' => '📡 Selecciona canal:',
            'helpText' => 'Desde aquí puedes enviar notificaciones a los usuarios sobre sus reservas por múltiples canales:',
            'emailOption' => '📧 Email',
            'whatsappOption' => '💬 WhatsApp',
            'smsOption' => '📞 SMS',
            'notificationsCenterTitle' => '🔔 Centro de Notificaciones',
            'reservationsCount' => 'Reservas',
            'confirmAction' => '📧 Confirmación',
            'approvalAction' => '✅ Aprobada',
            'rejectionAction' => '❌ Rechazada',
            'noNotifications' => 'No hay reservas para notificar.',
            'status_aprobada' => 'Aprobada',
            'status_pendiente' => 'Pendiente',
            'status_rechazada' => 'Rechazada',
            'status_activo' => 'Activo',
            'status_inactivo' => 'Inactivo',
            'status_entrada' => 'Entrada',
            'status_salida' => 'Salida'
        ],
        'en' => [
            'brand' => '🏰 Salon Social',
            'dashboard' => '📊 Dashboard',
            'myReservations' => '📅 My reservations',
            'admin' => '✅ Admin',
            'notifications' => '📧 Notifications',
            'inventory' => '📦 Inventory',
            'users' => '👥 Users',
            'logout' => '🚪 Logout',
            'login' => '🔐 Login',
            'welcome' => '🏰 Welcome',
            'loginSubtitle' => 'Sign in to the Reservation System',
            'email' => '📧 Email Address',
            'password' => '🔐 Password',
            'submitLogin' => '🔓 Enter',
            'emailPlaceholder' => 'you@example.com',
            'passwordPlaceholder' => 'Your password',
            'footer' => '✨ Salon Social - Reservation System 2024',
            'siteCopyright' => 'Salon Social System © 2024',
            'dashboardStatsTitle' => '📊 Reservation Status',
            'upcomingReservationsTitle' => '🚨 Upcoming Reservations (Traffic Light)',
            'noUpcoming' => '😴 Nothing coming up right now.',
            'date' => '📆 Date',
            'requester' => '👤 Requester',
            'status' => '✅ Status',
            'semaphore' => '⏱️ Traffic Light',
            ' days' => ' days',
            'newReservationTitle' => '📅 New Reservation',
            'error' => '⚠️ Error:',
            'eventDate' => '📆 Event Date',
            'startTime' => '🕐 Start Time',
            'endTime' => '🕑 End Time',
            'eventTitle' => '🎯 Event Title',
            'attendees' => '👥 Number of Attendees',
            'phone' => '📱 Phone',
            'notes' => '📝 Additional Notes',
            'bookButton' => '✅ Book',
            'myReservationsTitle' => '📋 My Reservations',
            'schedule' => '🕐 Schedule',
            'approveReservationsTitle' => '✅ Approve Reservations',
            'downloadCsv' => '📊 Download CSV Report',
            'actions' => '⚙️ Actions',
            'approveButton' => '✅ Approve',
            'rejectButton' => '❌ Reject',
            'inventoryTitle' => 'Inventory',
            'createItemTitle' => 'Create Item',
            'nameLabel' => 'Name',
            'unitLabel' => 'Unit',
            'notesLabel' => 'Notes',
            'saveButton' => 'Save',
            'movementTitle' => 'Movement',
            'itemLabel' => 'Item',
            'quantityLabel' => 'Quantity',
            'typeLabel' => 'Type',
            'reasonLabel' => 'Reason',
            'registerButton' => 'Register',
            'recentMovementsTitle' => 'Recent Movements',
            'item' => 'Item',
            'qty' => 'Qty',
            'user' => 'User',
            'entry' => 'Entry',
            'exit' => 'Exit',
            'usersSystemTitle' => '👥 System Users',
            'name' => 'Name',
            'rol' => 'Role',
            'state' => 'Status',
            'action' => 'Action',
            'createUserTitle' => '➕ Create User',
            'fullName' => '👤 Full Name',
            'roleLabel' => '🎭 Role',
            'createUserButton' => '✅ Create User',
            'resident' => '👤 Resident',
            'supervisor' => '⭐ Supervisor',
            'adminRole' => '👑 Admin',
            'active' => '✓ Active',
            'inactive' => '✗ Inactive',
            'channelSelect' => '📡 Select channel:',
            'helpText' => 'From here you can send notifications to users about their reservations through multiple channels:',
            'emailOption' => '📧 Email',
            'whatsappOption' => '💬 WhatsApp',
            'smsOption' => '📞 SMS',
            'notificationsCenterTitle' => '🔔 Notifications Center',
            'reservationsCount' => 'Reservations',
            'confirmAction' => '📧 Confirmation',
            'approvalAction' => '✅ Approved',
            'rejectionAction' => '❌ Rejected',
            'noNotifications' => 'No reservations to notify.',
            'status_aprobada' => 'Approved',
            'status_pendiente' => 'Pending',
            'status_rechazada' => 'Rejected',
            'status_activo' => 'Active',
            'status_inactivo' => 'Inactive',
            'status_entrada' => 'Entry',
            'status_salida' => 'Exit'
        ]
    ];
}

function t(string $key): string {
    $lang = app_locale();
    $translations = translations();
    return $translations[$lang][$key] ?? $translations['es'][$key] ?? $key;
}

function t_status(string $status): string {
    return t('status_' . $status);
}

function t_role(string $role): string {
    return t($role === 'residente' ? 'resident' : ($role === 'supervisor' ? 'supervisor' : 'adminRole'));
}
?>
