<?php
partial('page_head', ['heading' => 'Areas of Expertise', 'crumb' => 'Expertise', 'eyebrow' => 'Professional Strengths',
    'lead' => 'The professional expertise CA ' . setting('ca_name') . ' brings to accounting, taxation, financial planning and business advisory work.']);
?>
<section class="section">
  <div class="container">
    <?php if ($items): ?>
      <div class="card-grid grid-3">
        <?php foreach ($items as $x): ?>
          <article class="card expertise-card tall reveal">
            <span class="card-icon"><?= icon($x['icon'] ?: 'file', 'icon', 28) ?></span>
            <h3><?= e($x['title']) ?></h3>
            <p><?= e($x['description']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <?php partial('empty_state', ['ic' => 'award', 'heading' => 'Expertise details coming soon',
        'text' => 'This section is being updated.', 'cta_url' => url('contact'), 'cta_label' => 'Contact Us']); ?>
    <?php endif; ?>
    <p class="note center">Descriptions are general in nature. Services are provided within the scope of the firm's professional authorisations.</p>
  </div>
</section>
<section class="cta-band">
  <div class="container cta-inner">
    <div><h2><?= e(setting('cta_title')) ?></h2><p><?= e(setting('cta_text')) ?></p></div>
    <div class="btn-row">
      <a class="btn btn-gold" href="<?= e(url('book-consultation')) ?>">Book a Consultation</a>
      <a class="btn btn-light" href="<?= e(url('services')) ?>">View Services</a>
    </div>
  </div>
</section>
