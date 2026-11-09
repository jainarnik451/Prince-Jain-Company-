<section class="panel narrow-panel">
  <h2>Change password</h2>
  <p class="muted">Signed in as <?= e($user['email']) ?>.</p>
  <form method="post" action="<?= e(url('admin/account')) ?>" class="form">
    <?= csrf_field() ?>
    <div class="field"><label for="current">Current password</label><input id="current" name="current" type="password" required autocomplete="current-password"></div>
    <div class="field"><label for="new">New password</label><input id="new" name="new" type="password" required minlength="10" autocomplete="new-password"><p class="help">At least 10 characters with letters and numbers.</p></div>
    <div class="field"><label for="new2">Confirm new password</label><input id="new2" name="new2" type="password" required minlength="10" autocomplete="new-password"></div>
    <button class="btn btn-primary" type="submit">Update password</button>
  </form>
</section>
