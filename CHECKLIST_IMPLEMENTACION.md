# ✅ CHECKLIST DE IMPLEMENTACIÓN

## 📋 FASE 1: PREPARACIÓN (15 minutos)

- [ ] Descargar `Composer-Setup.exe` desde https://getcomposer.org/download/
- [ ] Ejecutar instalador y seleccionar `C:\xampp\php\php.exe`
- [ ] Reiniciar PowerShell/CMD
- [ ] Verificar: `composer --version` (debe mostrar versión)

## 📦 FASE 2: INSTALAR LIBRERÍAS (5-10 minutos)

```bash
cd C:\xampp\htdocs\salon_social
composer require phpmailer/phpmailer
composer require twilio/sdk
```

- [ ] Comando ejecutado sin errores
- [ ] Carpeta `vendor/` creada (verificar: `dir vendor`)
- [ ] Archivo `vendor/autoload.php` existe

## 🔑 FASE 3: OBTENER CREDENCIALES (10-20 minutos)

### Gmail:
- [ ] Ir a: https://myaccount.google.com/apppasswords
- [ ] Seleccionar Mail + Windows Computer
- [ ] Copiar contraseña (16 caracteres)
- [ ] Anotar en un lugar seguro

### Twilio:
- [ ] Registrarse en: https://www.twilio.com/
- [ ] Completar setup (te dan $15 de prueba)
- [ ] Obtener Account SID en Dashboard
- [ ] Obtener Auth Token en Dashboard
- [ ] (Opcional) Comprar número SMS
- [ ] Anotar en un lugar seguro

## 📝 FASE 4: CONFIGURAR ARCHIVOS (10 minutos)

- [ ] Abrir `config/api_keys.php`
- [ ] Reemplazar MAIL_USERNAME con tu email Gmail
- [ ] Reemplazar MAIL_PASSWORD con los 16 caracteres
- [ ] Reemplazar TWILIO_SID
- [ ] Reemplazar TWILIO_AUTH_TOKEN
- [ ] Guardar archivo
- [ ] ⚠️ VERIFICAR: Agregar `config/api_keys.php` a `.gitignore`

## 🗄️ FASE 5: ACTUALIZAR BASE DE DATOS (5 minutos)

Opción A (Nueva instalación):
- [ ] Ejecutar `schema.sql` en phpMyAdmin (ya tiene los campos)

Opción B (Si ya tienes datos):
```sql
ALTER TABLE users ADD COLUMN phone VARCHAR(20) DEFAULT NULL;
ALTER TABLE users ADD COLUMN whatsapp VARCHAR(20) DEFAULT NULL;
```

- [ ] Campos `phone` y `whatsapp` existen en tabla `users`
- [ ] Verificar en phpMyAdmin

## 👥 FASE 6: ACTUALIZAR FORMULARIOS (OPCIONAL - 10 minutos)

Si quieres que los usuarios registren sus números:

- [ ] Editar `app/views/usuarios/listar.php`
- [ ] Agregar campos `<input type="tel" name="phone"...>`
- [ ] Agregar campos `<input type="tel" name="whatsapp"...>`
- [ ] Editar `app/controllers/UsuarioController.php`
- [ ] Actualizar método `create()` para procesar phone y whatsapp
- [ ] Editar `app/models/Usuario.php`
- [ ] Actualizar método `create()` para guardar phone y whatsapp

## 🧪 FASE 7: PRUEBA DE SERVICIOS (5-10 minutos)

### Prueba Email:

1. Crear archivo `test_email.php` en raíz:
```php
<?php
require_once 'config/api_keys.php';
require_once 'vendor/autoload.php';
require_once 'app/services/EmailService.php';

$resultado = EmailService::enviarConfirmacionReserva(
    'tu-email@gmail.com',
    'Test User',
    [
        'titulo' => 'Prueba',
        'fecha_evento' => '2026-04-20',
        'hora_inicio' => '14:00',
        'hora_fin' => '18:00',
        'asistentes' => 10
    ]
);

echo $resultado ? "✅ Email enviado" : "❌ Error";
?>
```

2. Acceder: `http://localhost/salon_social/test_email.php`  
3. Verificar que llegó el email a tu bandeja

- [ ] Email de confirmación recibido

### Prueba WhatsApp (Opcional):

1. Verificar tu número en Twilio (Verified Caller IDs)
2. Crear `test_whatsapp.php` similar al anterior
3. Reemplazar número y llamar `WhatsappService::enviarConfirmacionReserva()`

- [ ] WhatsApp recibido en tu teléfono

## 🎯 FASE 8: PRUEBA EN PRODUCCIÓN (5 minutos)

1. Acceder a: `http://localhost/salon_social/`
2. Crear una nueva reserva desde dashboard
3. Verificar que se envíen notificaciones

- [ ] Email de confirmación recibido
- [ ] (Si tiene whatsapp) WhatsApp recibido
- [ ] Admin recibe notificación de nueva reserva

## 🔍 VERIFICAÇÕES FINALES

- [ ] No hay errores en `php error_log`
- [ ] Base de datos tiene registros de reservas
- [ ] Todos los servicios responden sin error
- [ ] `vendor/` está en `.gitignore`
- [ ] `config/api_keys.php` está en `.gitignore`

---

## 🚨 SOLUCIÓN DE PROBLEMAS

| Problema | Solución |
|----------|----------|
| "Class not found EmailService" | Verificar rutas en `require_once` |
| "Error al enviar email" | Verificar credenciales Gmail (16 caracteres) |
| "Twilio error: Invalid number" | Usar formato +país-número (e.g., +573001234567) |
| "vendor not found" | Ejecutar `composer require phpmailer/phpmailer` nuevamente |
| "Permission denied" | Abrir PowerShell como Administrador |

---

## 📚 ARCHIVOS DE REFERENCIA

Cuando necesites ayuda:

- **Instalación:** `COMPOSER_WINDOWS.md` + `INSTALAR_SERVICIOS.md`
- **Estructura:** `ESTRUCTURA_ACTUALIZADA.md`
- **Resumen:** `SERVICIOS_RESUMEN.md`
- **Técnico:** `app/services/README.md`

---

## ✨ CHECKLIST ANTES DE ENTREGAR

- [ ] Todos los pasos completados
- [ ] Pruebas exitosas
- [ ] Documentación actualizada
- [ ] `.gitignore` configurado
- [ ] No hay credenciales en código (solo en `config/api_keys.php`)
- [ ] Aplicación funciona completamente

---

## 🎉 ¡LISTO PARA PRODUCCIÓN!

Cuando todos los checkmarks estén marcados ✅, tu aplicación está lista para:

✅ Enviar emails de confirmación  
✅ Enviar mensajes WhatsApp  
✅ Enviar SMS de notificación  
✅ Notificar al admin automáticamente  
✅ Confirmar/rechazar reservas por notificación  

---

**Progreso:** [████████░░░░░░░░░░░░] XX%

Marque las fases conforme las completa para seguir tu avance.

**¿Necesitas ayuda?** Revisa la documentación en cada fase.
