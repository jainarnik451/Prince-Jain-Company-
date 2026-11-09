<div class="filters">
  <a href="<?= e(url('admin/enquiries')) ?>" class="<?= $status === '' ? 'on' : '' ?>">All</a>
  <?php foreach (enq_statuses() as $k => $l): ?><a href="<?= e(url('admin/enquiries?status=' . $k)) ?>" class="<?= $status === $k ? 'on' : '' ?>"><?= e($l) ?></a><?php endforeach; ?>
</div>
<?php if (!$rows): ?>
  <div class="panel"><p class="muted">No enquiries<?= $status ? ' with this status' : ' yet' ?>.</p></div>
<?php else: ?>
<div class="panel flush"><div class="table-wrap"><table class="table">
  <thead><tr><th>Name</th><th>Subject</th><th>Service</th><th>Contact</th><th>Status</th><th>Received</th></tr></thead>
  <tbody>
  <?php foreach ($rows as $r): ?>
    <tr class="<?= $r['is_read'] ? '' : 'unread' ?>">
      <td><?= e($r['name']) ?></td>
      <td><a href="<?= e(url('admin/enquiries/' . $r['id'])) ?>"><?= e(str_limit($r['subject'], 60)) ?></a><?= $r['is_read'] ? '' : ' <span class="pill s-new">New</span>' ?></td>
      <td><?= e($r['service']) ?></td>
      <td><?= e($r['email']) ?><?= $r['phone'] ? '<br><small>' . e($r['phone']) . '</small>' : '' ?></td>
      <td><span class="pill s-<?= e($r['status']) ?>"><?= e(enq_statuses()[$r['status']] ?? $r['status']) ?></span></td>
      <td><?= e(fmt_date($r['created_at'], true)) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody></table></div></div>
<?php endif; ?>
