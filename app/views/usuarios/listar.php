<section class="grid" style="grid-template-columns: 1fr;">
  <div class="card">
    <h2>👥 Usuarios del Sistema</h2>
    <table class="table">
      <tr>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Rol</th>
        <th>Estado</th>
        <th>Acción</th>
      </tr>
      <?php foreach ($usuarios as $u): ?>
      <tr>
        <td><strong><?php echo htmlspecialchars($u['name']); ?></strong></td>
        <td class="muted"><?php echo htmlspecialchars($u['email']); ?></td>
        <td>
          <?php 
            $rol_badge = $u['role'] === 'admin' ? 'danger' : ($u['role'] === 'supervisor' ? 'warn' : 'success');
            $rol_icon = $u['role'] === 'admin' ? '👑' : ($u['role'] === 'supervisor' ? '⭐' : '👤');
          ?>
          <span class="badge <?php echo $rol_badge; ?>"><?php echo $rol_icon; ?> <?php echo ucfirst($u['role']); ?></span>
        </td>
        <td>
          <span class="semaforo <?php echo $u['active'] ? 'verde' : 'rojo'; ?>">
            <?php echo $u['active'] ? '✓ Activo' : '✗ Inactivo'; ?>
          </span>
        </td>
        <td>
            <form method="post" action="<?php echo route_to('usuarios_toggle'); ?>">
                <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                <input type="hidden" name="active" value="<?php echo $u['active'] ? 0 : 1; ?>">
                <button type="submit" class="action-btn">
                  <?php echo $u['active'] ? '🔴 Desactivar' : '🟢 Activar'; ?>
                </button>
            </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</section>

<section class="grid" style="grid-template-columns: 1fr; margin-top: 60px;">
  <div class="card">
    <h2>➕ Crear Usuario</h2>
    <form method="post" action="<?php echo route_to('usuarios_crear'); ?>">
      <label>👤 Nombre Completo</label>
      <input name="name" required placeholder="Ej: Juan Pérez">
      
      <label>📧 Correo Electrónico</label>
      <input type="email" name="email" required placeholder="correo@ejemplo.com">
      
      <label>🎭 Rol</label>
      <select name="role">
        <option value="residente">👤 Residente</option>
        <option value="supervisor">⭐ Supervisor</option>
        <option value="admin">👑 Admin</option>
      </select>
      
      <label>🔐 Contraseña</label>
      <input type="password" name="password" required placeholder="Mínimo 8 caracteres">
      
      <button type="submit">✅ Crear Usuario</button>
    </form>
  </div>
</section>
