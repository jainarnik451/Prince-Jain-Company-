<?php
$val = fn($k, $dflt = '') => $_SESSION['_old'][$k] ?? ($row[$k] ?? $dflt);
$isPub = isset($_SESSION['_old']) ? !empty($_SESSION['_old']['published']) : ($row ? (bool)$row['published'] : empty($d['default_unpublished']));
?>
<p><a href="<?= e(url('admin/' . $e)) ?>">&larr; Back to <?= e($d['label']) ?></a></p>
<section class="panel narrow-panel">
  <form method="post" action="<?= e(url('admin/' . $e . '/save')) ?>" class="form">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int)($row['id'] ?? 0) ?>">
    <?php foreach ($d['fields'] as [$name, $label, $type, $o]): $req = !empty($o['required']); ?>
      <div class="field">
        <label for="f-<?= e($name) ?>"><?= e($label) ?><?= $req ? ' *' : '' ?></label>
        <?php if ($type === 'select'): ?>
          <select id="f-<?= e($name) ?>" name="<?= e($name) ?>"<?= $req ? ' required' : '' ?>>
            <?php if (!$req): ?><option value="">—</option><?php endif; ?>
            <?php foreach ($o['options'] as $k => $l): ?><option value="<?= e($k) ?>"<?= (string)$val($name) === (string)$k ? ' selected' : '' ?>><?= e($l) ?></option><?php endforeach; ?>
          </select>
        <?php elseif ($type === 'textarea' || $type === 'markdown'): ?>
          <textarea id="f-<?= e($name) ?>" name="<?= e($name) ?>" rows="<?= $type === 'markdown' ? 8 : 4 ?>"<?= $req ? ' required' : '' ?>><?= e($val($name)) ?></textarea>
        <?php elseif ($type === 'number'): ?>
          <input id="f-<?= e($name) ?>" name="<?= e($name) ?>" type="number" value="<?= e($val($name, '')) ?>" placeholder="Auto">
        <?php else: ?>
          <input id="f-<?= e($name) ?>" name="<?= e($name) ?>" type="<?= $type === 'url' ? 'url' : 'text' ?>"<?= $req ? ' required' : '' ?><?= !empty($o['max']) ? ' maxlength="' . (int)$o['max'] . '"' : '' ?> value="<?= e($val($name)) ?>">
        <?php endif; ?>
        <?php if (!empty($o['help'])): ?><p class="help"><?= e($o['help']) ?></p><?php endif; ?>
        <?php if ($type === 'markdown'): ?><p class="help">Supports <code>**bold**</code>, <code>- lists</code> and <code>[links](https://…)</code>.</p><?php endif; ?>
      </div>
    <?php endforeach; ?>
    <label class="checkbox"><input type="checkbox" name="published" value="1" <?= $isPub ? 'checked' : '' ?>> <span>Published on the website</span></label>
    <?php if (!empty($row['admin_note']) && !$row['published']): ?><div class="alert alert-info"><?= e($row['admin_note']) ?></div><?php endif; ?>
    <button class="btn btn-primary" type="submit">Save</button>
  </form>
</section>
