<section class="grid" style="grid-column: 1/-1;">
  <div class="card" style="max-width: 1200px; margin: 0 auto;">
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px; border-bottom: 3px solid #f0f0f0; padding-bottom: 20px;">
      <h1 style="margin: 0; font-size: 32px;" data-i18n="notificationsCenterTitle"><?php echo htmlspecialchars(t('notificationsCenterTitle')); ?></h1>
      <span class="badge success" style="padding: 8px 15px; font-size: 14px; background: #22c55e; color: white;">
        <?php echo count($reservas); ?> <?php echo htmlspecialchars(t('reservationsCount')); ?>
      </span>
    </div>
    
    <?php if (isset($_SESSION['flash'])): ?>
      <div style="padding: 15px 20px; margin-bottom: 25px; border-radius: 8px; background: <?php echo $_SESSION['flash']['type']==='success' ? '#d1fae5' : '#fee2e2'; ?>; color: <?php echo $_SESSION['flash']['type']==='success' ? '#065f46' : '#991b1b'; ?>; border-left: 4px solid <?php echo $_SESSION['flash']['type']==='success' ? '#22c55e' : '#ef4444'; ?>;">
        <?php echo htmlspecialchars($_SESSION['flash']['message']); ?>
      </div>
      <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
    
    <p style="color: #666; margin-bottom: 30px; font-size: 15px; line-height: 1.6;" data-i18n="helpText">
      <?php echo htmlspecialchars(t('helpText')); ?>
    </p>
    
    <?php if (empty($reservas)): ?>
      <div style="text-align: center; padding: 60px 20px;">
        <div style="font-size: 48px; margin-bottom: 20px;">📭</div>
        <p style="color: #999; font-size: 16px;"><?php echo htmlspecialchars(t('noNotifications')); ?></p>
      </div>
    <?php else: ?>
      <div style="display: grid; gap: 20px; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));">
        <?php foreach ($reservas as $r): ?>
        <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: all 0.3s ease;">
          
          <!-- Encabezado de la tarjeta -->
          <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
            <div>
              <div style="font-size: 14px; color: #999; margin-bottom: 4px;">📅 <?php echo date('d/m/Y - H:i', strtotime($r['fecha_evento'] . ' ' . $r['hora_inicio'])); ?></div>
              <div style="font-size: 16px; font-weight: 600; color: #1f2937;">
                🎯 <?php echo htmlspecialchars($r['titulo']); ?>
              </div>
            </div>
            <span class="badge" style="padding: 6px 12px; font-size: 12px; background: <?php 
              echo $r['estado']==='aprobada' ? '#d1fae5; color: #065f46;' : 
                   ($r['estado']==='pendiente' ? '#fef3c7; color: #92400e;' : '#fee2e2; color: #991b1b;'); 
            ?>">
              <?php 
                $estado_icon = $r['estado']==='aprobada' ? '✅' : 
                              ($r['estado']==='pendiente' ? '⏳' : '❌');
                echo $estado_icon . ' ' . ucfirst($r['estado']); 
              ?>
            </span>
          </div>
          
          <!-- Información del usuario -->
          <div style="background: white; padding: 12px; border-radius: 8px; margin-bottom: 15px; border-left: 3px solid #3b82f6;">
            <div style="font-weight: 600; color: #1f2937; margin-bottom: 4px;">👤 <?php echo htmlspecialchars($r['name']); ?></div>
            <div style="display: grid; gap: 6px; font-size: 13px; color: #666;">
              <div title="<?php echo htmlspecialchars($r['email']); ?>">
                📧 <span style="font-family: monospace; font-size: 12px; color: #3b82f6;">
                  <?php echo strlen($r['email']) > 25 ? substr($r['email'], 0, 22) . '...' : htmlspecialchars($r['email']); ?>
                </span>
              </div>
              <div>
                📱 <span style="font-family: monospace; font-size: 12px; color: #22d3ee;">
                  <?php echo htmlspecialchars($r['phone'] ?? '—'); ?>
                </span>
              </div>
            </div>
          </div>
          
          <!-- Selector de canal -->
          <div style="margin-bottom: 15px;">
            <label style="display: block; font-size: 12px; font-weight: 600; color: #666; margin-bottom: 6px;" data-i18n="channelSelect"><?php echo htmlspecialchars(t('channelSelect')); ?></label>
            <select id="canal_<?php echo $r['id']; ?>" style="width: 100%; padding: 10px; font-size: 13px; border: 2px solid #e2e8f0; border-radius: 6px; background: white; color: #1f2937; font-weight: 500; cursor: pointer; transition: border 0.3s;">
              <option value="email"><?php echo htmlspecialchars(t('emailOption')); ?></option>
              <option value="whatsapp" <?php echo empty($r['phone']) ? 'disabled' : ''; ?>><?php echo htmlspecialchars(t('whatsappOption')); ?></option>
              <option value="sms" <?php echo empty($r['phone']) ? 'disabled' : ''; ?>><?php echo htmlspecialchars(t('smsOption')); ?></option>
            </select>
          </div>
          
          <!-- Botones de acción -->
          <div style="display: grid; gap: 8px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
              <!-- Confirmación -->
              <form method="post" action="<?php echo route_to('notificaciones_enviar'); ?>" style="display: contents;" onsubmit="this.querySelector('[name=canal]').value = document.getElementById('canal_<?php echo $r['id']; ?>').value;">
                <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                <input type="hidden" name="tipo" value="confirmacion">
                <input type="hidden" name="canal" value="email">
                <button type="submit" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 10px 12px; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 6px;">
                  <?php echo htmlspecialchars(t('confirmAction')); ?>
                </button>
              </form>
              
              <!-- Aprobación -->
              <form method="post" action="<?php echo route_to('notificaciones_enviar'); ?>" style="display: contents;" onsubmit="this.querySelector('[name=canal]').value = document.getElementById('canal_<?php echo $r['id']; ?>').value;">
                <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                <input type="hidden" name="tipo" value="aprobacion">
                <input type="hidden" name="canal" value="email">
                <button type="submit" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color: white; padding: 10px 12px; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 6px;">
                  <?php echo htmlspecialchars(t('approvalAction')); ?>
                </button>
              </form>
            </div>
            
            <!-- Rechazo (ancho completo) -->
            <form method="post" action="<?php echo route_to('notificaciones_enviar'); ?>" style="display: contents;" onsubmit="this.querySelector('[name=canal]').value = document.getElementById('canal_<?php echo $r['id']; ?>').value;">
              <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
              <input type="hidden" name="tipo" value="rechazo">
              <input type="hidden" name="canal" value="email">
              <button type="submit" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 10px 12px; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s; width: 100%;">
                <?php echo htmlspecialchars(t('rejectionAction')); ?>
              </button>
            </form>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<style>
  select {
    transition: all 0.3s;
  }
  
  select:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none;
  }
  
  button:hover {
    opacity: 0.9;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
  }
</style>
