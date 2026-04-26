# Salón Social

Aplicación PHP (sin framework) para gestionar reservas de un salón social con módulo de inventario y usuarios por roles.

## Requisitos
- PHP 8.1+ con extensiones `mysqli` y `mbstring` (en XAMPP ya vienen). 
- MySQL / MariaDB.
- Servidor web apuntando a la carpeta `public/` (en XAMPP puedes usar `http://localhost/salon_social/public`).

## Instalación rápida (XAMPP en Windows)
1. Clona o copia el proyecto en `C:\xampp\htdocs\salon_social`.
2. Enciende Apache y MySQL en el panel de XAMPP.
3. Abre en el navegador:
   - `http://localhost/salon_social/public/?r=login`
4. La primera carga crea la base de datos y tablas automáticamente (usa `database/salon_social.sql`).

## Credenciales iniciales
- Correo: `admin@gmail.com`
- Contraseña: `admin123`
- Rol: `admin`

Si ya existe algún usuario en la tabla `users`, el seeding no se vuelve a ejecutar. Para cambiar la clave del admin:
1. Entra a MySQL y actualiza la fila con `id = 1`.
2. O ejecuta un `UPDATE` generando un `password_hash` con `PASSWORD_BCRYPT`.

## Configuración de base de datos
Variables de entorno soportadas (opcionales): `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`.
Por defecto: host `127.0.0.1`, usuario `root`, sin contraseña, base `salon_social`.

## Flujo de autenticación
- Ruta de login: `/?r=login` (GET muestra el formulario, POST valida en `AuthController::login`).
- Al iniciar sesión se guarda el usuario en `$_SESSION['user']`.
- Logout: `/?r=logout` (POST) destruye la sesión.

## Roles y permisos
- `admin`: acceso completo (reservas de todos, inventario, usuarios, reportes CSV).
- `supervisor`: inventario y reportes CSV.
- `residente`: solo sus reservas.
Las rutas y restricciones están en `routes/web.php`.

## Uso: reservas
1. **Mis reservas** (`/?r=reservas`): crear y ver tus reservas.
2. **Admin reservas** (`/?r=admin_reservas`, solo admin): aprobar/rechazar/cancelar reservas de todos.
3. Exportar CSV (admin/supervisor): `/?r=reporte_csv`.

## Uso: inventario
Ruta: `/?r=inventario` (admin y supervisor).

1) **Crear ítem**
   - Nombre, unidad (ej. "pcs"), stock inicial, mínimo, notas.
   - Guarda y el ítem aparece en la tabla de la izquierda.

2) **Registrar movimiento**
   - Selecciona ítem, cantidad, tipo (Entrada suma / Salida resta), motivo.
   - Se actualiza el stock y se registra en “Últimos movimientos”.

Notas actuales:
- No bloquea salidas que dejen stock negativo.
- El campo "mínimo" es informativo (no dispara alerta visible todavía).

## Uso: usuarios (solo admin)
- Ruta `/?r=usuarios`.
- Crear usuarios con rol `admin`, `supervisor` o `residente`.
- Activar/desactivar usuarios. No hay pantalla de cambio de contraseña; para cambiarla, actualizar `password_hash` en BD o crear un usuario nuevo.

## Estructura relevante
- `public/index.php`: front controller, carga rutas y aplica auth.
- `routes/web.php`: definición de vistas, controladores y roles.
- `app/controllers/*`: lógica de Auth, Usuarios, Reservas, Inventario, Reportes.
- `app/models/*`: modelos y conexión (`Conexion.php` inicializa DB y aplica schema.sql).
- `app/views/*`: vistas simples con PHP.
- `database/salon_social.sql`: schema completo.

## Troubleshooting
- **“Credenciales inválidas”**: verifica que entras por `/?r=login` y que usas `admin@gmail.com` / `admin123`. Si cambiaron las credenciales, revisa la tabla `users`.
- **No se crean tablas**: confirma que MySQL está levantado y que el usuario tiene permisos de creación; se lee el schema desde `database/salon_social.sql` en `Conexion::get()`.
- **Ruta rompe sin sesión**: las rutas con `auth` redirigen al login; las que requieren rol responden 403.

## Mejoras sugeridas (opcionales)
- Bloquear stock negativo y mostrar alerta cuando stock < mínimo.
- Pantalla “Mi perfil” para que cada usuario cambie su contraseña.
- Filtros por fecha y exportación CSV del inventario.

