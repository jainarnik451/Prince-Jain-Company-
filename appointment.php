<p><a href="<?= e(url('admin/appointments')) ?>">&larr; All appointments</a></p>
<div class="two-col wide-left">
  <section class="panel">
    <h2><?= e($r['ref']) ?> <span class="pill s-<?= e($r['status']) ?>"><?= e(appt_statuses()[$r['status']] ?? $r['status']) ?></span></h2>
    <dl class="details">
      <div><dt>Name</dt><dd><?= e($r['name']) ?></dd></div>
      <div><dt>Phone</dt><dd><a href="<?= e(tel_link($r['phone'])) ?>"><?= e($r['phone']) ?></a> · <a href="<?= e(wa_link($r['phone'])) ?>" target="_blank" rel="noopener">WhatsApp</a></dd></div>
      <div><dt>Email</dt><dd><a href="mailto:<?= e($r['email']) ?>"><?= e($r['email']) ?></a></dd></div>
      <div><dt>Service</dt><dd><?= e($r['service']) ?></dd></div>
      <div><dt>Preferred date &amp; time</dt><dd><?= e(fmt_date($r['pref_date'])) ?>, <?= e(time_label($r['pref_time'])) ?></dd></div>
      <div><dt>Consultation type</dt><dd><?= e($r['mode']) ?></dd></div>
      <div><dt>Received</dt><dd><?= e(fmt_date($r['created_at'], true)) ?></dd></div>
      <div><dt>Message</dt><dd><?= $r['message'] ? nl2br(e($r['message'])) : '<span class="muted">None</span>' ?></dd></div>
    </dl>
  </section>
  <section class="panel">
    <h2>Manage</h2>
    <p class="muted">The visitor is told their request is <strong>not confirmed</strong> until you approve it. Approving does not send an automatic message; please contact the client to confirm.</p>
    <form method="post" action="<?= e(url('admin/appointments/' . $r['id'])) ?>" class="form">
      <?= csrf_field() ?>
      <div class="quick-actions">
        <button class="btn btn-sm btn-ok" name="quick" value="approved" type="submit">Approve</button>
        <button class="btn btn-sm btn-danger-outline" name="quick" value="rejected" type="submit">Reject</button>
        <button class="btn btn-sm btn-outline" name="quick" value="completed" type="submit">Mark completed</button>
      </div>
      <div class="field"><label for="status">Or set status</label>
        <select id="status" name="status"><?php foreach (appt_statuses() as $k => $l): ?><option value="<?= e($k) ?>"<?= $r['status'] === $k ? ' selected' : '' ?>><?= e($l) ?></option><?php endforeach; ?></select></div>
      <div class="field"><label for="admin_notes">Internal notes</label><textarea id="admin_notes" name="admin_notes" rows="5" maxlength="4000"><?= e($r['admin_notes']) ?></textarea><p class="help">Only visible to staff.</p></div>
      <button class="btn btn-primary" type="submit">Save notes / status</button>
    </form>
    <form method="post" action="<?= e(url('admin/appointments/' . $r['id'] . '/delete')) ?>" data-confirm="Delete this appointment request permanently?" class="mt-s">
      <?= csrf_field() ?><button class="btn btn-sm btn-danger-outline" type="submit">Delete request</button>
    </form>
  </section>
</div>
