<?php
partial('page_head', ['heading' => 'Insights & Articles', 'crumb' => 'Insights', 'eyebrow' => 'Knowledge Centre',
    'lead' => 'Articles and updates on income tax, GST, accounting, finance and business advisory.']);
$filtering = ($q !== '' || $cat !== '');
?>
<section class="section">
  <div class="container">
    <?php if ($totalAll > 0 || $filtering): ?>
      <form class="blog-tools" method="get" action="<?= e(url('insights')) ?>" role="search">
        <div class="search-box">
          <label class="sr-only" for="blog-q">Search articles</label>
          <?= icon('search', 'icon', 18) ?>
          <input id="blog-q" type="search" name="q" value="<?= e($q) ?>" placeholder="Search articles" maxlength="80">
          <?php if ($cat !== ''): ?><input type="hidden" name="cat" value="<?= e($cat) ?>"><?php endif; ?>
          <button class="btn btn-primary btn-sm" type="submit">Search</button>
        </div>
        <ul class="chip-filter" aria-label="Filter by category">
          <li><a href="<?= e(url('insights') . ($q !== '' ? '?q=' . rawurlencode($q) : '')) ?>"<?= $cat === '' ? ' class="is-active" aria-current="true"' : '' ?>>All</a></li>
          <?php foreach ($cats as $c): ?>
            <li><a href="<?= e(url('insights') . '?' . http_build_query(array_filter(['cat' => $c, 'q' => $q]))) ?>"<?= $cat === $c ? ' class="is-active" aria-current="true"' : '' ?>><?= e($c) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </form>
    <?php endif; ?>

    <?php if ($featured): ?>
      <h2 class="mini-head">Featured</h2>
      <div class="card-grid grid-2 featured-grid">
        <?php foreach ($featured as $a) { partial('article_card', ['a' => $a, 'big' => true]); } ?>
      </div>
      <h2 class="mini-head">Latest</h2>
    <?php endif; ?>

    <?php if ($articles): ?>
      <?php if ($filtering): ?><p class="muted"><?= (int)$total ?> article<?= $total === 1 ? '' : 's' ?> found<?= $q !== '' ? ' for “' . e($q) . '”' : '' ?><?= $cat !== '' ? ' in ' . e($cat) : '' ?>. <a href="<?= e(url('insights')) ?>">Clear filters</a></p><?php endif; ?>
      <div class="card-grid grid-3">
        <?php foreach ($articles as $a) { partial('article_card', ['a' => $a]); } ?>
      </div>
      <?= paginate_links($page, $pages, url('insights'), array_filter(['q' => $q, 'cat' => $cat])) ?>
    <?php elseif ($filtering): ?>
      <?php partial('empty_state', ['ic' => 'search', 'heading' => 'No articles found',
        'text' => 'Try a different search term or category.', 'cta_url' => url('insights'), 'cta_label' => 'View all articles']); ?>
    <?php else: ?>
      <?php partial('empty_state', ['ic' => 'newspaper', 'heading' => 'Articles coming soon',
        'text' => 'We will publish insights on income tax, GST, accounting and business advisory here. Please check back soon.',
        'cta_url' => url('contact'), 'cta_label' => 'Contact Us']); ?>
    <?php endif; ?>
  </div>
</section>
