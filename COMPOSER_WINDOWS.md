# 🪟 GUÍA RÁPIDA: INSTALAR COMPOSER EN WINDOWS

## ¿QUÉ ES COMPOSER?

Composer es el **"gerente de librerías"** de PHP. Te permite descargar e instalar paquetes automáticamente.

---

## 3 PASOS PARA INSTALAR COMPOSER

### PASO 1️⃣: Descargar el instalador

1. Ve a: **https://getcomposer.org/download/**
2. Busca la sección **"Windows Installer"**
3. Descarga el archivo `Composer-Setup.exe`

### PASO 2️⃣: Ejecutar el instalador

1. Haz doble clic en `Composer-Setup.exe`
2. Siguiente, siguiente, siguiente...
3. Si te pide elegir PHP, selecciona: **C:\xampp\php\php.exe**
4. Finish

### PASO 3️⃣: Verificar instalación

Abre **PowerShell** o **CMD** como administrador:

```bash
composer --version
```

Debería salir algo como:
```
Composer version 2.6.0 2023-11-29 09:52:13
```

✅ **¡Listo!** Composer está instalado.

---

## 📥 INSTALAR LAS LIBRERÍAS

Abre **PowerShell** en tu carpeta del proyecto:

```bash
# Ir a la carpeta del proyecto
cd C:\xampp\htdocs\salon_social

# Instalar PHPMailer (para emails)
composer require phpmailer/phpmailer

# Instalar Twilio SDK (para WhatsApp + SMS)
composer require twilio/sdk
```

**Resultado:** Se crea la carpeta `vendor/` automáticamente (puede tomar 1-2 minutos).

---

## 🆘 PROBLEMAS COMUNES

### ❌ "composer: No se reconoce como comando"

**Solución:**
1. Instala Composer nuevamente con Composer-Setup.exe
2. Reinicia PowerShell después de instalar
3. Verifica: `composer --version`

### ❌ "PHP executable not found"

**Solución:**
1. Abre Composer-Setup.exe de nuevo
2. Selecciona manualmente: `C:\xampp\php\php.exe`
3. Continúa

### ❌ Error descargando paquetes

**Solución:**
```bash
# Limpiar cache
composer clear-cache

# Intentar de nuevo
composer require phpmailer/phpmailer
```

### ❌ "Permission denied"

**Solución:**
- Abre PowerShell **como Administrador**
- Navega a tu carpeta
- Ejecuta el comando de nuevo

---

## ✅ VERIFICAR QUE FUNCIONÓ

```bash
# Debería existir esta carpeta:
C:\xampp\htdocs\salon_social\vendor\

# Y este archivo (línea 1):
C:\xampp\htdocs\salon_social\vendor\autoload.php
```

---

## 🎯 CUANDO EJECUTES COMPOSER

Verás algo así:

```
Using version ^2.11 for phpmailer/phpmailer
./composer.json has been updated
Running composer update phpmailer/phpmailer
...
Installing phpmailer/phpmailer (v6.8.1)
  - Installing phpmailer/phpmailer (v6.8.1): Loading from cache
```

Esto es **NORMAL** - Composer está descargando las librerías.

---

## 💡 PRÓXIMOS PASOS

1. ✅ Composer instalado
2. ✅ Librerías descargadas (vendor/)
3. ⏳ Seguir con: [INSTALAR_SERVICIOS.md](INSTALAR_SERVICIOS.md)

---

**¿Dudas?** Consulta: https://getcomposer.org/doc/00-intro.md
