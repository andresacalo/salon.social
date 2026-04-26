# 📁 ESTRUCTURA DEL PROYECTO - ACTUALIZADA

```
salon_social/
│
├── 📄 INSTALAR_SERVICIOS.md          ← 🆕 Guía de instalación paso a paso
├── 📄 SERVICIOS_RESUMEN.md           ← 🆕 Resumen de la integración
├── 📄 index.php                      (Front controller)
├── 📄 README.md                      (Descripción general)
├── 📄 show_tables.php                (Debug)
├── 📄 temp.patch                     
│
├── 📦 vendor/                        (Se crea con Composer - NO EDITAR)
│   ├── phpmailer/                   (PHPMailer library)
│   ├── twilio/                      (Twilio SDK)
│   └── ...
│
├── ⚙️ config/
│   ├── coneccion.php                (Conexión a BD - ORIGINAL)
│   └── 🆕 api_keys.php              (API Keys - CONFIDENCIAL ⚠️)
│
├── 🗄️ database/
│   ├── salon_social.sql             
│   └── (Backups)
│
├── 📋 docs/
│   └── (Documentación adicional)
│
├── 🎨 public/
│   ├── index.php                    (Punto de entrada público)
│   ├── css/
│   │   └── style.css                (Estilos + mejoras)
│   ├── img/
│   │   └── (Imágenes)
│   └── js/
│       └── (Scripts frontend)
│
├── 🚀 app/
│   ├── helpers.php                  (Funciones helper)
│   │
│   ├── 📂 controllers/              (Controladores MVC)
│   │   ├── AuthController.php       (Login/Auth)
│   │   ├── InventarioController.php (Inventario CRUD)
│   │   ├── ReporteController.php    (Reportes)
│   │   ├── 🆕 ReservaController.php (ACTUALIZADO - Notificaciones integradas)
│   │   ├── UsuarioController.php    (Usuarios CRUD)
│   │   └── UsuarioController.ejemplo.php  (🆕 Ejemplo actualizado)
│   │
│   ├── 📂 models/                   (Modelos de datos)
│   │   ├── Conexion.php             (Singleton BD)
│   │   ├── Inventario.php           (Model Inventario)
│   │   ├── Reserva.php              (Model Reserva)
│   │   └── 🆕 Usuario.php           (ACTUALIZADO - findById(), getAdmins())
│   │
│   ├── 📂 services/                 ← 🆕 NUEVA CARPETA
│   │   ├── EmailService.php         📧 Envío de emails
│   │   ├── WhatsappService.php      💬 Envío WhatsApp
│   │   ├── SmsService.php           📱 Envío SMS
│   │   └── README.md                (Documentación técnica)
│   │
│   └── 📂 views/                    (Vistas HTML)
│       ├── auth/
│       │   └── login.php
│       ├── dashboard/
│       │   └── index.php
│       ├── inventario/
│       │   └── listar.php
│       ├── layouts/
│       │   ├── footer.php
│       │   └── header.php
│       ├── reservas/
│       │   ├── aprobar.php
│       │   └── listar.php
│       └── usuarios/
│           └── listar.php
│
├── 🛣️ routes/
│   └── web.php                      (Definición de rutas)
│
└── ⚡ .gitignore                    (Archivos a ignorar - IMPORTANTE)
    # Agregar a este archivo:
    # vendor/
    # config/api_keys.php
```

---

## 🆕 CAMBIOS POR ARCHIVO

### `config/api_keys.php` - NUEVO
```php
// Configuración de Gmail
define('MAIL_USERNAME', 'tu-email@gmail.com');
define('MAIL_PASSWORD', 'xxxx xxxx xxxx xxxx');

// Configuración de Twilio
define('TWILIO_SID', 'ACxxxxxxxxxxxx');
define('TWILIO_AUTH_TOKEN', 'xxxxxxxxxxxxxx');
```

### `schema.sql` - ACTUALIZADO
```sql
CREATE TABLE users (
  ...
  phone VARCHAR(20) DEFAULT NULL,      ← NUEVO
  whatsapp VARCHAR(20) DEFAULT NULL,   ← NUEVO
  ...
)
```

### `app/models/Usuario.php` - ACTUALIZADO
```php
// Nuevos métodos agregados:
public static function findById(int $id): ?array { ... }
public static function getAdmins(): ?array { ... }
```

### `app/controllers/ReservaController.php` - ACTUALIZADO
```php
// Agregados:
- require_once de api_keys.php
- require_once de EmailService, WhatsappService, SmsService
- Llamadas a servicios en método crear()
- Notificaciones en método cambiarEstado()
```

### `app/services/` - NUEVA CARPETA CON 4 ARCHIVOS
```php
- EmailService.php      (4 métodos de email)
- WhatsappService.php   (5 métodos WhatsApp)
- SmsService.php        (4 métodos SMS)
- README.md             (Documentación)
```

---

## 🔧 ARCHIVOS A CREAR/ACTUALIZAR TÚ (Cuando quieras)

Si quieres que los usuarios registren sus números:

### `app/views/usuarios/listar.php` - AGREGAR CAMPOS
```html
<input type="tel" name="phone" placeholder="+1234567890">
<input type="tel" name="whatsapp" placeholder="+1234567890">
```

### `app/controllers/UsuarioController.php` - ACTUALIZAR
```php
$phone = trim($_POST['phone'] ?? null);
$whatsapp = trim($_POST['whatsapp'] ?? null);
// Guardar en BD
```

### `app/models/Usuario.php` - ACTUALIZAR CREATE
```php
public static function create(
    string $name, 
    string $email, 
    string $role, 
    string $password
    // Agregar parámetros:
    // string $phone = null,
    // string $whatsapp = null
) { ... }
```

---

## 📦 ARCHIVOS GENERADOS POR COMPOSER

Cuando ejecutes `composer require phpmailer/phpmailer` y `composer require twilio/sdk`:

```
vendor/
├── autoload.php              ← Incluir en tu app
├── phpmailer/
│   └── phpmailer/
│       ├── PHPMailer.php
│       ├── SMTP.php
│       └── Exception.php
├── twilio/
│   └── sdk/
│       └── Twilio/
│           ├── Rest/
│           ├── Exceptions/
│           └── ...
└── composer/
    └── autoload_*.php
```

**NO EDITES ESTOS ARCHIVOS** - Se regeneran automáticamente.

---

## 🎯 QUÉ HACE CADA ARCHIVO NUEVO

| Archivo | Propósito |
|---------|-----------|
| `config/api_keys.php` | Almacena credenciales de APIs (CONFIDENCIAL) |
| `app/services/EmailService.php` | Lógica para enviar emails con PHPMailer |
| `app/services/WhatsappService.php` | Lógica para enviar WhatsApp con Twilio |
| `app/services/SmsService.php` | Lógica para enviar SMS con Twilio |
| `app/services/README.md` | Documentación técnica de los servicios |
| `INSTALAR_SERVICIOS.md` | Guía paso a paso de instalación |
| `SERVICIOS_RESUMEN.md` | Este archivo - resumen de todo |

---

## ✅ ESTADO ACTUAL

✅ **Archivos creados:** 7  
✅ **Archivos modificados:** 3  
✅ **Nuevas funciones:** 20+  
✅ **Servicios integrados:** 3 (Email, WhatsApp, SMS)  
⏳ **Pasos para activar:** 5 (ver INSTALAR_SERVICIOS.md)  

---

## 🚀 PRÓXIMA ACCIÓN

📖 **Lee:** [INSTALAR_SERVICIOS.md](INSTALAR_SERVICIOS.md)

Sigue los 6 pasos para activar los servicios en tu aplicación.

---

**Última actualización:** 14/04/2026  
**Versión:** 1.0  
**Estado:** Listo para instalación ✅
