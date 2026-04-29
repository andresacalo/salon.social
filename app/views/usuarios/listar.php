<section class="grid" style="grid-template-columns: 1fr;">
  <div class="card">
    <h2 data-i18n="usersSystemTitle"><?php echo htmlspecialchars(t('usersSystemTitle')); ?></h2>
    <table class="table">
      <tr>
        <th data-i18n="name"><?php echo htmlspecialchars(t('name')); ?></th>
        <th data-i18n="email"><?php echo htmlspecialchars(t('email')); ?></th>
        <th data-i18n="rol"><?php echo htmlspecialchars(t('rol')); ?></th>
        <th data-i18n="state"><?php echo htmlspecialchars(t('state')); ?></th>
        <th data-i18n="action"><?php echo htmlspecialchars(t('action')); ?></th>
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
          <span class="badge <?php echo $rol_badge; ?>"><?php echo $rol_icon; ?> <?php echo htmlspecialchars(t_role($u['role'])); ?></span>
        </td>
        <td>
          <span class="semaforo <?php echo $u['active'] ? 'verde' : 'rojo'; ?>">
            <?php echo htmlspecialchars(t($u['active'] ? 'active' : 'inactive')); ?>
          </span>
        </td>
        <td>
            <form method="post" action="<?php echo route_to('usuarios_toggle'); ?>">
                <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                <input type="hidden" name="active" value="<?php echo $u['active'] ? 0 : 1; ?>">
                <button type="submit" class="action-btn">
                  <?php echo $u['active'] ? '🔴 ' . htmlspecialchars(t('rejectButton')) : '🟢 ' . htmlspecialchars(t('approveButton')); ?>
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
    <h2 data-i18n="createUserTitle"><?php echo htmlspecialchars(t('createUserTitle')); ?></h2>
    <form method="post" action="<?php echo route_to('usuarios_crear'); ?>">
      <label><?php echo htmlspecialchars(t('fullName')); ?></label>
      <input name="name" required placeholder="<?php echo htmlspecialchars(t('fullName')); ?>">
      
      <label><?php echo htmlspecialchars(t('email')); ?></label>
      <input type="email" name="email" required placeholder="<?php echo htmlspecialchars(t('emailPlaceholder')); ?>">
      
      <label><?php echo htmlspecialchars(t('roleLabel')); ?></label>
      <select name="role">
        <option value="residente"><?php echo htmlspecialchars(t('resident')); ?></option>
        <option value="supervisor"><?php echo htmlspecialchars(t('supervisor')); ?></option>
        <option value="admin"><?php echo htmlspecialchars(t('adminRole')); ?></option>
      </select>
      
      <label><?php echo htmlspecialchars(t('password')); ?></label>
      <input type="password" name="password" required placeholder="<?php echo htmlspecialchars(t('passwordPlaceholder')); ?>">
      
      <button type="submit"><?php echo htmlspecialchars(t('createUserButton')); ?></button>
    </form>
  </div>
</section>
