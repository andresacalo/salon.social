<section class="grid">
  <div class="card">
    <h2 data-i18n="approveReservationsTitle"><?php echo htmlspecialchars(t('approveReservationsTitle')); ?></h2>
    
    <?php if (isset($_SESSION['flash'])): ?>
      <div style="padding: 12px 16px; margin-bottom: 20px; border-radius: 6px; background: <?php echo $_SESSION['flash']['type'] === 'success' ? '#dcfce7' : '#fee2e2'; ?>; color: <?php echo $_SESSION['flash']['type'] === 'success' ? '#166534' : '#991b1b'; ?>; border-left: 4px solid <?php echo $_SESSION['flash']['type'] === 'success' ? '#22c55e' : '#ef4444'; ?>; font-weight: 500;">
        <strong><?php echo htmlspecialchars($_SESSION['flash']['type'] === 'success' ? '✅ Éxito' : '❌ Error'); ?></strong> <?php echo htmlspecialchars($_SESSION['flash']['message']); ?>
      </div>
      <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Status Filter Buttons -->
    <div style="display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
      <a href="<?php echo route_to('reporte_csv'); ?>" class="badge muted" style="display: inline-block; cursor: pointer;" data-i18n="downloadCsv"><?php echo htmlspecialchars(t('downloadCsv')); ?></a>
      <div style="flex: 1; display: flex; gap: 12px; flex-wrap: wrap;">
        <button class="status-btn active" data-status="todos" style="background: #0f172a; border: 2px solid #22d3ee; color: #22d3ee; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
          📋 Todas
        </button>
        <button class="status-btn" data-status="pendiente" style="background: #78350f; border: 2px solid #facc15; color: #facc15; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
          ⏳ Pendiente (<?php echo $stats['pendientes'] ?? 0; ?>)
        </button>
        <button class="status-btn" data-status="aprobada" style="background: #064e3b; border: 2px solid #4ade80; color: #4ade80; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
          ✅ Aprobada (<?php echo $stats['aprobadas'] ?? 0; ?>)
        </button>
        <button class="status-btn" data-status="rechazada" style="background: #7f1d1d; border: 2px solid #f87171; color: #f87171; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
          ❌ Rechazada (<?php echo $stats['rechazadas'] ?? 0; ?>)
        </button>
      </div>
    </div>

    <!-- Reservations Table -->
    <div style="overflow-x: auto;">
      <table class="table" id="reservasTable">
        <tr>
          <th data-i18n="date"><?php echo htmlspecialchars(t('date')); ?></th>
          <th data-i18n="schedule"><?php echo htmlspecialchars(t('schedule')); ?></th>
          <th data-i18n="requester"><?php echo htmlspecialchars(t('requester')); ?></th>
          <th data-i18n="eventTitle"><?php echo htmlspecialchars(t('eventTitle')); ?></th>
          <th data-i18n="status"><?php echo htmlspecialchars(t('status')); ?></th>
          <th data-i18n="actions"><?php echo htmlspecialchars(t('actions')); ?></th>
        </tr>
        <?php foreach ($reservas as $r): ?>
        <tr class="reserva-row" data-estado="<?php echo $r['estado']; ?>">
          <td><strong><?php echo date('d/m/Y', strtotime($r['fecha_evento'])); ?></strong></td>
          <td><?php echo $r['hora_inicio'] . ' - ' . $r['hora_fin']; ?></td>
          <td><?php echo htmlspecialchars($r['user_name']); ?></td>
          <td><?php echo htmlspecialchars($r['titulo']); ?></td>
          <td>
            <select class="estado-select" data-id="<?php echo $r['id']; ?>" style="padding: 6px 10px; border-radius: 4px; border: 1px solid #cbd5e1; background: #1e293b; color: #e2e8f0; cursor: pointer;">
              <option value="pendiente" <?php echo $r['estado'] === 'pendiente' ? 'selected' : ''; ?>>⏳ Pendiente</option>
              <option value="aprobada" <?php echo $r['estado'] === 'aprobada' ? 'selected' : ''; ?>>✅ Aprobada</option>
              <option value="rechazada" <?php echo $r['estado'] === 'rechazada' ? 'selected' : ''; ?>>❌ Rechazada</option>
            </select>
          </td>
          <td style="min-width: 200px;">
            <button class="btn-delete" data-id="<?php echo $r['id']; ?>" style="background: #dc2626; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.3s; hover: background #b91c1c;">
              🗑️ Eliminar
            </button>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
  console.log('Script loaded - status change functionality initializing');
  
  // Filter by status
  document.querySelectorAll('.status-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const status = this.dataset.status;
      const rows = document.querySelectorAll('.reserva-row');
      
      // Update active button style
      document.querySelectorAll('.status-btn').forEach(b => {
        b.classList.remove('active');
        b.style.opacity = '0.6';
      });
      this.classList.add('active');
      this.style.opacity = '1';
      
      // Filter rows
      rows.forEach(row => {
        if (status === 'todos' || row.dataset.estado === status) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  });

  const statusAction = '<?php echo route_to('reservas_estado'); ?>';
  const deleteAction = '<?php echo route_to('reservas_eliminar'); ?>';

  // Change status
  document.querySelectorAll('.estado-select').forEach(select => {
    select.addEventListener('change', function(e) {
      const id = this.dataset.id;
      const newStatus = this.value;
      
      console.log('Estado cambió - ID:', id, 'Nuevo Estado:', newStatus);
      
      // Create form and submit
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = statusAction;
      
      const idInput = document.createElement('input');
      idInput.type = 'hidden';
      idInput.name = 'id';
      idInput.value = id;
      form.appendChild(idInput);
      
      const statusInput = document.createElement('input');
      statusInput.type = 'hidden';
      statusInput.name = 'status';
      statusInput.value = newStatus;
      form.appendChild(statusInput);
      
      console.log('Enviando formulario a:', form.action);
      document.body.appendChild(form);
      form.submit();
    });
  });

  // Delete reservation
  document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.dataset.id;
      console.log('Eliminar ID:', id);
      
      if (confirm('¿Estás seguro de que deseas eliminar esta reserva?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteAction;
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = id;
        form.appendChild(idInput);
        
        console.log('Enviando eliminar a:', form.action);
        document.body.appendChild(form);
        form.submit();
      }
    });
  });
});
</script>
