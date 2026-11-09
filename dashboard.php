<?php
$tiles = [
    ['Total enquiries', $c['enq_total'], 'admin/enquiries', 'mail'],
    ['New (unread) enquiries', $c['enq_new'], 'admin/enquiries', 'inbox'],
    ['Appointment requests', $c['appt_total'], 'admin/appointments', 'calendar'],
    ['Pending appointments', $c['appt_pending'], 'admin/appointments?status=pending', 'clock'],
    ['Total articles', $c['art_total'], 'admin/articles', 'newspaper'],
    ['Published articles', $c['art_pub'], 'admin/articles', 'check'],
    ['Feedback submissions', $c['fb_total'], 'admin/feedback', 'star'],
    ['Feedback awaiting review', $c['fb_pending'], 'admin/feedback?status=pending', 'message'],
];
?>
<div class="tiles">
  <?php foreach ($tiles as [$l, $n, $href, $ic]): if (in_array($href, ['admin/articles'], true) && !can('blog')) { continue; } ?>
    <a class="tile" href="<?= e(url($href)) ?>"><?= icon($ic, 'icon', 22) ?><strong><?= (int)$n ?></strong><span><?= e($l) ?></span></a>
  <?php endforeach; ?>
</div>
<div class="two-col">
  <section class="panel">
    <h2>Recent appointment requests</h2>
    <?php if ($recentAppt): ?>
      <table class="table"><thead><tr><th>Ref</th><th>Name</th><th>Preferred</th><th>Status</th></tr></thead><tbody>
        <?php foreach ($recentAppt as $r): ?><tr>
          <td><a href="<?= e(url('admin/appointments/' . $r['id'])) ?>"><?= e($r['ref']) ?></a></td><td><?= e($r['name']) ?></td>
          <td><?= e(fmt_date($r['pref_date'])) ?> <?= e(time_label($r['pref_time'])) ?></td><td><span class="pill s-<?= e($r['status']) ?>"><?= e(appt_statuses()[$r['status']] ?? $r['status']) ?></span></td></tr><?php endforeach; ?>
      </tbody></table>
    <?php else: ?><p class="muted">No appointment requests yet.</p><?php endif; ?>
  </section>
  <section class="panel">
    <h2>Recent enquiries</h2>
    <?php if ($recentEnq): ?>
      <table class="table"><thead><tr><th>Name</th><th>Subject</th><th>Received</th></tr></thead><tbody>
        <?php foreach ($recentEnq as $r): ?><tr class="<?= $r['is_read'] ? '' : 'unread' ?>">
          <td><?= e($r['name']) ?></td><td><a href="<?= e(url('admin/enquiries/' . $r['id'])) ?>"><?= e(str_limit($r['subject'], 40)) ?></a></td><td><?= e(fmt_date($r['created_at'])) ?></td></tr><?php endforeach; ?>
      </tbody></table>
    <?php else: ?><p class="muted">No enquiries yet.</p><?php endif; ?>
  </section>
</div>
<section class="panel">
  <h2>Before you launch</h2>
  <ul class="todo">
    <li><a href="<?= e(url('admin/legal')) ?>">Review and publish the four legal pages</a> (templates contain <code><?= e(REVIEW_MARKER) ?></code> markers that block publication).</li>
    <li><a href="<?= e(url('admin/services')) ?>">Review the service list</a>. Services in regulated areas are saved as drafts until you confirm they fall within your authorised scope.</li>
    <li><a href="<?= e(url('admin/settings/site')) ?>">Add verified details</a> such as ICAI membership number, qualifications and tagline (optional).</li>
    <li><a href="<?= e(url('admin/settings/branding')) ?>">Upload a logo</a> when ready, and review the profile photograph.</li>
  </ul>
</section>
