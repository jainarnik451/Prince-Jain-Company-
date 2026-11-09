<div class="auth-card">
  <div class="auth-logo"><span class="logo-mark">PJ</span></div>
  <h1>Website setup</h1>

  <?php if ($installed): ?>
    <p>The website has already been set up. This page is now disabled.</p>
    <p><a class="btn btn-primary" href="<?= e(url('admin/login')) ?>">Go to admin sign-in</a></p>

  <?php elseif (!$dbOk): ?>
    <div class="alert alert-error">The database could not be reached. Check the <code>DB_*</code> values in your <code>.env</code> file and make sure the database and user exist, then reload this page.</div>
    <p class="muted">Details are written to <code>storage/logs/app.log</code> only when logging is enabled.</p>

  <?php elseif (!$hasKey): ?>
    <div class="alert alert-error">Setup is locked. Add a long random <code>INSTALL_KEY</code> to your <code>.env</code> file, then reload this page.</div>

  <?php else: ?>
    <p class="muted">Creates the database tables, loads the starter content and creates the first administrator account. The setup key is the <code>INSTALL_KEY</code> value from your <code>.env</code> file.</p>
    <form method="post" action="<?= e(url('install')) ?>" class="form" autocomplete="off">
      <?= csrf_field() ?>
      <div class="field"><label for="install_key">Setup key</label><input id="install_key" name="install_key" type="password" required></div>
      <div class="field"><label for="name">Administrator name</label><input id="name" name="name" required maxlength="120" value="<?= e($_POST['name'] ?? '') ?>"></div>
      <div class="field"><label for="email">Administrator email</label><input id="email" name="email" type="email" required maxlength="190"></div>
      <div class="field"><label for="password">Password</label><input id="password" name="password" type="password" required minlength="10" autocomplete="new-password"><p class="help">At least 10 characters with letters and numbers.</p></div>
      <div class="field"><label for="password2">Confirm password</label><input id="password2" name="password2" type="password" required minlength="10" autocomplete="new-password"></div>
      <button class="btn btn-primary" type="submit">Install website</button>
    </form>
  <?php endif; ?>
</div>
