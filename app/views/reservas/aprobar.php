<section class="grid">
  <div class="card">
    <h2>✅ Aprobar Reservas</h2>
    <a href="<?php echo route_to('reporte_csv'); ?>" class="badge muted" style="display: inline-block; margin-bottom: 16px; cursor: pointer;">📊 Descargar Reporte CSV</a>
    <table class="table">
      <tr>
        <th>📆 Fecha</th>
        <th>🕐 Horario</th>
        <th>👤 Usuario</th>
        <th>🎯 Título</th>
        <th>✅ Estado</th>
        <th>⚙️ Acciones</th>
      </tr>
      <?php foreach ($reservas as $r): ?>
      <tr>
        <td><strong><?php echo date('d/m/Y', strtotime($r['fecha_evento'])); ?></strong></td>
        <td><?php echo $r['hora_inicio'] . ' - ' . $r['hora_fin']; ?></td>
        <td><?php echo htmlspecialchars($r['user_name']); ?></td>
        <td><?php echo htmlspecialchars($r['titulo']); ?></td>
        <td>
          <span class="badge <?php echo $r['estado']==='aprobada'?'success':($r['estado']==='pendiente'?'warn':'danger'); ?>">
            <?php 
              $estado_icon = $r['estado']==='aprobada' ? '✅' : ($r['estado']==='pendiente' ? '⏳' : '❌');
              echo $estado_icon . ' ' . ucfirst($r['estado']); 
            ?>
          </span>
        </td>
        <td style="min-width: 200px;">
            <div style="display: flex; gap: 8px;">
                <form method="post" action="<?php echo route_to('reservas_estado'); ?>" style="flex: 1;">
                    <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                    <input type="hidden" name="status" value="aprobada">
                    <button type="submit" class="action-btn">✅ Aprobar</button>
                </form>
                <form method="post" action="<?php echo route_to('reservas_estado'); ?>" style="flex: 1;">
                    <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                    <input type="hidden" name="status" value="rechazada">
                    <button type="submit" class="action-btn" style="background: linear-gradient(120deg, #ef4444, #dc2626); color: white;">❌ Rechazar</button>
                </form>
            </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</section>
