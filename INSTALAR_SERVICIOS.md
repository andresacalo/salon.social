# 🚀 GUÍA DE INSTALACIÓN: SERVICIOS DE NOTIFICACIÓN

Tu aplicación Salón Social ahora tiene integración completa de servicios de mensajería (Email, WhatsApp, SMS). Aquí te muestro exactamente qué hacer.

---

## 📋 PASO 1: INSTALAR COMPOSER (Una sola vez)

Si ya tienes Composer instalado, **salta al Paso 2**.

### Windows:
1. Descarga desde: https://getcomposer.org/download/
2. Ejecuta el instalador (siguiente, siguiente, siguiente)
3. Verifica en la terminal:
   ```bash
   composer --version
   ```

---

## 📦 PASO 2: INSTALAR LIBRERÍAS NECESARIAS

Abre **PowerShell** (o CMD) en tu carpeta del proyecto:

```bash
# Navega a tu proyecto
cd C:\xampp\htdocs\salon_social

# Instala PHPMailer (para enviar emails)
composer require phpmailer/phpmailer

# Instala Twilio SDK (para WhatsApp y SMS)
composer require twilio/sdk
```

✅ Esto crea automáticamente una carpeta `vendor/` con todas las librerías.

---

## 🔑 PASO 3: CONFIGURAR CREDENCIALES

### 📧 CONFIGURAR GMAIL

1. Ve a: https://myaccount.google.com/apppasswords
2. Selecciona:
   - **App:** Mail
   - **Device:** Windows Computer
3. Copia la contraseña de 16 caracteres que Gmail genera
4. Abre `config/api_keys.php` y reemplaza:

```php
define('MAIL_USERNAME', 'tu-email@gmail.com');      // Tu email aquí
define('MAIL_PASSWORD', 'xxxx xxxx xxxx xxxx');      // Los 16 caracteres aquí
```

### 💬 CONFIGURAR TWILIO (WhatsApp + SMS)

1. Regístrate en: https://www.twilio.com/
2. Completa el setup inicial (te dan $15 de prueba)
3. En el **Dashboard**, obtén:
   - **Account SID** 
   - **Auth Token**
4. Abre `config/api_keys.php` y reemplaza:

```php
define('TWILIO_SID', 'ACxxxxxxxxxxxx');                    // Tu SID aquí
define('TWILIO_AUTH_TOKEN', 'xxxxxxxxxxxxxx');             // Tu Token aquí
```

### 📱 PARA SMS (Opcional pero recomendado)

En Twilio, compra un número de teléfono que soporte SMS y reemplaza:

```php
define('TWILIO_SMS_FROM', '+1234567890');  // Tu número aquí
```

---

## 🗄️ PASO 4: ACTUALIZAR BASE DE DATOS

Como agregué dos campos nuevos (phone y whatsapp) a la tabla users, ejecuta:

```bash
# Opción 1: Si es una nueva instalación
# El schema.sql se ejecuta automáticamente

# Opción 2: Si ya tienes datos y necesitas agregar los campos
# En phpMyAdmin, ejecuta estas dos líneas:

ALTER TABLE users ADD COLUMN phone VARCHAR(20) DEFAULT NULL;
ALTER TABLE users ADD COLUMN whatsapp VARCHAR(20) DEFAULT NULL;
```

---

## 📝 PASO 5: ACTUALIZAR EL FORMULARIO DE USUARIO

Ahora los usuarios pueden registrar sus números. Abre `app/views/usuarios/listar.php` (o el formulario de registro) y agrega estos campos:

```html
<!-- Nuevo campo: Teléfono -->
<div>
    <label for="phone">📱 Teléfono:</label>
    <input type="tel" id="phone" name="phone" placeholder="+1234567890">
</div>

<!-- Nuevo campo: WhatsApp -->
<div>
    <label for="whatsapp">💬 WhatsApp:</label>
    <input type="tel" id="whatsapp" name="whatsapp" placeholder="+1234567890">
</div>
```

También actualiza el controlador de usuarios para guardar estos datos:

```php
$phone = trim($_POST['phone'] ?? '');
$whatsapp = trim($_POST['whatsapp'] ?? '');
// Dentro del INSERT o UPDATE:
// phone, whatsapp VALUES ?, ?
```

---

## 🧪 PASO 6: PROBAR LOS SERVICIOS

### Prueba 1: Enviar Email

Crea un archivo temporal `test_email.php` en la raíz:

```php
<?php
require_once 'config/api_keys.php';
require_once 'app/services/EmailService.php';

$resultado = EmailService::enviarConfirmacionReserva(
    'tu-email@gmail.com',  // Tu email
    'Juan Pérez',
    [
        'titulo' => 'Evento de Prueba',
        'fecha_evento' => '2026-04-20',
        'hora_inicio' => '14:00',
        'hora_fin' => '18:00',
        'asistentes' => 50
    ]
);

echo $resultado ? "✅ Email enviado!" : "❌ Error al enviar";
?>
```

Accede a: `http://localhost/salon_social/test_email.php`

### Prueba 2: Enviar WhatsApp

Primero verifica tu número en Twilio (ve a **Verified Caller IDs**).

```php
<?php
require_once 'config/api_keys.php';
require_once 'app/services/WhatsappService.php';

$resultado = WhatsappService::enviarConfirmacionReserva(
    'whatsapp:+573001234567',  // Tu número +país-número
    'Juan',
    [...]
);
?>
```

---

## ✅ CHECKLIST DE VERIFICACIÓN

- [ ] Composer instalado (`composer --version` fuera un número)
- [ ] `vendor/` existe en la carpeta `salon_social`
- [ ] `config/api_keys.php` actualizado con credenciales reales
- [ ] Campos `phone` y `whatsapp` agregados a tabla `users`
- [ ] Formulario de usuario actualizado con nuevos campos
- [ ] Prueba de email funciona
- [ ] (Opcional) Prueba de WhatsApp funciona

---

## 🎯 ¿QUÉ OCURRE AHORA?

Cuando un usuario crea una reserva:

1. ✅ Se guarda en la BD
2. 📧 Recibe EMAIL de confirmación
3. 💬 Recibe WhatsApp (si tiene número)
4. 📱 Recibe SMS (si tiene número)
5. 🔔 Admin recibe EMAIL de notificación

Cuando el admin aprueba/rechaza:

1. 📧 Usuario recibe EMAIL (aprobada o rechazada)
2. 💬 Usuario recibe WhatsApp (si tiene número)
3. 📱 Usuario recibe SMS (si tiene número)

---

## ❌ TROUBLESHOOTING

**Problema:** "Class not found EmailService"
**Solución:** Verifica que `config/api_keys.php` exista y esté en la ruta correcta.

**Problema:** "Error al enviar email"
**Solución:** Verifica tu contraseña de Gmail no sea la contraseña de la cuenta, sino la "App Password" de 16 caracteres.

**Problema:** "Twilio error: Invalid number"
**Solución:** Asegúrate de enviar números con formato `+país-número` (ej: `+573001234567`)

**Problema:** Nada funciona
**Solución:** Revisa los logs del servidor:
```bash
# En XAMPP: C:\xampp\apache\logs\error.log
# O en PHP: php.ini -> error_log
```

---

## 📞 SOPORTE

- Gmail Help: https://support.google.com
- Twilio Docs: https://www.twilio.com/docs
- PHPMailer: https://github.com/PHPMailer/PHPMailer

---

**¡Tu aplicación está lista para enviar notificaciones!** 🎉

¿Preguntas? Revisa el archivo [app/services/README.md](app/services/README.md) para más detalles.
