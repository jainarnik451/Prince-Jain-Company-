<div class="empty-state">
  <span class="empty-icon"><?= icon($ic ?? 'file', 'icon', 28) ?></span>
  <h3><?= e($heading ?? 'Coming soon') ?></h3>
  <p><?= e($text ?? '') ?></p>
  <?php if (!empty($cta_url)): ?><a class="btn btn-outline" href="<?= e($cta_url) ?>"><?= e($cta_label ?? 'Contact us') ?></a><?php endif; ?>
</div>
