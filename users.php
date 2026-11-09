<div class="panel flush"><div class="table-wrap"><table class="table">
  <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Last sign-in</th><th>Actions</th></tr></thead><tbody>
  <?php foreach ($rows as $r): $self = (int)$r['id'] === (int)$user['id']; ?>
    <tr><td><?= e($r['name']) ?><?= $self ? ' <small>(you)</small>' : '' ?></td><td><?= e($r['email']) ?></td><td><?= e($r['role']) ?></td>
      <td><span class="pill s-<?= $r['active'] ? 'approved' : 'rejected' ?>"><?= $r['active'] ? 'Active' : 'Disabled' ?></span></td>
      <td><?= e(fmt_date($r['last_login'], true) ?: 'Never') ?></td>
      <td class="actions">
        <?php if (!$self): ?>
          <form method="post" action="<?= e(url('admin/users')) ?>"><?= csrf_field() ?><input type="hidden" name="uid" value="<?= (int)$r['id'] ?>"><input type="hidden" name="action" value="<?= $r['active'] ? 'deactivate' : 'activate' ?>"><button class="btn btn-sm btn-outline" type="submit"><?= $r['active'] ? 'Disable' : 'Enable' ?></button></form>
          <form method="post" action="<?= e(url('admin/users')) ?>" data-confirm="Delete this user?"><?= csrf_field() ?><input type="hidden" name="uid" value="<?= (int)$r['id'] ?>"><input type="hidden" name="action" value="delete"><button class="btn btn-sm btn-danger-outline" type="submit">Delete</button></form>
        <?php endif; ?>
      </td></tr>
  <?php endforeach; ?></tbody></table></div></div>
<section class="panel narrow-panel">
  <h2>Add a user</h2>
  <p class="muted"><strong>Staff</strong> can manage appointments, enquiries and feedback only. <strong>Admin</strong> can manage everything.</p>
  <form method="post" action="<?= e(url('admin/users')) ?>" class="form" autocomplete="off">
    <?= csrf_field() ?><input type="hidden" name="action" value="create">
    <div class="field"><label for="u-name">Name</label><input id="u-name" name="name" required maxlength="120"></div>
    <div class="field"><label for="u-email">Email</label><input id="u-email" name="email" type="email" required maxlength="190"></div>
    <div class="field"><label for="u-role">Role</label><select id="u-role" name="role"><option value="staff">Staff</option><option value="admin">Admin</option></select></div>
    <div class="field"><label for="u-pw">Temporary password</label><input id="u-pw" name="password" type="password" required minlength="10" autocomplete="new-password"></div>
    <button class="btn btn-primary" type="submit">Create user</button>
  </form>
</section>
