<div class="tiles tiles-wide">
  <?php foreach ($groups as $k => $g): ?>
    <a class="tile tile-text" href="<?= e(url('admin/settings/' . $k)) ?>"><strong><?= e($g['title']) ?></strong><span><?= e($g['desc']) ?></span></a>
  <?php endforeach; ?>
</div>
