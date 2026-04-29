<section class="grid" style="max-width:400px;margin:80px auto;">
  <div class="card">
    <h2 style="text-align:center; color:#22d3ee;" data-i18n="welcome"><?php echo htmlspecialchars(t('welcome')); ?></h2>
    <p style="text-align:center; color:#94a3b8; margin-bottom:24px;" data-i18n="loginSubtitle"><?php echo htmlspecialchars(t('loginSubtitle')); ?></p>
    <form method="post" action="<?php echo route_to('login'); ?>">
      <label data-i18n="email"><?php echo htmlspecialchars(t('email')); ?></label>
      <input type="email" name="email" required placeholder="<?php echo htmlspecialchars(t('emailPlaceholder')); ?>" data-i18n-placeholder="emailPlaceholder">
      
      <label data-i18n="password"><?php echo htmlspecialchars(t('password')); ?></label>
      <input type="password" name="password" required placeholder="<?php echo htmlspecialchars(t('passwordPlaceholder')); ?>" data-i18n-placeholder="passwordPlaceholder">
      
      <button type="submit" style="width:100%;" data-i18n="submitLogin"><?php echo htmlspecialchars(t('submitLogin')); ?></button>
    </form>
    <p style="text-align:center; color:#94a3b8; font-size:0.9rem; margin-top:16px;" data-i18n="siteCopyright">
      <?php echo htmlspecialchars(t('siteCopyright')); ?>
    </p>
  </div>
</section>
