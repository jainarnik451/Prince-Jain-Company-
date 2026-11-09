<p><a href="<?= e(url('admin/enquiries')) ?>">&larr; All enquiries</a></p>
<div class="two-col wide-left">
  <section class="panel">
    <h2><?= e($r['subject']) ?></h2>
    <dl class="details">
      <div><dt>Name</dt><dd><?= e($r['name']) ?></dd></div>
      <div><dt>Email</dt><dd><a href="mailto:<?= e($r['email']) ?>"><?= e($r['email']) ?></a></dd></div>
      <?php if ($r['phone']): ?><div><dt>Phone</dt><dd><a href="<?= e(tel_link($r['phone'])) ?>"><?= e($r['phone']) ?></a> · <a href="<?= e(wa_link($r['phone'])) ?>" target="_blank" rel="noopener">WhatsApp</a></dd></div><?php endif; ?>
      <?php if ($r['service']): ?><div><dt>Service</dt><dd><?= e($r['service']) ?></dd></div><?php endif; ?>
      <div><dt>Received</dt><dd><?= e(fmt_date($r['created_at'], true)) ?></dd></div>
      <div><dt>Message</dt><dd><?= nl2br(e($r['message'])) ?></dd></div>
    </dl>
  </section>
  <section class="panel">
    <h2>Manage</h2>
    <form method="post" action="<?= e(url('admin/enquiries/' . $r['id'])) ?>" class="form">
      <?= csrf_field() ?>
      <div class="field"><label for="status">Status</label>
        <select id="status" name="status"><?php foreach (enq_statuses() as $k => $l): ?><option value="<?= e($k) ?>"<?= $r['status'] === $k ? ' selected' : '' ?>><?= e($l) ?></option><?php endforeach; ?></select></div>
      <div class="field"><label for="admin_notes">Internal notes</label><textarea id="admin_notes" name="admin_notes" rows="5" maxlength="4000"><?= e($r['admin_notes']) ?></textarea></div>
      <div class="quick-actions">
        <button class="btn btn-primary" type="submit">Save</button>
        <button class="btn btn-outline" type="submit" name="mark_unread" value="1">Save &amp; mark unread</button>
      </div>
    </form>
    <form method="post" action="<?= e(url('admin/enquiries/' . $r['id'] . '/delete')) ?>" data-confirm="Delete this enquiry permanently?" class="mt-s">
      <?= csrf_field() ?><button class="btn btn-sm btn-danger-outline" type="submit">Delete enquiry</button>
    </form>
  </section>
</div>
