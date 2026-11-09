<?php
$logoImg = setting('logo_image');
$mark = setting('logo_mark', 'PJ');
$firmName = setting('firm_name');
?>
<a class="logo <?= e($class ?? '') ?>" href="<?= e(url('')) ?>" aria-label="<?= e($firmName) ?> — Home">
  <?php if ($logoImg !== ''): ?>
    <img class="logo-img" src="<?= e(img_src($logoImg)) ?>" alt="<?= e($firmName) ?>" height="44">
  <?php else: ?>
    <span class="logo-mark" aria-hidden="true"><?= e($mark) ?></span>
    <span class="logo-text"><strong><?= e($firmName) ?></strong><small>Chartered Accountants</small></span>
  <?php endif; ?>
</a>
