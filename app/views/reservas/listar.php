<section class="grid">
  <div class="card">
    <h2>📅 Nueva Reserva</h2>
    
    <?php if (isset($_SESSION['flash']) && $_SESSION['flash']['type'] === 'error'): ?>
      <div style="padding: 12px 16px; margin-bottom: 20px; border-radius: 6px; background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; font-weight: 500;">
        <strong>⚠️ Error:</strong> <?php echo htmlspecialchars($_SESSION['flash']['message']); ?>
      </div>
      <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
    
    <form method="post" action="<?php echo route_to('reservas_crear'); ?>">
      <label>📆 Fecha del Evento</label>
      <input type="date" name="fecha_evento" value="<?php echo htmlspecialchars($_SESSION['form_data']['fecha_evento'] ?? ''); ?>" required>
      
      <label>🕐 Hora de Inicio</label>
      <input type="time" name="hora_inicio" value="<?php echo htmlspecialchars($_SESSION['form_data']['hora_inicio'] ?? ''); ?>" required>
      
      <label>🕑 Hora de Fin</label>
      <input type="time" name="hora_fin" value="<?php echo htmlspecialchars($_SESSION['form_data']['hora_fin'] ?? ''); ?>" required>
      
      <label>🎯 Título del Evento</label>
      <input name="titulo" value="<?php echo htmlspecialchars($_SESSION['form_data']['titulo'] ?? ''); ?>" required placeholder="Ej: Cumpleaños, Reunión, etc.">
      
      <label>👥 Número de Asistentes</label>
      <input type="number" name="asistentes" min="0" value="<?php echo htmlspecialchars($_SESSION['form_data']['asistentes'] ?? 0); ?>" placeholder="Cantidad de personas">
      
      <label>📧 Correo Electrónico</label>
      <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['form_data']['email'] ?? ''); ?>" required placeholder="tu@correo.com">
      
      <label>📱 Teléfono/Celular</label>
      <input type="tel" name="phone" value="<?php echo htmlspecialchars($_SESSION['form_data']['phone'] ?? ''); ?>" required placeholder="Ej: +1234567890 o 123-456-7890">
      
      <label>📝 Notas Adicionales</label>
      <textarea name="notas" placeholder="Detalles especiales, requerimientos, etc."><?php echo htmlspecialchars($_SESSION['form_data']['notas'] ?? ''); ?></textarea>
      
      <button type="submit">✅ Reservar</button>
    </form>
  </div>
  <div class="card">
    <h2>📋 Mis Reservas</h2>
    <table class="table">
      <tr>
        <th>📆 Fecha</th>
        <th>🕐 Inicio</th>
        <th>🕑 Fin</th>
        <th>🎯 Título</th>
        <th>✅ Estado</th>
      </tr>
      <?php foreach ($reservas as $r): ?>
      <tr>
        <td><strong><?php echo date('d/m/Y', strtotime($r['fecha_evento'])); ?></strong></td>
        <td><?php echo $r['hora_inicio']; ?></td>
        <td><?php echo $r['hora_fin']; ?></td>
        <td><?php echo htmlspecialchars($r['titulo']); ?></td>
        <td>
          <span class="badge <?php 
            echo $r['estado']==='aprobada'?'success':($r['estado']==='pendiente'?'warn':'danger'); 
          ?>">
            <?php 
              $estado_icon = $r['estado']==='aprobada' ? '✅' : ($r['estado']==='pendiente' ? '⏳' : '❌');
              echo $estado_icon . ' ' . ucfirst($r['estado']); 
            ?>
          </span>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</section>
