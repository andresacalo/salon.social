<section class="grid">
    <div class="card">
        <h2>📊 Estado de Reservas</h2>
        <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(140px,1fr));">
            <div style="text-align: center;">
                <div class="badge success">✅ Aprobadas</div>
                <h3 style="margin: 12px 0; font-size: 2.2rem; color: #4ade80;"><?php echo $stats['aprobadas']; ?></h3>
            </div>
            <div style="text-align: center;">
                <div class="badge warn">⏳ Pendientes</div>
                <h3 style="margin: 12px 0; font-size: 2.2rem; color: #facc15;"><?php echo $stats['pendientes']; ?></h3>
            </div>
            <div style="text-align: center;">
                <div class="badge danger">❌ Rechazadas</div>
                <h3 style="margin: 12px 0; font-size: 2.2rem; color: #f87171;"><?php echo $stats['rechazadas']; ?></h3>
            </div>
        </div>
    </div>
    <div class="card">
        <h2>🚨 Próximas Reservas (Semáforo)</h2>
        <?php if (empty($proximas)): ?>
            <p class="muted">😴 Nada próximo por el momento.</p>
        <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>📆 Fecha</th>
                    <th>👤 Solicitante</th>
                    <th>✅ Estado</th>
                    <th>⏱️ Semáforo</th>
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
