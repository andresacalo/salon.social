# 📧📱💬 SERVICIOS DE NOTIFICACIÓN - RESUMEN INTEGRACIÓN

## 🎯 ¿QUÉ SE AGREGÓ A TU PROYECTO?

### 📂 Nuevas Carpetas
```
app/
└── services/                    ← 🆕 NUEVA CARPETA
    ├── EmailService.php         (Envía emails con PHPMailer)
    ├── WhatsappService.php      (Envía WhatsApp con Twilio)
    ├── SmsService.php           (Envía SMS con Twilio)
    └── README.md                (Documentación detallada)
```

### 📄 Nuevos Archivos
```
config/
└── api_keys.php                 ← 🆕 Credenciales de APIs
    
INSTALAR_SERVICIOS.md            ← 🆕 Guía de instalación
```

### 📝 Archivos Modificados
```
schema.sql                        ← ✏️ Agregadas columnas phone, whatsapp
app/models/Usuario.php           ← ✏️ Nuevos métodos findById(), getAdmins()
app/controllers/ReservaController.php    ← ✏️ Integración de servicios
```

---

## 🔄 FLUJO DE NOTIFICACIONES

### Cuando se crea UNA RESERVA:

```
Usuario llena formulario
         ↓
ReservaController::crear()
         ↓
├─ Guardar en BD
├─ 📧 EmailService::enviarConfirmacionReserva()
├─ 💬 WhatsappService::enviarConfirmacionReserva() (si tiene whatsapp)
├─ 📱 SmsService::enviarConfirmacionReserva() (si tiene phone)
└─ 🔔 Notificar a Admin (email + whatsapp)
```

### Cuando Admin APRUEBA una Reserva:

```
Admin hace clic en "Aprobar"
         ↓
ReservaController::cambiarEstado('aprobada')
         ↓
├─ Actualizar estado en BD
├─ 📧 EmailService::enviarAprobacionReserva()
├─ 💬 WhatsappService::enviarAprobacionReserva()
└─ 📱 SmsService::enviarAprobacionReserva()
```

### Cuando Admin RECHAZA una Reserva:

```
Admin hace clic en "Rechazar"
         ↓
ReservaController::cambiarEstado('rechazada')
         ↓
├─ Actualizar estado en BD
├─ 📧 EmailService::enviarRechazoReserva()
├─ 💬 WhatsappService::enviarRechazoReserva()
└─ 📱 SmsService::enviarRechazoReserva()
```

---

## 📊 MATRIZ DE SERVICIOS

| Evento | Email | WhatsApp | SMS |
|--------|-------|----------|-----|
| Confirmación (Usuario crea) | ✅ | ✅ (opt) | ✅ (opt) |
| Notificación Admin | ✅ | ✅ (opt) | ❌ |
| Aprobación (Admin aprueba) | ✅ | ✅ (opt) | ✅ (opt) |
| Rechazo (Admin rechaza) | ✅ | ✅ (opt) | ✅ (opt) |
| Recordatorio Evento | 🟡 No implementado aún | 🟡 Disponible | 🟡 Disponible |

---

## 🚀 PRÓXIMOS PASOS

### INMEDIATO (Obligatorio):

1. **Instalar Composer**
   ```bash
   composer --version
   ```

2. **Instalar librerías**
   ```bash
   cd C:\xampp\htdocs\salon_social
   composer require phpmailer/phpmailer
   composer require twilio/sdk
   ```

3. **Configurar credenciales**
   - Editar `config/api_keys.php`
   - Agregar Gmail credentials
   - Agregar Twilio SID/Token

4. **Actualizar BD**
   ```sql
   ALTER TABLE users ADD COLUMN phone VARCHAR(20) DEFAULT NULL;
   ALTER TABLE users ADD COLUMN whatsapp VARCHAR(20) DEFAULT NULL;
   ```

### OPCIONAL (Recomendado):

5. **Actualizar formulario de usuario**
   - Agregar inputs para phone y whatsapp
   - Actualizar controller para guardar estos datos

6. **Actualizar formulario de reserva** (si lo deseas)
   - Mostrar qué canales de notificación tiene el usuario

7. **Agregar recordatorios**
   - Crear un script que envíe recordatorios 24h antes del evento

---

## 📋 MÉTODOS DISPONIBLES

### EmailService
```php
EmailService::enviarConfirmacionReserva($email, $nombre, $reserva);
EmailService::notificarAdminNuevaReserva($reserva, $nombreCliente);
EmailService::enviarAprobacionReserva($email, $nombre, $reserva);
EmailService::enviarRechazoReserva($email, $nombre, $reserva);
```

### WhatsappService
```php
WhatsappService::enviarConfirmacionReserva($numero, $nombre, $reserva);
WhatsappService::notificarAdminNuevaReserva($numero, $nombreCliente, $reserva);
WhatsappService::enviarAprobacionReserva($numero, $nombre, $reserva);
WhatsappService::enviarRechazoReserva($numero, $nombre, $reserva);
WhatsappService::recordatorioEvento($numero, $nombre, $reserva);
```

### SmsService
```php
SmsService::enviarConfirmacionReserva($numero, $nombre, $reserva);
SmsService::enviarAprobacionReserva($numero, $nombre, $reserva);
SmsService::enviarRechazoReserva($numero, $nombre, $reserva);
SmsService::recordatorioEvento($numero, $nombre, $reserva);
```

---

## 🔒 SEGURIDAD

### ⚠️ IMPORTANTE: NO SUBAS `config/api_keys.php` A GITHUB

```bash
# Agregar a .gitignore
echo "config/api_keys.php" >> .gitignore
```

### En Producción: Usa Variables de Entorno

En lugar de hardcodear credenciales:

```php
// Crear archivo .env en la raíz
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=xxxx-xxxx-xxxx-xxxx

TWILIO_SID=ACxxxxxx
TWILIO_AUTH_TOKEN=xxxxx
```

Luego en `config/api_keys.php`:
```php
define('MAIL_USERNAME', getenv('MAIL_USERNAME'));
define('MAIL_PASSWORD', getenv('MAIL_PASSWORD'));
```

---

## 💡 EJEMPLOS DE USO

### Enviar Email Personalizado:
```php
<?php
require_once 'config/api_keys.php';
require_once 'app/services/EmailService.php';

$resultado = EmailService::enviarConfirmacionReserva(
    'cliente@gmail.com',
    'Juan Pérez',
    [
        'titulo' => 'Boda de mi hermana',
        'fecha_evento' => '2026-05-15',
        'hora_inicio' => '18:00',
        'hora_fin' => '23:00',
        'asistentes' => 150
    ]
);

if ($resultado === true) {
    echo "✅ Email enviado exitosamente";
} else {
    echo "❌ Error: " . $resultado;
}
?>
```

### Verificar Integración:
```php
<?php
// Instancia todos los servicios
$email_ok = class_exists('EmailService');
$whatsapp_ok = class_exists('WhatsappService');
$sms_ok = class_exists('SmsService');

echo ($email_ok && $whatsapp_ok && $sms_ok) ? "✅ Servicios OK" : "❌ Error";
?>
```

---

## 🐛 DEBUGGING

### Ver logs de errores:
```bash
# Si usas XAMPP
tail -f C:\xampp\apache\logs\error.log

# O desde PHP
error_log("Mi mensaje aquí");
// Se guarda en: C:\xampp\php\php.ini -> error_log
```

### Verificar si los servicios se cargan:
```php
<?php
require_once 'config/api_keys.php';
var_dump(defined('MAIL_USERNAME')); // Should be true
var_dump(defined('TWILIO_SID'));   // Should be true
?>
```

---

## ✅ CHECKLIST FINAL

- [ ] Composer instalado
- [ ] `vendor/` creado
- [ ] `config/api_keys.php` actualizado
- [ ] Tabla `users` tiene columnas `phone` y `whatsapp`
- [ ] Test de Email funciona
- [ ] (Opcional) Test de WhatsApp funciona
- [ ] (Opcional) Test de SMS funciona
- [ ] `.gitignore` incluye `config/api_keys.php`
- [ ] Documentación leída (este archivo + `/app/services/README.md`)

---

## 🎉 ¡LISTO!

Tu aplicación de Salón Social ahora puede:
- ✅ Enviar emails de confirmación
- ✅ Enviar mensajes WhatsApp
- ✅ Enviar SMS de notificación
- ✅ Notificar a admin en tiempo real
- ✅ Confirmar aprobación/rechazo automáticamente

**¡Tus usuarios estarán felices con las notificaciones! 🚀**

---

**Dudas?** Revisa:
- [app/services/README.md](app/services/README.md) - Documentación técnica
- [INSTALAR_SERVICIOS.md](INSTALAR_SERVICIOS.md) - Guía paso a paso
