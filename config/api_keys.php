<?php

/**
 * 🔐 CONFIGURACIÓN DE APIs Y SERVICIOS DE TERCEROS
 * 
 * ADVERTENCIA: ¡NUNCA subas este archivo a GitHub o repositorios públicos!
 * Agrega "config/api_keys.php" a tu .gitignore
 * 
 * Las contraseñas y tokens aquí están expuestos. En producción, 
 * usa variables de entorno (.env)
 */

// ============================================
// 📧 CONFIGURACIÓN DE EMAIL (PHPMailer)
// ============================================

define('MAIL_HOST', 'smtp.gmail.com');           // Servidor SMTP
define('MAIL_PORT', 587);                        // Puerto
define('MAIL_USERNAME', 'tu-email@gmail.com');   // Tu email
define('MAIL_PASSWORD', 'tu-contraseña-app');    // Tu contraseña o App Password
define('MAIL_FROM_EMAIL', 'info@salonsocial.com'); // Email que aparece como remitente
define('MAIL_FROM_NAME', 'Salón Social');        // Nombre que aparece

// ============================================
// 💬 CONFIGURACIÓN DE WHATSAPP (Twilio)
// ============================================

define('TWILIO_SID', 'YOUR_TWILIO_SID_HERE');
define('TWILIO_AUTH_TOKEN', 'YOUR_TWILIO_AUTH_TOKEN_HERE');
define('TWILIO_WHATSAPP_FROM', 'whatsapp:+14155238886'); // Número de prueba de Twilio

// ============================================
// 📱 CONFIGURACIÓN DE SMS (Twilio)
// ============================================

define('TWILIO_SMS_FROM', '+1234567890'); // Tu número SMS en Twilio

// ============================================
// 🌍 CONFIGURACIÓN GENERAL
// ============================================

define('ADMIN_EMAIL', 'admin@salonsocial.com');  // Email del administrador
define('APP_NAME', 'Salón Social');              // Nombre de la app
define('APP_URL', 'http://localhost/salon_social'); // URL de tu app

// ============================================
// 📌 NOTAS DE CONFIGURACIÓN
// ============================================

/*
 * PARA GMAIL:
 * 1. Activa "Aplicaciones menos seguras" o crea una "Contraseña de Aplicación"
 * 2. Ve a: myaccount.google.com/apppasswords
 * 3. Copia la contraseña de 16 caracteres generada
 * 4. Pégala en MAIL_PASSWORD
 * 
 * PARA TWILIO:
 * 1. Regístrate en: twilio.com
 * 2. Crea un proyecto (trial gratis con saldo)
 * 3. Obtén tu SID y AUTH_TOKEN en la dashboard
 * 4. Compra un número para SMS
 * 5. Verifica números para WhatsApp
 * 
 * PARA PRODUCCIÓN:
 * - Usa un archivo .env en lugar de defines
 * - Usa getenv() para leer variables de entorno
 * - Nunca almacenes tokens en código fuente
 */

?>
