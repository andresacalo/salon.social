<section class="grid">
  <div class="card">
    <h2 data-i18n="newReservationTitle"><?php echo htmlspecialchars(t('newReservationTitle')); ?></h2>
    
    <?php if (isset($_SESSION['flash']) && $_SESSION['flash']['type'] === 'error'): ?>
      <div style="padding: 12px 16px; margin-bottom: 20px; border-radius: 6px; background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; font-weight: 500;">
        <strong><?php echo htmlspecialchars(t('error')); ?></strong> <?php echo htmlspecialchars($_SESSION['flash']['message']); ?>
      </div>
      <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
    
    <form method="post" action="<?php echo route_to('reservas_crear'); ?>">
      <label><?php echo htmlspecialchars(t('eventDate')); ?></label>
      <input type="date" name="fecha_evento" value="<?php echo htmlspecialchars($_SESSION['form_data']['fecha_evento'] ?? ''); ?>" required>
      
      <label><?php echo htmlspecialchars(t('startTime')); ?></label>
      <input type="time" name="hora_inicio" value="<?php echo htmlspecialchars($_SESSION['form_data']['hora_inicio'] ?? ''); ?>" required>
      
      <label><?php echo htmlspecialchars(t('endTime')); ?></label>
      <input type="time" name="hora_fin" value="<?php echo htmlspecialchars($_SESSION['form_data']['hora_fin'] ?? ''); ?>" required>
      
      <label><?php echo htmlspecialchars(t('eventTitle')); ?></label>
      <input name="titulo" value="<?php echo htmlspecialchars($_SESSION['form_data']['titulo'] ?? ''); ?>" required placeholder="<?php echo htmlspecialchars(t('eventTitle')); ?>">
      
      <label><?php echo htmlspecialchars(t('attendees')); ?></label>
      <input type="number" name="asistentes" min="0" value="<?php echo htmlspecialchars($_SESSION['form_data']['asistentes'] ?? 0); ?>" placeholder="<?php echo htmlspecialchars(t('attendees')); ?>">
      
      <label><?php echo htmlspecialchars(t('email')); ?></label>
      <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['form_data']['email'] ?? ''); ?>" required placeholder="<?php echo htmlspecialchars(t('emailPlaceholder')); ?>">
      
      <label><?php echo htmlspecialchars(t('phone')); ?></label>
      <input type="tel" name="phone" value="<?php echo htmlspecialchars($_SESSION['form_data']['phone'] ?? ''); ?>" required placeholder="<?php echo htmlspecialchars(t('phone')); ?>">
      
      <label><?php echo htmlspecialchars(t('notes')); ?></label>
      <textarea name="notas" placeholder="<?php echo htmlspecialchars(t('notes')); ?>"><?php echo htmlspecialchars($_SESSION['form_data']['notas'] ?? ''); ?></textarea>
      
      <button type="submit"><?php echo htmlspecialchars(t('bookButton')); ?></button>
    </form>
  </div>
  <div class="card">
    <h2 data-i18n="myReservationsTitle"><?php echo htmlspecialchars(t('myReservationsTitle')); ?></h2>
    <table class="table">
      <tr>
        <th data-i18n="date"><?php echo htmlspecialchars(t('date')); ?></th>
        <th data-i18n="startTime"><?php echo htmlspecialchars(t('startTime')); ?></th>
        <th data-i18n="endTime"><?php echo htmlspecialchars(t('endTime')); ?></th>
        <th data-i18n="eventTitle"><?php echo htmlspecialchars(t('eventTitle')); ?></th>
        <th data-i18n="status"><?php echo htmlspecialchars(t('status')); ?></th>
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
