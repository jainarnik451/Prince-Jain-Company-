<?php $val = fn($k) => $_SESSION['_old'][$k] ?? ($values[$k] ?? ''); ?>
<p><a href="<?= e(url('admin/settings')) ?>">&larr; All settings</a></p>
<section class="panel narrow-panel">
  <p class="muted"><?= e($g['desc']) ?></p>
  <form method="post" action="<?= e(url('admin/settings/' . $group)) ?>" enctype="multipart/form-data" class="form">
    <?= csrf_field() ?>
    <?php foreach ($g['fields'] as $f): $k = $f['key']; ?>
      <div class="field">
        <label for="s-<?= e($k) ?>"><?= e($f['label']) ?></label>
        <?php if ($f['type'] === 'textarea' || $f['type'] === 'markdown'): ?>
          <textarea id="s-<?= e($k) ?>" name="<?= e($k) ?>" rows="<?= $f['type'] === 'markdown' ? 7 : 4 ?>"<?= !empty($f['max']) ? ' maxlength="' . (int)$f['max'] . '"' : '' ?>><?= e($val($k)) ?></textarea>
        <?php elseif ($f['type'] === 'image'): $cur = $values[$k] ?? ''; ?>
          <?php if ($cur !== ''): ?><img class="thumb-prev" src="<?= e(img_src($cur)) ?>" alt="Current image"><label class="checkbox"><input type="checkbox" name="clear_<?= e($k) ?>" value="1"> <span>Remove current image<?= $f['default'] !== '' ? ' (reverts to none)' : '' ?></span></label><?php endif; ?>
          <input id="s-<?= e($k) ?>" name="img_<?= e($k) ?>" type="file" accept="image/jpeg,image/png,image/webp">
        <?php else: ?>
          <input id="s-<?= e($k) ?>" name="<?= e($k) ?>" type="<?= in_array($f['type'], ['email', 'url', 'time'], true) ? e($f['type']) : 'text' ?>"<?= !empty($f['max']) ? ' maxlength="' . (int)$f['max'] . '"' : '' ?> value="<?= e($val($k)) ?>">
        <?php endif; ?>
        <?php if (!empty($f['help'])): ?><p class="help<?= str_starts_with($f['help'], 'PLACEHOLDER') ? ' placeholder' : '' ?>"><?= e($f['help']) ?></p><?php endif; ?>
      </div>
    <?php endforeach; ?>
    <button class="btn btn-primary" type="submit">Save settings</button>
  </form>
</section>
