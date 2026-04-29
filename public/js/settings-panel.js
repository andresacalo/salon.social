class SettingsPanel {
    constructor() {
        this.isOpen = false;
        this.lang = localStorage.getItem('salon_lang') || 'es';
        this.textSize = localStorage.getItem('salon_textSize') || 'normal';
        this.notifications = localStorage.getItem('salon_notifications') !== 'false';
        this.theme = localStorage.getItem('salon_theme') || 'dark';
        this.soundEnabled = localStorage.getItem('salon_sound') !== 'false';
        this.highContrast = localStorage.getItem('salon_contrast') === 'true';

        this.translations = {
            es: {
                settings: 'Configuración',
                language: 'Idioma',
                textSize: 'Tamaño de texto',
                notifications: 'Notificaciones',
                sound: 'Sonidos',
                theme: 'Tema',
                contrast: 'Alto contraste',
                small: 'Pequeño',
                normal: 'Normal',
                large: 'Grande',
                dark: 'Oscuro',
                light: 'Claro',
                spanish: 'Español',
                english: 'English',
                brand: '🏰 Salón Social',
                dashboard: '📊 Dashboard',
                myReservations: '📅 Mis reservas',
                admin: '✅ Admin',
                notifications: '📧 Notificaciones',
                inventory: '📦 Inventario',
                users: '👥 Usuarios',
                logout: '🚪 Salir',
                login: '🔐 Ingresar',
                welcome: '🏰 Bienvenido',
                loginSubtitle: 'Accede al Sistema de Reservas',
                email: '📧 Correo Electrónico',
                password: '🔐 Contraseña',
                submitLogin: '🔓 Entrar',
                emailPlaceholder: 'tu@correo.com',
                passwordPlaceholder: 'Tu contraseña',
                footer: '✨ Salon Social - Sistema de Reservas 2024',
                siteCopyright: 'Sistema Salón Social © 2024',
                dashboardStatsTitle: '📊 Estado de Reservas',
                upcomingReservationsTitle: '🚨 Próximas Reservas (Semáforo)',
                noUpcoming: '😴 Nada próximo por el momento.',
                date: '📆 Fecha',
                requester: '👤 Solicitante',
                status: '✅ Estado',
                semaphore: '⏱️ Semáforo',
                newReservationTitle: '📅 Nueva Reserva',
                eventDate: '📆 Fecha del Evento',
                startTime: '🕐 Hora de Inicio',
                endTime: '🕑 Hora de Fin',
                eventTitle: '🎯 Título del Evento',
                attendees: '👥 Número de Asistentes',
                bookButton: '✅ Reservar',
                myReservationsTitle: '📋 Mis Reservas',
                inventoryTitle: 'Inventario',
                createItemTitle: 'Crear ítem',
                nameLabel: 'Nombre',
                unitLabel: 'Unidad',
                notesLabel: 'Notas',
                saveButton: 'Guardar',
                movementTitle: 'Movimiento',
                itemLabel: 'Ítem',
                quantityLabel: 'Cantidad',
                typeLabel: 'Tipo',
                reasonLabel: 'Motivo',
                registerButton: 'Registrar',
                recentMovementsTitle: 'Últimos movimientos',
                item: 'Ítem',
                qty: 'Cant.',
                user: 'Usuario',
                entry: 'Entrada',
                exit: 'Salida',
                usersSystemTitle: '👥 Usuarios del Sistema',
                rol: 'Rol',
                state: 'Estado',
                action: 'Acción',
                createUserTitle: '➕ Crear Usuario',
                fullName: '👤 Nombre Completo',
                roleLabel: '🎭 Rol',
                createUserButton: '✅ Crear Usuario',
                resident: '👤 Residente',
                supervisor: '⭐ Supervisor',
                adminRole: '👑 Admin',
                active: '✓ Activo',
                inactive: '✗ Inactivo',
                channelSelect: '📡 Selecciona canal:',
                helpText: 'Desde aquí puedes enviar notificaciones a los usuarios sobre sus reservas por múltiples canales:',
                emailOption: '📧 Email',
                whatsappOption: '💬 WhatsApp',
                smsOption: '📞 SMS',
                notificationsCenterTitle: '🔔 Centro de Notificaciones',
                reservationsCount: 'Reservas',
                confirmAction: '📧 Confirmación',
                approvalAction: '✅ Aprobada',
                rejectionAction: '❌ Rechazada',
                noNotifications: 'No hay reservas para notificar.',
                downloadCsv: '📊 Descargar Reporte CSV',
                approveReservationsTitle: '✅ Aprobar Reservas',
                actions: '⚙️ Acciones'
            },
            en: {
                settings: 'Settings',
                language: 'Language',
                textSize: 'Text Size',
                notifications: 'Notifications',
                sound: 'Sounds',
                theme: 'Theme',
                contrast: 'High Contrast',
                small: 'Small',
                normal: 'Normal',
                large: 'Large',
                dark: 'Dark',
                light: 'Light',
                spanish: 'Español',
                english: 'English',
                brand: '🏰 Salon Social',
                dashboard: '📊 Dashboard',
                myReservations: '📅 My reservations',
                admin: '✅ Admin',
                notifications: '📧 Notifications',
                inventory: '📦 Inventory',
                users: '👥 Users',
                logout: '🚪 Logout',
                login: '🔐 Login',
                welcome: '🏰 Welcome',
                loginSubtitle: 'Sign in to the Reservation System',
                email: '📧 Email Address',
                password: '🔐 Password',
                submitLogin: '🔓 Enter',
                emailPlaceholder: 'you@example.com',
                passwordPlaceholder: 'Your password',
                footer: '✨ Salon Social - Reservation System 2024',
                'siteCopyright': 'Salon Social System © 2024',
                'dashboardStatsTitle': '📊 Reservation Status',
                'upcomingReservationsTitle': '🚨 Upcoming Reservations (Traffic Light)',
                'noUpcoming': '😴 Nothing coming up right now.',
                'date': '📆 Date',
                'requester': '👤 Requester',
                'status': '✅ Status',
                'semaphore': '⏱️ Traffic Light',
                'newReservationTitle': '📅 New Reservation',
                'eventDate': '📆 Event Date',
                'startTime': '🕐 Start Time',
                'endTime': '🕑 End Time',
                'eventTitle': '🎯 Event Title',
                'attendees': '👥 Number of Attendees',
                'bookButton': '✅ Book',
                'myReservationsTitle': '📋 My Reservations',
                'inventoryTitle': 'Inventory',
                'createItemTitle': 'Create Item',
                'nameLabel': 'Name',
                'unitLabel': 'Unit',
                'notesLabel': 'Notes',
                'saveButton': 'Save',
                'movementTitle': 'Movement',
                'itemLabel': 'Item',
                'quantityLabel': 'Quantity',
                'typeLabel': 'Type',
                'reasonLabel': 'Reason',
                'registerButton': 'Register',
                'recentMovementsTitle': 'Recent Movements',
                'item': 'Item',
                'qty': 'Qty',
                'user': 'User',
                'entry': 'Entry',
                'exit': 'Exit',
                'usersSystemTitle': '👥 System Users',
                'rol': 'Role',
                'state': 'Status',
                'action': 'Action',
                'createUserTitle': '➕ Create User',
                'fullName': '👤 Full Name',
                'roleLabel': '🎭 Role',
                'createUserButton': '✅ Create User',
                'resident': '👤 Resident',
                'supervisor': '⭐ Supervisor',
                'adminRole': '👑 Admin',
                'active': '✓ Active',
                'inactive': '✗ Inactive',
                'channelSelect': '📡 Select channel:',
                'helpText': 'From here you can send notifications to users about their reservations through multiple channels:',
                'emailOption': '📧 Email',
                'whatsappOption': '💬 WhatsApp',
                'smsOption': '📞 SMS',
                'notificationsCenterTitle': '🔔 Notifications Center',
                'reservationsCount': 'Reservations',
                'confirmAction': '📧 Confirmation',
                'approvalAction': '✅ Approved',
                'rejectionAction': '❌ Rejected',
                'noNotifications': 'No reservations to notify.',
                'downloadCsv': '📊 Download CSV Report',
                'approveReservationsTitle': '✅ Approve Reservations',
                'actions': '⚙️ Actions'
            }
        };

        this.init();
    }

    t(key) {
        return this.translations[this.lang][key] || key;
    }

    init() {
        this.applySettings();
        this.createPanel();
        this.attachListeners();
        this.translatePage();
    }

    createPanel() {
        const panel = document.createElement('div');
        panel.id = 'settings-panel-container';
        panel.innerHTML = `
            <div id="settings-button" class="settings-btn" title="${this.t('settings')}">⚙️</div>
            <div id="settings-popup" class="settings-popup">
                <div class="settings-header">
                    <h3>${this.t('settings')}</h3>
                    <button id="close-settings" class="close-btn">✕</button>
                </div>
                <div class="settings-content">
                    <div class="setting-group">
                        <label>${this.t('language')}</label>
                        <div class="setting-options">
                            <button class="lang-btn${this.lang === 'es' ? ' active' : ''}" data-lang="es">🇪🇸 ${this.t('spanish')}</button>
                            <button class="lang-btn${this.lang === 'en' ? ' active' : ''}" data-lang="en">🇺🇸 ${this.t('english')}</button>
                        </div>
                    </div>
                    <div class="setting-group">
                        <label>${this.t('textSize')}</label>
                        <div class="setting-options">
                            <button class="size-btn${this.textSize === 'small' ? ' active' : ''}" data-size="small">${this.t('small')}</button>
                            <button class="size-btn${this.textSize === 'normal' ? ' active' : ''}" data-size="normal">${this.t('normal')}</button>
                            <button class="size-btn${this.textSize === 'large' ? ' active' : ''}" data-size="large">${this.t('large')}</button>
                        </div>
                    </div>
                    <div class="setting-group">
                        <label>${this.t('theme')}</label>
                        <div class="setting-options">
                            <button class="theme-btn${this.theme === 'dark' ? ' active' : ''}" data-theme="dark">🌙 ${this.t('dark')}</button>
                            <button class="theme-btn${this.theme === 'light' ? ' active' : ''}" data-theme="light">☀️ ${this.t('light')}</button>
                        </div>
                    </div>
                    <div class="setting-group toggle-group">
                        <label>${this.t('notifications')}</label>
                        <label class="toggle-switch">
                            <input type="checkbox" id="notifications-toggle" ${this.notifications ? 'checked' : ''}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="setting-group toggle-group">
                        <label>${this.t('sound')}</label>
                        <label class="toggle-switch">
                            <input type="checkbox" id="sound-toggle" ${this.soundEnabled ? 'checked' : ''}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="setting-group toggle-group">
                        <label>${this.t('contrast')}</label>
                        <label class="toggle-switch">
                            <input type="checkbox" id="contrast-toggle" ${this.highContrast ? 'checked' : ''}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(panel);
    }

    attachListeners() {
        const settingsBtn = document.getElementById('settings-button');
        const closeBtn = document.getElementById('close-settings');
        const popup = document.getElementById('settings-popup');

        settingsBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.togglePanel();
        });
        closeBtn.addEventListener('click', () => this.togglePanel());

        document.addEventListener('click', (e) => {
            if (!popup.contains(e.target) && e.target !== settingsBtn) {
                if (this.isOpen) this.togglePanel();
            }
        });

        document.querySelectorAll('.lang-btn').forEach(btn => {
            btn.addEventListener('click', () => this.setLanguage(btn.dataset.lang));
        });
        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.addEventListener('click', () => this.setTextSize(btn.dataset.size));
        });
        document.querySelectorAll('.theme-btn').forEach(btn => {
            btn.addEventListener('click', () => this.setTheme(btn.dataset.theme));
        });
        document.getElementById('notifications-toggle').addEventListener('change', (e) => this.setNotifications(e.target.checked));
        document.getElementById('sound-toggle').addEventListener('change', (e) => this.setSound(e.target.checked));
        document.getElementById('contrast-toggle').addEventListener('change', (e) => this.setContrast(e.target.checked));
    }

    togglePanel() {
        const popup = document.getElementById('settings-popup');
        this.isOpen = !this.isOpen;
        popup.style.display = this.isOpen ? 'block' : 'none';
    }

    setLanguage(lang) {
        this.lang = lang;
        localStorage.setItem('salon_lang', lang);
        this.setCookie('salon_lang', lang, 365);
        this.updatePanelLanguage();
        this.translatePage();
        this.updateButtonState('.lang-btn', lang);
        this.playNotificationSound();
    }

    setCookie(name, value, days) {
        const expires = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/`;
    }

    setTextSize(size) {
        this.textSize = size;
        localStorage.setItem('salon_textSize', size);
        this.applyTextSize();
        this.updateButtonState('.size-btn', size);
        this.playNotificationSound();
    }

    setTheme(theme) {
        this.theme = theme;
        localStorage.setItem('salon_theme', theme);
        this.applyTheme();
        this.updateButtonState('.theme-btn', theme);
        this.playNotificationSound();
    }

    setNotifications(enabled) {
        this.notifications = enabled;
        localStorage.setItem('salon_notifications', enabled);
        this.playNotificationSound();
    }

    setSound(enabled) {
        this.soundEnabled = enabled;
        localStorage.setItem('salon_sound', enabled);
        this.playNotificationSound();
    }

    setContrast(enabled) {
        this.highContrast = enabled;
        localStorage.setItem('salon_contrast', enabled);
        this.applyContrast();
        this.playNotificationSound();
    }

    updateButtonState(selector, value) {
        document.querySelectorAll(selector).forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.size === value || btn.dataset.theme === value || btn.dataset.lang === value) {
                btn.classList.add('active');
            }
        });
    }

    updatePanelLanguage() {
        document.querySelector('.settings-header h3').textContent = this.t('settings');
        document.getElementById('settings-button').title = this.t('settings');
        const labels = ['language', 'textSize', 'theme', 'notifications', 'sound', 'contrast'];
        document.querySelectorAll('.setting-group label').forEach((label, index) => {
            label.textContent = this.t(labels[index]);
        });
        document.querySelectorAll('.lang-btn').forEach(btn => {
            btn.textContent = btn.dataset.lang === 'es' ? `🇪🇸 ${this.t('spanish')}` : `🇺🇸 ${this.t('english')}`;
        });
        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.textContent = this.t(btn.dataset.size);
        });
        document.querySelectorAll('.theme-btn').forEach(btn => {
            btn.textContent = `${btn.dataset.theme === 'dark' ? '🌙' : '☀️'} ${this.t(btn.dataset.theme)}`;
        });
        document.documentElement.lang = this.lang;
    }

    translatePage() {
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.dataset.i18n;
            const text = this.translations[this.lang] ? this.translations[this.lang][key] : undefined;
            if (text === undefined) {
                return;
            }
            if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA' || el.tagName === 'SELECT') {
                el.value = text;
            } else {
                el.textContent = text;
            }
        });

        document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
            el.placeholder = this.t(el.dataset.i18nPlaceholder);
        });

        document.querySelectorAll('[data-i18n-title]').forEach(el => {
            el.title = this.t(el.dataset.i18nTitle);
        });
    }

    applySettings() {
        this.applyTextSize();
        this.applyTheme();
        this.applyContrast();
        document.documentElement.lang = this.lang;
    }

    applyTextSize() {
        const sizes = { small: '13px', normal: '16px', large: '20px' };
        document.documentElement.style.fontSize = sizes[this.textSize] || sizes.normal;
    }

    applyTheme() {
        if (this.theme === 'light') {
            document.documentElement.classList.add('light-theme');
            document.documentElement.classList.remove('dark-theme');
        } else {
            document.documentElement.classList.remove('light-theme');
            document.documentElement.classList.add('dark-theme');
        }
    }

    applyContrast() {
        if (this.highContrast) {
            document.documentElement.classList.add('high-contrast');
        } else {
            document.documentElement.classList.remove('high-contrast');
        }
    }

    playNotificationSound() {
        if (!this.soundEnabled) return;
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gain = audioContext.createGain();
            oscillator.connect(gain);
            gain.connect(audioContext.destination);
            oscillator.frequency.value = 850;
            oscillator.type = 'sine';
            gain.gain.setValueAtTime(0.2, audioContext.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.12);
            oscillator.start();
            oscillator.stop(audioContext.currentTime + 0.12);
        } catch (e) {
            console.warn('Audio no disponible', e);
        }
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => new SettingsPanel());
} else {
    new SettingsPanel();
}
