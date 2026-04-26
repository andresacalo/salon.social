# 📊 INFORME DE REQUISITOS DEL SISTEMA
## Sistema de Gestión de Reservas y Control de Inventario - Salón Social

**Fecha:** 14 de Abril, 2026  
**Proyecto:** Salón Social  
**Estado:** Entrega 1 - Análisis de Requisitos y Ubicación en el Código

---

## 📌 TABLA DE CONTENIDOS

1. [Introducción](#introducción)
2. [Requisitos Funcionales](#requisitos-funcionales)
3. [Estado de Implementación](#estado-de-implementación)
4. [Detalle por Requisito](#detalle-por-requisito)
5. [Archivos Clave del Proyecto](#archivos-clave-del-proyecto)
6. [Conclusiones](#conclusiones)

---

## 🎯 INTRODUCCIÓN

Este informe detalla el análisis de los 7 requisitos principales que debe cumplir el sistema de gestión del Salón Social. Para cada requisito se especifica:

- ✅ Qué se debe hacer
- 📍 Dónde está implementado en el código
- 🔧 Cómo funciona
- ✔️ Estado actual

---

## 📋 REQUISITOS FUNCIONALES

El sistema debe cumplir con los siguientes requisitos:

| # | Requisito | Puntuación | Prioridad |
|---|-----------|-----------|-----------|
| 1 | Administrar usuarios con perfiles (Admin, Residente, Supervisor) | 0.5 | 🔴 Alta |
| 2 | Autenticación de usuarios (Login) | 0.3 | 🔴 Alta |
| 3 | Tabla de inventario bien estructurada en BD | 0.5 | 🔴 Alta |
| 4 | Administración y control de insumos | 0.4 | 🟡 Media |
| 5 | Administración y control de reservas | 0.4 | 🟡 Media |
| 6 | Admin puede autorizar o rechazar reservas | 0.4 | 🟡 Media |
| 7 | Validación de fechas (48h mínimo, 90d máximo) | 0.5 | 🔴 Alta |
| **TOTAL** | **3.0 puntos** | | |

---

# 🔍 DETALLE POR REQUISITO

---

## ✅ REQUISITO 1: Administrar Usuarios (0.5 puntos)

### 📝 Descripción:
El sistema debe permitir crear usuarios con los siguientes perfiles:
- 👑 **Administrador** - Puede crear/modificar/eliminar usuarios y aprobar reservas
- 👤 **Residente** - Puede createservas y ver su inventario personal
- ⭐ **Supervisor** - Puede controlar inventario y ver reportes

Solo el administrador puede crear, activar o desactivar usuarios.

### 🎯 Funcionalidades Implementadas:

#### 1. Base de Datos
**Archivo:** `schema.sql` (Líneas 4-11)

```sql
CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','residente','supervisor') NOT NULL DEFAULT 'residente',
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Campos importantes:**
- `role` - Define el tipo de usuario (solo 3 opciones permitidas)
- `active` - Permite desactivar sin borrar (1=activo, 0=inactivo)
- `password_hash` - Contraseña encriptada (nunca en texto plano)

#### 2. Modelo de Base de Datos
**Archivo:** `app/models/Usuario.php`

```php
// Método para crear un nuevo usuario
public static function create(string $name, string $email, string $role, string $password): void

// Método para cambiar estado (activar/desactivar)
public static function toggle(int $id, int $active): void

// Método que crea automáticamente un admin si no existe
public static function seedAdmin(): void
```

#### 3. Controlador (Lógica de Negocio)
**Archivo:** `app/controllers/UsuarioController.php`

```php
// Solo admin puede crear usuarios
public function crear(): void {
    $this->requireRole(['admin']); // Valida que sea admin
    // Crear el usuario...
}

// Solo admin puede activar/desactivar
public function toggle(): void {
    $this->requireRole(['admin']); // Valida que sea admin
    // Cambiar estado...
}
```

**Protección:** El método `requireRole()` asegura que solo administradores accedan.

#### 4. Interfaz de Usuario
**Archivo:** `app/views/usuarios/listar.php`

La página muestra:
- 👥 **Tabla de Usuarios** con:
  - Nombre del usuario
  - Correo electrónico
  - Rol actual (con cada en diferentes colores)
  - Estado (Activo/Inactivo)
  - Botones para Activar/Desactivar

- ➕ **Formulario Crear Usuario** con:
  - Campo Nombre
  - Campo Email
  - Selector de Rol (Residente, Supervisor, Admin)
  - Campo Contraseña

#### 5. Rutas Protegidas
**Archivo:** `routes/web.php`

```php
'usuarios' => [
    'view' => 'usuarios/listar',
    'role' => ['admin']  // Solo admin accede
],
'usuarios_crear' => [
    'post' => ['UsuarioController','crear'],
    'role' => ['admin']  // Solo admin puede crear
],
'usuarios_toggle' => [
    'post' => ['UsuarioController','toggle'],
    'role' => ['admin']  // Solo admin puede cambiar estado
],
```

### ✔️ Estado: **✅ COMPLETADO Y FUNCIONAL**

**Interfaz:**
```
┌─────────────────────────────────────────────────┐
│ 👥 Usuarios del Sistema                         │
├─────────────────────────────────────────────────┤
│ Nombre      │ Correo        │ Rol      │ Acción│
├─────────────────────────────────────────────────┤
│ Juan Pérez  │ juan@...      │ 👑 Admin │ 🔴    │
│ María López │ maria@...     │ 👤 Residente│ 🟢 │
└─────────────────────────────────────────────────┘
│ ➕ Crear Usuario                                 │
│ Nombre: [____________________]                  │
│ Correo: [____________________]                  │
│ Rol:    [Admin ▼]                              │
│ Contraseña: [________________]     [Crear]     │
└─────────────────────────────────────────────────┘
```

---

## ✅ REQUISITO 2: Loguear Usuarios (0.3 puntos)

### 📝 Descripción:
Los usuarios creados en el sistema deben poder ingresar con sus credenciales (email + contraseña).

### 🎯 Funcionalidades Implementadas:

#### 1. Base de Datos
**Archivo:** `schema.sql` - Tabla `users`

```sql
email VARCHAR(160) NOT NULL UNIQUE,  -- Email único para cada usuario
password_hash VARCHAR(255) NOT NULL,  -- Contraseña encriptada
```

#### 2. Modelo - Validación de Credenciales
**Archivo:** `app/models/Usuario.php`

```php
public static function login(string $email, string $password): ?array {
    // Busca el usuario por email
    // Verifica que la contraseña sea correcta
    // Retorna los datos del usuario o null si falla
}
```

#### 3. Controlador - Gestión de Sesión
**Archivo:** `app/controllers/AuthController.php`

```php
public function login(): void {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Valida credenciales
    $user = Usuario::login($email, $password);
    
    if ($user) {
        $_SESSION['user'] = $user;  // Guarda en sesión
        header('Location: ' . route_to('dashboard'));
    } else {
        flash('error', 'Credenciales inválidas');
    }
}

public function logout(): void {
    unset($_SESSION['user']);  // Elimina sesión
    header('Location: ' . route_to('login'));
}
```

#### 4. Interfaz de Login
**Archivo:** `app/views/auth/login.php`

```html
┌──────────────────────────┐
│  🏰 Bienvenido           │
│                          │
│ 📧 Correo                │
│ [correo@ejemplo.com]    │
│                          │
│ 🔐 Contraseña            │
│ [________________]       │
│                          │
│   [🔓 Entrar]            │
└──────────────────────────┘
```

#### 5. Protección de Páginas
**Archivo:** `public/index.php`

```php
// En cada página que requiere login
$user = current_user();  // Obtiene usuario actual
if (($route['auth'] ?? false) && !$user) {
    // Si requiere auth y no está logueado
    header('Location: ' . route_to('login'));
    exit;
}
```

#### 6. Rutas
**Archivo:** `routes/web.php`

```php
'login' => [
    'view' => 'auth/login',
    'post' => ['AuthController','login']
],
'logout' => [
    'post' => ['AuthController','logout'],
    'auth' => true  // Requiere estar logueado
],
```

### ✔️ Estado: **✅ COMPLETADO Y FUNCIONAL**

**Flujo:**
```
[Login Form] → [Validar Email/Pass] → [Crear Sesión] → [Redirect Dashboard]
                                   ↓
                          [Error? Mostrar mensaje y volver]
```

---

## ✅ REQUISITO 3: Tabla de Inventario en BD (0.5 puntos)

### 📝 Descripción:
Diseñar una tabla de base de datos bien estructurada para almacenar los insumos (artículos) del salón.

### 🎯 Estructura Implementada:

#### 1. Tabla Principal de Artículos
**Archivo:** `schema.sql` (Líneas 14-20)

```sql
CREATE TABLE inventario_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(140) NOT NULL,
  unidad INT DEFAULT 1,
  notas TEXT,
  creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Campos y su propósito:**
| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | INT PRIMARY KEY | Identificador único del artículo |
| `nombre` | VARCHAR(140) | Nombre del artículo (Ej: "Sillas", "Mesas") |
| `unidad` | INT | Cantidad base/unidad de medida |
| `notas` | TEXT | Notas adicionales (color, estado, etc.) |
| `creado_el` | TIMESTAMP | Fecha de creación automática |

#### 2. Tabla de Movimientos (Historial)
**Archivo:** `schema.sql` (Líneas 22-34)

```sql
CREATE TABLE inventario_movimientos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  item_id INT UNSIGNED NOT NULL,
  cantidad INT NOT NULL,
  tipo ENUM('entrada','salida') NOT NULL,
  motivo VARCHAR(255) DEFAULT NULL,
  usuario_id INT UNSIGNED DEFAULT NULL,
  creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_item FOREIGN KEY (item_id) REFERENCES inventario_items(id),
  CONSTRAINT fk_mov_user FOREIGN KEY (usuario_id) REFERENCES users(id),
  INDEX idx_item (item_id)
);
```

**Campos y su propósito:**
| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | INT PRIMARY KEY | Identificador del movimiento |
| `item_id` | INT FK | Referencia al artículo |
| `cantidad` | INT | Cantidad movida |
| `tipo` | ENUM | "entrada" (ingresa) o "salida" (sale) |
| `motivo` | VARCHAR | Razón del movimiento |
| `usuario_id` | INT FK | Quién hizo el movimiento |
| `creado_el` | TIMESTAMP | Cuándo se hizo |
| `idx_item` | INDEX | Índice para búsquedas rápidas |

#### 3. Relaciones (Constraints)
```
users (1) ──── (N) inventario_movimientos
    ↑                    ↑
    └────────────────────┘
              ↓
    inventario_items (1) ──── (N) inventario_movimientos
```

**Explicación:**
- Un artículo puede tener muchos movimientos
- Un usuario puede registrar muchos movimientos
- Cada movimiento se registra con fecha automática

### ✔️ Estado: **✅ COMPLETADO Y FUNCIONAL**

**Ventajas del diseño:**
- ✅ Historial completo de movimientos
- ✅ Rastreabilidad (quién, cuándo, qué)
- ✅ Reportes precisos
- ✅ Auditoría de cambios

---

## ✅ REQUISITO 4: Administración de Insumos (0.4 puntos)

### 📝 Descripción:
La aplicación debe permitir administrar y controlar los insumos del salón (crear, listar, registrar movimientos de entrada/salida).

### 🎯 Funcionalidades Implementadas:

#### 1. Modelo - Operaciones CRUD
**Archivo:** `app/models/Inventario.php`

```php
// Crear nuevo artículo
public static function create(string $name, int $unit, string $notes): void

// Registrar movimiento (entrada/salida)
public static function move(int $itemId, int $qty, string $kind, string $reason, int $userId): void

// Obtener todos los artículos
public static function all(): array

// Obtener últimos movimientos
public static function movements(): array
```

#### 2. Controlador - Validación y Lógica
**Archivo:** `app/controllers/InventarioController.php`

```php
// Solo admin y supervisor pueden
public function crearItem(): void {
    $this->requireRole(['admin','supervisor']);
    // Crear artículo nuevo
}

public function movimiento(): void {
    $this->requireRole(['admin','supervisor']);
    // Registrar entrada o salida
    // Valida que exista el artículo
    // Valida cantidad positiva
}
```

#### 3. Interfaz - Vista de Gestión
**Archivo:** `app/views/inventario/listar.php`

La interfaz está dividida en 4 secciones:

**Sección 1: Tabla de Inventario Existente**
```
┌────────────────────────────────────────────┐
│ 📦 Inventario                              │
├────────────┬──────────┬───────────────────┤
│ Item       │ Unidad   │ Notas             │
├────────────┼──────────┼───────────────────┤
│ Sillas     │ 50       │ Color: Blanco     │
│ Mesas      │ 20       │ Madera            │
│ Luces      │ 100      │ LED               │
└────────────┴──────────┴───────────────────┘
```

**Sección 2: Crear Nuevo Artículo**
```
┌──────────────────────────────┐
│ ➕ Crear Ítem                │
│                              │
│ 📝 Nombre del Artículo       │
│ [Ej: Sillas, Mesas, etc. ]  │
│                              │
│ 📊 Unidad/Cantidad Base      │
│ [50]                         │
│                              │
│ 📋 Notas Adicionales         │
│ [Color, estado, etc.]        │
│                              │
│        [✅ Guardar Ítem]     │
└──────────────────────────────┘
```

**Sección 3: Registrar Movimiento**
```
┌──────────────────────────────┐
│ 🔄 Registrar Movimiento      │
│                              │
│ 📦 Selecciona Ítem           │
│ [Sillas ▼]                  │
│                              │
│ 🔢 Cantidad                  │
│ [10]                         │
│                              │
│ ➡️ Tipo de Movimiento         │
│ [📥 Entrada ▼]              │
│                              │
│ ⚠️ Motivo                     │
│ [Reabastecimiento]          │
│                              │
│      [✅ Registrar]          │
└──────────────────────────────┘
```

**Sección 4: Historial de Movimientos**
```
┌───────────────────────────────────────────────────────┐
│ 📅 Últimos Movimientos                                │
├──────────┬──────────┬────┬────────┬──────────┬────────┤
│ Fecha    │ Ítem     │Cant│ Tipo   │ Motivo   │Usuario │
├──────────┼──────────┼────┼────────┼──────────┼────────┤
│ 14/04/26 │ Sillas   │ 50 │📥Entrada│Evento  │ Admin  │
│ 13/04/26 │ Mesas    │ 5  │📤Salida │Mantenimto│Juan  │
└──────────┴──────────┴────┴────────┴──────────┴────────┘
```

#### 4. Rutas
**Archivo:** `routes/web.php`

```php
'inventario' => [
    'view' => 'inventario/listar',
    'role' => ['admin','supervisor']  // Solo admin y supervisor
],
'inventario_crear' => [
    'post' => ['InventarioController','crearItem'],
    'role' => ['admin','supervisor']
],
'inventario_mov' => [
    'post' => ['InventarioController','movimiento'],
    'role' => ['admin','supervisor']
],
```

### ✔️ Estado: **✅ COMPLETADO Y FUNCIONAL**

**Funcionalidades:**
- ✅ Crear artículos nuevos
- ✅ Listar todos los artículos
- ✅ Registrar entradas de inventario
- ✅ Registrar salidas de inventario
- ✅ Visualizar historial completo
- ✅ Rastrear quién hizo cada movimiento

---

## ✅ REQUISITO 5: Administración de Reservas (0.4 puntos)

### 📝 Descripción:
La aplicación debe permitir administrar y controlar las reservas realizadas por los residentes y el administrador.

### 🎯 Funcionalidades Implementadas:

#### 1. Base de Datos
**Archivo:** `schema.sql` (Líneas 36-49)

```sql
CREATE TABLE reservas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT UNSIGNED NOT NULL,
  titulo VARCHAR(140) NOT NULL,
  fecha_evento DATE NOT NULL,
  hora_inicio TIME NOT NULL,
  hora_fin TIME NOT NULL,
  asistentes INT DEFAULT 0,
  notas TEXT,
  estado ENUM('pendiente','aprobada','rechazada','cancelada') NOT NULL DEFAULT 'pendiente',
  creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_res_user FOREIGN KEY (usuario_id) REFERENCES users(id),
  INDEX idx_fecha (fecha_evento)
);
```

**Estados de Reserva:**
- 🔵 **pendiente** - Espera aprobación del admin
- ✅ **aprobada** - Autorizada por admin
- ❌ **rechazada** - Rechazada por admin
- 🚫 **cancelada** - Cancelada por usuario

#### 2. Modelo
**Archivo:** `app/models/Reserva.php`

```php
// Crear nueva reserva
public static function create(int $userId, string $title, string $date, ...): void

// Obtener reservas de un usuario
public static function forUser(int $userId): array

// Obtener todas las reservas (para admin)
public static function all(): array

// Cambiar estado de una reserva
public static function changeStatus(int $id, string $status): void

// Obtener próximas reservas
public static function upcoming(): array

// Obtener estadísticas
public static function stats(): array
```

#### 3. Controlador
**Archivo:** `app/controllers/ReservaController.php`

```php
public function crear(): void {
    // Residentes pueden crear reservas
    // Se valida fecha, hora, duración, etc.
    // Se crea con estado 'pendiente'
}

public function cambiarEstado(): void {
    $this->requireRole(['admin']);  // Solo admin
    // Approbar o rechazar reservas
    // Actualiza el estado en BD
}
```

#### 4. Interfaz - Vista Residente
**Archivo:** `app/views/reservas/listar.php`

**Para Residentes:**
```
┌─────────────────────────────────────────┐
│ 📅 Nueva Reserva                        │
├─────────────────────────────────────────┤
│ 📆 Fecha del Evento                     │
│ [2026-04-20]                            │
│                                         │
│ 🕐 Hora de Inicio                       │
│ [14:00]                                 │
│                                         │
│ 🕑 Hora de Fin                          │
│ [18:00]                                 │
│                                         │
│ 🎯 Título del Evento                    │
│ [Cumpleaños]                            │
│                                         │
│ 👥 Número de Asistentes                 │
│ [50]                                    │
│                                         │
│ 📝 Notas Adicionales                    │
│ [Música disco, luces especiales]        │
│                                         │
│         [✅ Reservar]                   │
└─────────────────────────────────────────┘

┌──────────────────────────────────────────┐
│ 📋 Mis Reservas                          │
├──────────┬────────┬─────┬────────┬──────┤
│ Fecha    │ Inicio │ Fin │ Título │ Est. │
├──────────┼────────┼─────┼────────┼──────┤
│ 20/04/26 │ 14:00  │18:00│Cumple │✅Aprb│
│ 15/05/26 │ 20:00  │23:00│Fiesta │⏳Pend│
└──────────┴────────┴─────┴────────┴──────┘
```

#### 5. Dashboard - Estadísticas
**Archivo:** `app/views/dashboard/index.php`

```
┌────────────────────────────────────────┐
│ 📊 Estado de Reservas                  │
├────────────┬────────────┬──────────────┤
│✅ Aprobadas│ ⏳ Pendientes│ ❌ Rechazadas│
│     12     │      5     │      2      │
└────────────┴────────────┴──────────────┘

┌────────────────────────────────────────┐
│ 🚨 Próximas Reservas (Semáforo)        │
├──────────┬────────┬────────┬──────────┤
│ Fecha    │Usuario │ Estado │Semáforo  │
├──────────┼────────┼────────┼──────────┤
│ 20/04/26 │ Juan   │Aprobada│ 🟢 6 días│
│ 17/04/26 │ María  │Espera  │ 🟡 3 días│
│ 15/04/26 │ Pedro  │Aprobada│ 🔴 1 día │
└──────────┴────────┴────────┴──────────┘
```

#### 6. Rutas
**Archivo:** `routes/web.php`

```php
'dashboard' => ['view' => 'dashboard/index', 'auth' => true],
'reservas' => ['view' => 'reservas/listar', 'auth' => true],
'reservas_crear' => ['post' => ['ReservaController','crear'], 'auth' => true],
'reporte_csv' => ['get' => ['ReporteController','csvReservas'], 'role' => ['admin','supervisor']],
```

### ✔️ Estado: **✅ COMPLETADO Y FUNCIONAL**

**Funcionalidades:**
- ✅ Residentes pueden crear reservas
- ✅ Residentes ven sus propias reservas
- ✅ Estadísticas en dashboard
- ✅ Semáforo de urgencia (próximas reservas)
- ✅ Exportar reportes

---

## ✅ REQUISITO 6: Admin Autoriza/Rechaza Reservas (0.4 puntos)

### 📝 Descripción:
El administrador debe poder ver todas las reservas del sistema y puede autorizar (aprobar) o rechazar cada una.

### 🎯 Funcionalidades Implementadas:

#### 1. Vista Exclusiva del Admin
**Archivo:** `app/views/reservas/aprobar.php`

```
┌─────────────────────────────────────────────────────────┐
│ ✅ Aprobar Reservas                                     │
│ [📊 Descargar Reporte CSV]                              │
├──────────┬─────────┬─────────┬────────┬──────┬─────────┤
│ Fecha    │ Horario │ Usuario │ Título │ Est. │ Acciones│
├──────────┼─────────┼─────────┼────────┼──────┼─────────┤
│ 20/04/26 │14-18:00 │ Juan    │Cumple  │⏳Pend│[✅][❌] │
│ 25/04/26 │20-23:00 │ María   │Fiesta  │⏳Pend│[✅][❌] │
│ 19/04/26 │10-14:00 │ Pedro   │Reunión │⏳Pend│[✅][❌] │
└──────────┴─────────┴─────────┴────────┴──────┴─────────┘
```

Cada fila tiene:
- **[✅ Aprobar]** - Botón para autorizar
- **[❌ Rechazar]** - Botón para denegar

#### 2. Controlador - Lógica de Cambio de Estado
**Archivo:** `app/controllers/ReservaController.php`

```php
public function cambiarEstado(): void {
    $this->requireRole(['admin']);  // Solo admin accede
    
    $id = (int)($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';  // 'aprobada' o 'rechazada'
    
    // Validar que sea un estado válido
    if (in_array($status, ['aprobada', 'rechazada'])) {
        Reserva::changeStatus($id, $status);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Reserva actualizada'];
    }
    
    header('Location: ' . route_to('admin_reservas'));
}
```

#### 3. Modelo - Actualizar Estado
**Archivo:** `app/models/Reserva.php`

```php
public static function changeStatus(int $id, string $status): void {
    $db = Conexion::get();
    $stmt = $db->prepare('UPDATE reservas SET estado = ? WHERE id = ?');
    $stmt->bind_param('si', $status, $id);
    $stmt->execute();
}
```

#### 4. Ruta Protegida
**Archivo:** `routes/web.php`

```php
'admin_reservas' => [
    'view' => 'reservas/aprobar',
    'role' => ['admin']  // Solo admin puede ver esta página
],
'reservas_estado' => [
    'post' => ['ReservaController','cambiarEstado'],
    'role' => ['admin']  // Solo admin puede cambiar estado
],
```

#### 5. También: Descarga de Reportes
**Archivo:** `app/controllers/ReporteController.php`

```php
public function csvReservas(): void {
    $this->requireRole(['admin','supervisor']);
    
    // Obtiene todas las reservas
    // Genera archivo CSV
    // Descarga automáticamente
}
```

### ✔️ Estado: **✅ COMPLETADO Y FUNCIONAL**

**Flujo:**
```
┌──────────────────────┐
│  Admin ve reservas   │
│   (todos pendientes) │
└──────────────────────┘
         ↓
    [Lee detalles]
         ↓
    ┌────┴────┐
    ↓         ↓
[Aprobar] [Rechazar]
    ↓         ↓
  ✅    ❌
(reserva aprobada)  (reserva rechazada)
```

---

## ⚠️ REQUISITO 7: Validación de Fechas (0.5 puntos)

### 📝 Descripción:
El sistema NO debe permitir:
- ❌ Reservas con menos de 48 horas a partir de hoy
- ❌ Reservas con más de 90 días a partir de hoy

### 📍 Estado: **⚠️ PENDIENTE DE IMPLEMENTAR**

### 🔧 Qué Falta:

#### 1. Validación Backend (PHP)
**Archivo:** `app/controllers/ReservaController.php` - Método `crear()`

Necesita agregar:
```php
public function crear(): void {
    $fecha_evento = $_POST['fecha_evento'] ?? '';
    $hoy = new DateTime();
    $fechaEvento = new DateTime($fecha_evento);
    
    // Calcular diferencia en días
    $diferencia = $hoy->diff($fechaEvento)->days;
    
    // VALIDACIÓN 1: Mínimo 48 horas (2 días)
    if ($diferencia < 2) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'La reserva debe ser con al menos 48 horas de anticipación'];
        header('Location: ' . route_to('reservas'));
        exit;
    }
    
    // VALIDACIÓN 2: Máximo 90 días
    if ($diferencia > 90) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'No se pueden hacer reservas con más de 90 días de anticipación'];
        header('Location: ' . route_to('reservas'));
        exit;
    }
    
    // Si pasa las validaciones, crear la reserva...
    Reserva::create(...);
}
```

#### 2. Validación Frontend (HTML5)
**Archivo:** `app/views/reservas/listar.php`

Necesita cambiar:
```php
<!-- ACTUAL (sin validación) -->
<input type="date" name="fecha_evento" required>

<!-- NUEVO (con validación) -->
<?php
    $hoy = new DateTime();
    $min = $hoy->modify('+2 days')->format('Y-m-d');
    
    $max = new DateTime();
    $max->modify('+90 days')->format('Y-m-d');
?>
<input type="date" name="fecha_evento" required min="<?php echo $min; ?>" max="<?php echo $max; ?>">
```

#### 3. Ejemplo Visual
```
HOY: 14 de Abril de 2026

MÍNIMO PERMITIDO: 16 de Abril (48 horas después)
MÁXIMO PERMITIDO: 13 de Julio (90 días después)

Rango válido de fechas: [16/04/2026 - 13/07/2026]

❌ 15/04/2026 - NO PERMITIDA (menos de 48h)
❌ 14/07/2026 - NO PERMITIDA (más de 90 días)
✅ 20/04/2026 - PERMITIDA
✅ 01/07/2026 - PERMITIDA
```

### 📊 Impacto:
- Mejora seguridad de reservas
- Evita reservas de último minuto
- Impide sobrecargas futuras
- Asegura organización

---

## 📂 ARCHIVOS CLAVE DEL PROYECTO

### Estructura del Proyecto
```
salon_social/
├── app/
│   ├── controllers/
│   │   ├── AuthController.php          ✅ REQ 2
│   │   ├── UsuarioController.php       ✅ REQ 1
│   │   ├── InventarioController.php    ✅ REQ 4
│   │   ├── ReservaController.php       ✅ REQ 5,6
│   │   └── ReporteController.php       ✅ REQ 6
│   │
│   ├── models/
│   │   ├── Usuario.php                 ✅ REQ 1
│   │   ├── Inventario.php              ✅ REQ 3,4
│   │   ├── Reserva.php                 ✅ REQ 5,6
│   │   └── Conexion.php                 (Conexión BD)
│   │
│   ├── views/
│   │   ├── auth/
│   │   │   └── login.php               ✅ REQ 2
│   │   ├── usuarios/
│   │   │   └── listar.php              ✅ REQ 1
│   │   ├── inventario/
│   │   │   └── listar.php              ✅ REQ 3,4
│   │   ├── reservas/
│   │   │   ├── listar.php              ✅ REQ 5,7(parcial)
│   │   │   └── aprobar.php             ✅ REQ 6
│   │   ├── dashboard/
│   │   │   └── index.php               ✅ REQ 5
│   │   └── layouts/
│   │       ├── header.php
│   │       └── footer.php
│   │
│   └── helpers.php                      (Funciones auxiliares)
│
├── config/
│   └── coneccion.php                   (Configuración BD)
│
├── routes/
│   └── web.php                         ✅ Todas las rutas
│
├── public/
│   ├── index.php                       (Front controller)
│   └── css/
│       └── style.css                   (Estilos: 500+ líneas)
│
├── database/
│   └── salon_social.sql                (Script SQL)
│
└── schema.sql                          ✅ REQ 3 (Base de datos)
```

### Archivos Críticos
| Archivo | Propósito | Líneas |
|---------|-----------|--------|
| `schema.sql` | Define todas las tablas | 50 |
| `app/models/Usuario.php` | Lógica de usuarios | 40 |
| `app/models/Inventario.php` | Lógica de inventario | 35 |
| `app/models/Reserva.php` | Lógica de reservas | 50 |
| `app/controllers/AuthController.php` | Autenticación | 30 |
| `app/controllers/UsuarioController.php` | Gestión usuarios | 25 |
| `app/controllers/InventarioController.php` | Gestión inventario | 40 |
| `app/controllers/ReservaController.php` | Gestión reservas | 35 |
| `routes/web.php` | Enrutamiento | 20 |
| `public/css/style.css` | Interfaz visual | 500+ |

---

## 📊 RESUMEN DE ESTADO

### Tabla Completa de Requisitos
```
┌────┬────────────────────────────┬─────┬─────────┬──────────┐
│ #  │ Requisito                  │ Pts │ Estado  │ Puntos   │
├────┼────────────────────────────┼─────┼─────────┼──────────┤
│ 1  │ Usuarios + Perfiles        │0.5  │ ✅ LISTO│ 0.5/0.5  │
│ 2  │ Login                      │0.3  │ ✅ LISTO│ 0.3/0.3  │
│ 3  │ Tabla Inventario BD        │0.5  │ ✅ LISTO│ 0.5/0.5  │
│ 4  │ Gestión Insumos            │0.4  │ ✅ LISTO│ 0.4/0.4  │
│ 5  │ Gestión Reservas           │0.4  │ ✅ LISTO│ 0.4/0.4  │
│ 6  │ Admin Autoriza Reservas    │0.4  │ ✅ LISTO│ 0.4/0.4  │
│ 7  │ Validación Fechas 48h-90d  │0.5  │ ⚠️ FALTA│ 0.0/0.5  │
├────┼────────────────────────────┼─────┼─────────┼──────────┤
│    │ TOTAL ENTREGA 1            │3.0  │         │ 2.5/3.0  │
└────┴────────────────────────────┴─────┴─────────┴──────────┘
```

---

## 🎯 CONCLUSIONES

### ✅ Lo que Está Completado (2.5/3.0 puntos):

1. **Sistema de Usuarios Robusto**
   - Tres perfiles bien definidos
   - Protección de rutas por rol
   - Gestión solo para admin

2. **Autenticación Funcional**
   - Login con email/contraseña
   - Sesiones seguras
   - Logout disponible

3. **Base de Datos Bien Estructurada**
   - Tablas normalizadas
   - Relaciones definidas
   - Índices para velocidad

4. **Gestión de Inventario Completa**
   - Crear artículos
   - Registrar movimientos
   - Historial disponible

5. **Sistema de Reservas Integrado**
   - Residentes pueden reservar
   - Admin puede aprobar/rechazar
   - Dashboard con estadísticas
   - Semáforo de urgencia

6. **Reportes Disponibles**
   - Exportar a CSV
   - Filtros de usuario/fecha

### ⚠️ Lo que Falta (0.5 puntos):

1. **Validación de Fechas**
   - No permite reservas < 48 horas
   - No permite reservas > 90 días
   - Se necesita agregar en:
     - `ReservaController.php`
     - `views/reservas/listar.php`

### 📈 Recomendaciones para la Próxima Entrega:

1. Implementar validación de fechas (Req. 7)
2. Agregar notificaciones por email
3. Sistema de comentarios en reservas
4. Gráficos de uso del salón
5. Mejoras en seguridad (two-factor auth)
6. Backup automático de BD

---

## 📞 CONTACTO Y SOPORTE

**Se entregan los siguientes documentos:**
1. Este informe completo
2. Documento de cambios (CAMBIOS_REALIZADOS.md)
3. Código fuente comentado
4. Script de base de datos

**Para consultas:**
- Revisar los comentarios en el código
- Consultar la documentación inline
- Ejecutar la aplicación en localhost

---

**Documento Generado:** 14 de Abril, 2026  
**Versión:** 1.0  
**Estado:** Ready for First Delivery ✅

