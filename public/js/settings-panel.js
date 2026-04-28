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
                english: 'English'
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
                english: 'English'
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
        this.updatePanelLanguage();
        this.updateButtonState('.lang-btn', lang);
        this.playNotificationSound();
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
