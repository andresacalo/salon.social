<section class="grid">
  <div class="card">
    <h2 data-i18n="inventoryTitle"><?php echo htmlspecialchars(t('inventoryTitle')); ?></h2>
    <table class="table">
      <tr><th data-i18n="item"><?php echo htmlspecialchars(t('item')); ?></th><th data-i18n="unitLabel"><?php echo htmlspecialchars(t('unitLabel')); ?></th><th data-i18n="notesLabel"><?php echo htmlspecialchars(t('notesLabel')); ?></th></tr>
      <?php foreach ($items as $i): ?>
      <tr>
        <td><?php echo htmlspecialchars($i['nombre']); ?></td>
        <td><?php echo htmlspecialchars($i['unidad']); ?></td>
        <td class="muted"><?php echo htmlspecialchars($i['notas'] ?? ''); ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
  <div class="card">
    <h2 data-i18n="createItemTitle"><?php echo htmlspecialchars(t('createItemTitle')); ?></h2>
    <form method="post" action="<?php echo route_to('inventario_crear'); ?>">
      <label><?php echo htmlspecialchars(t('nameLabel')); ?></label>
      <input name="name" required>
      <label><?php echo htmlspecialchars(t('unitLabel')); ?></label>
      <input name="unit" value="unidad">
      <label><?php echo htmlspecialchars(t('notesLabel')); ?></label>
      <textarea name="notes"></textarea>
      <button type="submit" style="margin-top:10px;"><?php echo htmlspecialchars(t('saveButton')); ?></button>
    </form>
  </div>
  <div class="card">
    <h2 data-i18n="movementTitle"><?php echo htmlspecialchars(t('movementTitle')); ?></h2>
    <form method="post" action="<?php echo route_to('inventario_mov'); ?>">
      <label><?php echo htmlspecialchars(t('itemLabel')); ?></label>
      <select name="item_id">
        <?php foreach ($items as $i): ?>
        <option value="<?php echo $i['id']; ?>"><?php echo htmlspecialchars($i['nombre']); ?></option>
        <?php endforeach; ?>
      </select>
      <label><?php echo htmlspecialchars(t('quantityLabel')); ?></label>
      <input type="number" name="qty" min="1" required>
      <label><?php echo htmlspecialchars(t('typeLabel')); ?></label>
      <select name="kind"><option value="entrada"><?php echo htmlspecialchars(t('entry')); ?></option><option value="salida"><?php echo htmlspecialchars(t('exit')); ?></option></select>
      <label><?php echo htmlspecialchars(t('reasonLabel')); ?></label>
      <input name="reason">
      <button type="submit" style="margin-top:10px;"><?php echo htmlspecialchars(t('registerButton')); ?></button>
    </form>
  </div>
  <div class="card">
    <h2 data-i18n="recentMovementsTitle"><?php echo htmlspecialchars(t('recentMovementsTitle')); ?></h2>
    <table class="table">
      <tr><th data-i18n="date"><?php echo htmlspecialchars(t('date')); ?></th><th data-i18n="item"><?php echo htmlspecialchars(t('item')); ?></th><th data-i18n="qty"><?php echo htmlspecialchars(t('qty')); ?></th><th data-i18n="typeLabel"><?php echo htmlspecialchars(t('typeLabel')); ?></th><th data-i18n="reasonLabel"><?php echo htmlspecialchars(t('reasonLabel')); ?></th><th data-i18n="user"><?php echo htmlspecialchars(t('user')); ?></th></tr>
      <?php foreach ($movs as $m): ?>
      <tr>
        <td class="muted"><?php echo $m['creado_el']; ?></td>
        <td><?php echo htmlspecialchars($m['item_name']); ?></td>
        <td><?php echo $m['cantidad']; ?></td>
        <td><span class="badge <?php echo $m['tipo']==='entrada'?'success':'warn'; ?>"><?php echo htmlspecialchars(t('status_' . $m['tipo'])); ?></span></td>
        <td class="muted"><?php echo htmlspecialchars($m['motivo']); ?></td>
        <td class="muted"><?php echo htmlspecialchars($m['user_name'] ?? ''); ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</section>
