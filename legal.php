<?php partial('page_head', ['heading' => $heading, 'eyebrow' => 'Legal']); ?>
<section class="section">
  <div class="container narrow">
    <?php if ($ready): ?>
      <div class="prose prose-lg legal"><?= md($body) ?></div>
      <?php if (!empty($updated)): ?><p class="muted">Last updated: <?= e(fmt_date($updated)) ?></p><?php endif; ?>
    <?php else: ?>
      <?php partial('empty_state', ['ic' => 'file', 'heading' => 'This page is being prepared',
        'text' => 'This document is being finalised and will be available here soon. For questions in the meantime, please contact us.',
        'cta_url' => url('contact'), 'cta_label' => 'Contact Us']); ?>
    <?php endif; ?>
    <p class="note">The content of this website is for general information and does not constitute professional advice. Advice depends on individual circumstances.</p>
  </div>
</section>
