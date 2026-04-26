<section class="grid">
  <div class="card">
    <h2>Inventario</h2>
    <table class="table">
      <tr><th>Item</th><th>Unidad</th><th>Notas</th></tr>
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
    <h2>Crear ítem</h2>
    <form method="post" action="<?php echo route_to('inventario_crear'); ?>">
      <label>Nombre</label>
      <input name="name" required>
      <label>Unidad</label>
      <input name="unit" value="unidad">
      <label>Notas</label>
      <textarea name="notes"></textarea>
      <button type="submit" style="margin-top:10px;">Guardar</button>
    </form>
  </div>
  <div class="card">
    <h2>Movimiento</h2>
    <form method="post" action="<?php echo route_to('inventario_mov'); ?>">
      <label>Ítem</label>
      <select name="item_id">
        <?php foreach ($items as $i): ?>
        <option value="<?php echo $i['id']; ?>"><?php echo htmlspecialchars($i['nombre']); ?></option>
        <?php endforeach; ?>
      </select>
      <label>Cantidad</label>
      <input type="number" name="qty" min="1" required>
      <label>Tipo</label>
      <select name="kind"><option value="entrada">Entrada</option><option value="salida">Salida</option></select>
      <label>Motivo</label>
      <input name="reason">
      <button type="submit" style="margin-top:10px;">Registrar</button>
    </form>
  </div>
  <div class="card">
    <h2>Últimos movimientos</h2>
    <table class="table">
      <tr><th>Fecha</th><th>Ítem</th><th>Cant.</th><th>Tipo</th><th>Motivo</th><th>Usuario</th></tr>
      <?php foreach ($movs as $m): ?>
      <tr>
        <td class="muted"><?php echo $m['creado_el']; ?></td>
        <td><?php echo htmlspecialchars($m['item_name']); ?></td>
        <td><?php echo $m['cantidad']; ?></td>
        <td><span class="badge <?php echo $m['tipo']==='entrada'?'success':'warn'; ?>"><?php echo $m['tipo']; ?></span></td>
        <td class="muted"><?php echo htmlspecialchars($m['motivo']); ?></td>
        <td class="muted"><?php echo htmlspecialchars($m['user_name'] ?? ''); ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</section>
