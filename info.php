<?php
partial('page_head', ['heading' => 'Additional Information', 'eyebrow' => 'Resources',
    'lead' => 'Announcements, answers to common questions and updates from ' . setting('firm_name') . '.']);
?>
<section class="section">
  <div class="container">
    <nav class="chip-filter info-nav" aria-label="Sections">
      <ul><?php foreach ($sections as $key => [$label, $ic, $empty]): ?><li><a href="#<?= e($key) ?>"><?= e($label) ?></a></li><?php endforeach; ?></ul>
    </nav>

    <?php foreach ($sections as $key => [$label, $ic, $emptyText]): $list = $items[$key] ?? []; ?>
      <section class="info-section" id="<?= e($key) ?>">
        <header class="info-head"><span class="card-icon sm"><?= icon($ic, 'icon', 22) ?></span><h2><?= e($label) ?></h2></header>

        <?php if ($key === 'testimonial'): ?>
          <?php if ($reviews || $list): ?>
            <?php if ($reviews) { partial('reviews', ['reviews' => $reviews]); } ?>
            <?php foreach ($list as $it): ?>
              <figure class="card review-card"><blockquote><?= nl2br(e(md_plain($it['body']))) ?></blockquote><figcaption><strong><?= e($it['title']) ?></strong></figcaption></figure>
            <?php endforeach; ?>
          <?php else: ?>
            <?php partial('empty_state', ['ic' => $ic, 'heading' => 'Coming soon', 'text' => $emptyText]); ?>
          <?php endif; ?>

        <?php elseif ($key === 'faq'): ?>
          <?php if ($list): ?>
            <div class="faq">
              <?php foreach ($list as $it): ?>
                <details><summary><?= e($it['title']) ?><span class="svc-toggle" aria-hidden="true"><?= icon('chevron', 'icon', 20) ?></span></summary>
                  <div class="prose"><?= md($it['body']) ?></div></details>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <?php partial('empty_state', ['ic' => $ic, 'heading' => 'Coming soon', 'text' => $emptyText]); ?>
          <?php endif; ?>

        <?php else: ?>
          <?php if ($list): ?>
            <div class="info-list">
              <?php foreach ($list as $it): ?>
                <article class="card info-card">
                  <h3><?= e($it['title']) ?></h3>
                  <?php if ($it['body']): ?><div class="prose"><?= md($it['body']) ?></div><?php endif; ?>
                  <?php if ($it['link_url'] && preg_match('#^https?://#i', $it['link_url'])): ?>
                    <a class="link-arrow" href="<?= e($it['link_url']) ?>" target="_blank" rel="noopener noreferrer">Learn more <?= icon('arrow', 'icon', 16) ?></a>
                  <?php endif; ?>
                </article>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <?php partial('empty_state', ['ic' => $ic, 'heading' => 'Nothing to show yet', 'text' => $emptyText]); ?>
          <?php endif; ?>
        <?php endif; ?>
      </section>
    <?php endforeach; ?>
  </div>
</section>
