<section class="section error-page">
  <div class="container narrow center">
    <p class="error-code"><?= (int)$code ?></p>
    <h1>Something went wrong</h1>
    <p class="lead"><?= e($message) ?></p>
    <div class="btn-row center-row">
      <a class="btn btn-primary" href="<?= e(url('')) ?>">Back to Home</a>
      <a class="btn btn-outline" href="<?= e(url('contact')) ?>">Contact Us</a>
    </div>
  </div>
</section>
