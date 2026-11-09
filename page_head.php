<section class="page-head">
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="<?= e(url('')) ?>">Home</a><span aria-hidden="true">/</span><span aria-current="page"><?= e($crumb ?? $heading) ?></span>
    </nav>
    <p class="eyebrow"><?= e($eyebrow ?? '') ?></p>
    <h1><?= e($heading) ?></h1>
    <?php if (!empty($lead)): ?><p class="lead"><?= e($lead) ?></p><?php endif; ?>
  </div>
</section>
