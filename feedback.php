<div class="filters">
  <a href="<?= e(url('admin/feedback')) ?>" class="<?= $status === '' ? 'on' : '' ?>">All</a>
  <?php foreach (['pending' => 'Pending', 'approved' => 'Approved', 'hidden' => 'Hidden'] as $k => $l): ?><a href="<?= e(url('admin/feedback?status=' . $k)) ?>" class="<?= $status === $k ? 'on' : '' ?>"><?= e($l) ?></a><?php endforeach; ?>
</div>
<p class="muted">Feedback appears on the website only when approved <em>and</em> the person ticked the publishing permission. Email addresses are never published.</p>
<?php if (!$rows): ?>
  <div class="panel"><p class="muted">No feedback<?= $status ? ' with this status' : ' yet' ?>.</p></div>
<?php endif; ?>
<?php foreach ($rows as $r): ?>
  <article class="panel fb">
    <header>
      <div><strong><?= e($r['name']) ?></strong>
        <?php if ($r['email']): ?><small> · <?= e($r['email']) ?></small><?php endif; ?>
        <?php if ($r['service']): ?><small> · <?= e($r['service']) ?></small><?php endif; ?></div>
      <div class="stars" aria-label="<?= (int)$r['rating'] ?> out of 5"><?= str_repeat('★', (int)$r['rating']) . str_repeat('☆', 5 - (int)$r['rating']) ?></div>
    </header>
    <p><?= nl2br(e($r['message'])) ?></p>
    <footer>
      <span class="pill s-<?= e($r['status']) ?>"><?= e(ucfirst($r['status'])) ?></span>
      <span class="pill <?= $r['consent'] ? 's-approved' : 's-rejected' ?>"><?= $r['consent'] ? 'Publishing permission given' : 'No publishing permission' ?></span>
      <small><?= e(fmt_date($r['created_at'], true)) ?></small>
      <form method="post" action="<?= e(url('admin/feedback/' . $r['id'])) ?>" class="inline-actions" data-confirm-delete>
        <?= csrf_field() ?><input type="hidden" name="back" value="<?= e($status) ?>">
        <?php if ($r['status'] !== 'approved' && $r['consent']): ?><button class="btn btn-sm btn-ok" name="action" value="approve" type="submit">Approve</button><?php endif; ?>
        <?php if ($r['status'] !== 'hidden'): ?><button class="btn btn-sm btn-outline" name="action" value="hide" type="submit">Hide</button><?php endif; ?>
        <button class="btn btn-sm btn-danger-outline" name="action" value="delete" type="submit">Delete</button>
      </form>
    </footer>
  </article>
<?php endforeach; ?>
