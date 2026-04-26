# 📧 SERVICIOS DE NOTIFICACIÓN

Esta carpeta contiene los servicios para enviar notificaciones a través de múltiples canales:
- 📧 **EmailService** - Correos electrónicos
- 💬 **WhatsappService** - Mensajes WhatsApp
- 📱 **SmsService** - Mensajes SMS

## ⚡ Requisitos Previos

### 1. Instalar Composer (Si no lo tienes)
```bash
# Descargar e instalar desde: getcomposer.org
```

### 2. Instalar Librerías Necesarias
```bash
# Accede a la raíz del proyecto
cd C:\xampp\htdocs\salon_social

# Instala PHPMailer (para emails)
composer require phpmailer/phpmailer

# Instala Twilio SDK (para WhatsApp y SMS)
composer require twilio/sdk
```

## 📧 CONFIGURAR EMAIL (Gmail)

### Paso 1: Habilitar Contraseña de Aplicación
1. Ve a: [myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords)
2. Selecciona: Mail + Windows Computer
3. Gmail te genera una contraseña de 16 caracteres
4. Copia esa contraseña

### Paso 2: Actualizar config/api_keys.php
```php
define('MAIL_USERNAME', 'tu-email@gmail.com');
define('MAIL_PASSWORD', 'xxxx xxxx xxxx xxxx'); // Los 16 caracteres aquí
```

## 💬 CONFIGURAR WHATSAPP (Twilio)

### Paso 1: Crear Cuenta Twilio
1. Ve a: [twilio.com](https://twilio.com)
2. Regístrate (trial gratis con $15)
3. Completa el setup inicial

### Paso 2: Obtener Credenciales
1. En tu dashboard, busca **Account SID** y **Auth Token**
2. Copia ambos valores

### Paso 3: Actualizar config/api_keys.php
```php
define('TWILIO_SID', 'ACxxxxxxxxxxxx');
define('TWILIO_AUTH_TOKEN', 'xxxxxxxxxxxxxx');
define('TWILIO_WHATSAPP_FROM', 'whatsapp:+14155238886'); // Número de prueba
```

### Paso 4: Verifica tu Número
En Twilio, ve a **Verified Caller IDs** y verifica tu número personal para recibir mensajes de prueba.

## 📱 CONFIGURAR SMS (Twilio)

### Paso 1: Comprar Número en Twilio
1. En el dashboard de Twilio
2. Busca **Phone Numbers** → **Buy Numbers**
3. Elige un número con capacidad SMS
4. Nota el número

### Paso 2: Actualizar config/api_keys.php
```php
define('TWILIO_SMS_FROM', '+1234567890'); // Tu número aquí
```

## 🔧 USAR LOS SERVICIOS

### Ejemplo: En tu ReservaController.php

```php
<?php

// Al inicio del archivo
require_once __DIR__ . '/../config/api_keys.php';
require_once __DIR__ . '/../services/EmailService.php';
require_once __DIR__ . '/../services/WhatsappService.php';
require_once __DIR__ . '/../services/SmsService.php';

class ReservaController {
    
    public function crear(): void {
        // ... tu código de guardado en BD ...
        
        if ($reservaGuardada) {
            // Obtener datos del usuario
            $usuario = Usuario::obtenerPorId($_SESSION['user']['id']);
            
            // Enviar Email
            EmailService::enviarConfirmacionReserva(
                $usuario['email'],
                $usuario['name'],
                $reserva
            );
            
            // Enviar WhatsApp (si tiene número)
            if (!empty($usuario['whatsapp'])) {
                WhatsappService::enviarConfirmacionReserva(
                    $usuario['whatsapp'],
                    $usuario['name'],
                    $reserva
                );
            }
            
            // Enviar SMS (si tiene número)
            if (!empty($usuario['phone'])) {
                SmsService::enviarConfirmacionReserva(
                    $usuario['phone'],
                    $usuario['name'],
                    $reserva
                );
            }
            
            // Notificar al admin
            $admin = Usuario::obtenerAdmin();
            EmailService::notificarAdminNuevaReserva($admin['email'], $reserva, $usuario['name']);
        }
    }
}
```

## 📋 MÉTODOS DISPONIBLES

### EmailService
- `enviarConfirmacionReserva($email, $nombre, $reserva)`
- `notificarAdminNuevaReserva($reserva, $nombreCliente)`
- `enviarAprobacionReserva($email, $nombre, $reserva)`
- `enviarRechazoReserva($email, $nombre, $reserva)`

### WhatsappService
- `enviarConfirmacionReserva($numero, $nombre, $reserva)`
- `notificarAdminNuevaReserva($numero, $nombreCliente, $reserva)`
- `enviarAprobacionReserva($numero, $nombre, $reserva)`
- `enviarRechazoReserva($numero, $nombre, $reserva)`
- `recordatorioEvento($numero, $nombre, $reserva)`

### SmsService
- `enviarConfirmacionReserva($numero, $nombre, $reserva)`
- `enviarAprobacionReserva($numero, $nombre, $reserva)`
- `enviarRechazoReserva($numero, $nombre, $reserva)`
- `recordatorioEvento($numero, $nombre, $reserva)`

## ⚠️ IMPORTANTE: SEGURIDAD

1. **NUNCA subas `config/api_keys.php` a GitHub**
   ```bash
   # Agrega a tu .gitignore
   echo "config/api_keys.php" >> .gitignore
   ```

2. **En Producción:** Usa variables de entorno (.env)
   ```php
   // En lugar de hardcodear valores, usa:
   define('MAIL_PASSWORD', getenv('MAIL_PASSWORD'));
   ```

3. **Logs:** Los errores se guardan en el log del servidor (revisar con error_log())

## 🧪 PROBAR SERVICIOS

### Test Email
```php
require_once 'config/api_keys.php';
require_once 'app/services/EmailService.php';

$resultado = EmailService::enviarConfirmacionReserva(
    'tu-email@gmail.com',
    'Juan Pérez',
    [
        'titulo' => 'Test',
        'fecha_evento' => '2026-04-20',
        'hora_inicio' => '14:00',
        'hora_fin' => '18:00',
        'asistentes' => 50
    ]
);

echo $resultado ? "✅ Email enviado" : "❌ Error";
```

### Test WhatsApp
```php
require_once 'config/api_keys.php';
require_once 'app/services/WhatsappService.php';

$resultado = WhatsappService::enviarConfirmacionReserva(
    '+573001234567', // Tu número
    'Juan',
    [... datos de la reserva ...]
);

echo $resultado ? "✅ WhatsApp enviado" : "❌ Error";
```

## 📞 SOPORTE

- **Gmail Issues:** [gmail-support](https://support.google.com)
- **Twilio Issues:** [twilio-support](https://support.twilio.com)
- **PHPMailer:** [phpmailer-github](https://github.com/PHPMailer/PHPMailer)

---

**Última actualización:** 14/04/2026  
**Estado:** Production Ready ✅
