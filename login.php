<div class="auth-card">
  <div class="auth-logo"><span class="logo-mark"><?= e(setting('logo_mark', 'PJ')) ?></span></div>
  <h1>Admin sign in</h1>
  <p class="muted"><?= e(setting('firm_name')) ?></p>
  <form method="post" action="<?= e(url('admin/login')) ?>" class="form">
    <?= csrf_field() ?>
    <div class="field"><label for="email">Email</label><input id="email" name="email" type="email" required autocomplete="username" autofocus></div>
    <div class="field"><label for="password">Password</label><input id="password" name="password" type="password" required autocomplete="current-password"></div>
    <button class="btn btn-primary" type="submit">Sign in</button>
  </form>
  <p class="fine"><a href="<?= e(url('')) ?>">&larr; Back to website</a></p>
</div>
