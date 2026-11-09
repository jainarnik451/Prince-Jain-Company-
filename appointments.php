<div class="filters">
  <a href="<?= e(url('admin/appointments')) ?>" class="<?= $status === '' ? 'on' : '' ?>">All</a>
  <?php foreach (appt_statuses() as $k => $l): ?><a href="<?= e(url('admin/appointments?status=' . $k)) ?>" class="<?= $status === $k ? 'on' : '' ?>"><?= e($l) ?></a><?php endforeach; ?>
</div>
<?php if (!$rows): ?>
  <div class="panel"><p class="muted">No appointment requests<?= $status ? ' with this status' : ' yet' ?>.</p></div>
<?php else: ?>
<div class="panel flush"><div class="table-wrap"><table class="table">
  <thead><tr><th>Ref</th><th>Name</th><th>Contact</th><th>Service</th><th>Preferred</th><th>Type</th><th>Status</th><th>Received</th></tr></thead>
  <tbody>
  <?php foreach ($rows as $r): ?>
    <tr class="<?= $r['is_read'] ? '' : 'unread' ?>">
      <td><a href="<?= e(url('admin/appointments/' . $r['id'])) ?>"><?= e($r['ref']) ?></a></td>
      <td><?= e($r['name']) ?></td>
      <td><?= e($r['phone']) ?><br><small><?= e($r['email']) ?></small></td>
      <td><?= e($r['service']) ?></td>
      <td><?= e(fmt_date($r['pref_date'])) ?><br><small><?= e(time_label($r['pref_time'])) ?></small></td>
      <td><?= e($r['mode']) ?></td>
      <td><span class="pill s-<?= e($r['status']) ?>"><?= e(appt_statuses()[$r['status']] ?? $r['status']) ?></span></td>
      <td><?= e(fmt_date($r['created_at'], true)) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody></table></div></div>
<?php endif; ?>
