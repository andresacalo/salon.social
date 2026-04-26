<section class="grid" style="max-width:400px;margin:80px auto;">
  <div class="card">
    <h2 style="text-align:center; color:#22d3ee;">🏰 Bienvenido</h2>
    <p style="text-align:center; color:#94a3b8; margin-bottom:24px;">Accede al Sistema de Reservas</p>
    <form method="post" action="<?php echo route_to('login'); ?>">
      <label>📧 Correo Electrónico</label>
      <input type="email" name="email" required placeholder="tu@correo.com">
      
      <label>🔐 Contraseña</label>
      <input type="password" name="password" required placeholder="Tu contraseña">
      
      <button type="submit" style="width:100%;">🔓 Entrar</button>
    </form>
    <p style="text-align:center; color:#94a3b8; font-size:0.9rem; margin-top:16px;">
      Sistema Salón Social © 2024
    </p>
  </div>
</section>
