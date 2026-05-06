<section class="grid">
    <div class="card">
        <h2 data-i18n="dashboardStatsTitle"><?php echo htmlspecialchars(t('dashboardStatsTitle')); ?></h2>
        <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(140px,1fr));">
            <div style="text-align: center; cursor: pointer;" class="stat-card" data-status="aprobada">
                <div class="badge success">✅ <?php echo htmlspecialchars(t('status_aprobada')); ?></div>
                <h3 style="margin: 12px 0; font-size: 2.2rem; color: #4ade80;"><?php echo $stats['aprobadas']; ?></h3>
            </div>
            <div style="text-align: center; cursor: pointer;" class="stat-card" data-status="pendiente">
                <div class="badge warn">⏳ <?php echo htmlspecialchars(t('status_pendiente')); ?></div>
                <h3 style="margin: 12px 0; font-size: 2.2rem; color: #facc15;"><?php echo $stats['pendientes']; ?></h3>
            </div>
            <div style="text-align: center; cursor: pointer;" class="stat-card" data-status="rechazada">
                <div class="badge danger">❌ <?php echo htmlspecialchars(t('status_rechazada')); ?></div>
                <h3 style="margin: 12px 0; font-size: 2.2rem; color: #f87171;"><?php echo $stats['rechazadas']; ?></h3>
            </div>
        </div>
    </div>
    <div class="card">
        <h2 data-i18n="upcomingReservationsTitle"><?php echo htmlspecialchars(t('upcomingReservationsTitle')); ?></h2>
        <?php if (empty($proximas)): ?>
            <p class="muted" data-i18n="noUpcoming"><?php echo htmlspecialchars(t('noUpcoming')); ?></p>
        <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th data-i18n="date"><?php echo htmlspecialchars(t('date')); ?></th>
                    <th data-i18n="requester"><?php echo htmlspecialchars(t('requester')); ?></th>
                    <th data-i18n="status"><?php echo htmlspecialchars(t('status')); ?></th>
                    <th data-i18n="semaphore"><?php echo htmlspecialchars(t('semaphore')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($proximas as $r):
                    $dias = (new DateTime())->diff(new DateTime($r['fecha_evento']))->days;
                    $color = $dias > 7 ? 'verde' : ($dias >=3 ? 'amarillo' : 'rojo');
                ?>
                <tr>
                    <td><strong><?php echo date('d/m/Y', strtotime($r['fecha_evento'])); ?></strong></td>
                    <td><?php echo htmlspecialchars($r['user_name']); ?></td>
                    <td><?php echo ucfirst(htmlspecialchars($r['estado'])); ?></td>
                    <td>
                        <span class="semaforo <?php echo $color; ?>">
                            <?php echo $color === 'verde' ? '🟢' : ($color === 'amarillo' ? '🟡' : '🔴'); ?>
                            <?php echo $dias; ?> días
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Make stat cards clickable if user is admin
    const statCards = document.querySelectorAll('.stat-card');
    const isAdmin = <?php echo isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin' ? 'true' : 'false'; ?>;
    
    if (isAdmin) {
        statCards.forEach(card => {
            card.style.transition = 'all 0.3s ease';
            card.style.padding = '16px';
            card.style.borderRadius = '8px';
            
            card.addEventListener('mouseenter', function() {
                this.style.background = 'rgba(34, 211, 238, 0.1)';
                this.style.transform = 'scale(1.05)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.background = 'transparent';
                this.style.transform = 'scale(1)';
            });
            
            card.addEventListener('click', function() {
                window.location.href = '<?php echo route_to('admin_reservas'); ?>#' + this.dataset.status;
            });
        });
    }
});
</script>
