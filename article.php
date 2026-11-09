<?php
$a = $article;
$shareUrl = abs_url('insights/' . $a['slug']);
$enc = rawurlencode($shareUrl);
$encT = rawurlencode($a['title']);
?>
<section class="page-head page-head-article">
  <div class="container narrow">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="<?= e(url('')) ?>">Home</a><span aria-hidden="true">/</span><a href="<?= e(url('insights')) ?>">Insights</a>
    </nav>
    <p class="meta"><?php if ($a['category']): ?><a class="tag" href="<?= e(url('insights?cat=' . rawurlencode($a['category']))) ?>"><?= e($a['category']) ?></a><?php endif; ?>
      <time datetime="<?= e(date('Y-m-d', strtotime($a['published_at']))) ?>"><?= e(fmt_date($a['published_at'])) ?></time>
      <span><?= reading_time($a['body']) ?> min read</span></p>
    <h1><?= e($a['title']) ?></h1>
    <?php if ($a['excerpt']): ?><p class="lead"><?= e($a['excerpt']) ?></p><?php endif; ?>
    <p class="byline">By <?= e($a['author'] ?: 'CA ' . setting('ca_name')) ?></p>
  </div>
</section>

<article class="section article-section">
  <div class="container narrow">
    <?php if ($a['image']): ?>
      <figure class="article-hero"><img src="<?= e(img_src($a['image'])) ?>" alt="<?= e($a['image_alt'] ?: $a['title']) ?>" width="1200" height="675"></figure>
    <?php endif; ?>
    <div class="prose prose-lg"><?= md($a['body']) ?></div>

    <div class="share" aria-label="Share this article">
      <span><?= icon('share', 'icon', 18) ?> Share</span>
      <a href="https://wa.me/?text=<?= $encT . '%20' . $enc ?>" target="_blank" rel="noopener noreferrer">WhatsApp</a>
      <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $enc ?>" target="_blank" rel="noopener noreferrer">LinkedIn</a>
      <a href="https://twitter.com/intent/tweet?url=<?= $enc ?>&amp;text=<?= $encT ?>" target="_blank" rel="noopener noreferrer">X</a>
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $enc ?>" target="_blank" rel="noopener noreferrer">Facebook</a>
      <a href="mailto:?subject=<?= $encT ?>&amp;body=<?= $enc ?>">Email</a>
      <button type="button" class="js-copy" data-copy="<?= e($shareUrl) ?>"><?= icon('link', 'icon', 16) ?> Copy link</button>
    </div>
    <p class="note">This article is for general information and is not professional advice. Please consult us about your specific circumstances.</p>
  </div>
</article>

<?php if ($related): ?>
<section class="section section-grey">
  <div class="container">
    <div class="section-head"><h2>Related articles</h2></div>
    <div class="card-grid grid-3"><?php foreach ($related as $r) { partial('article_card', ['a' => $r]); } ?></div>
  </div>
</section>
<?php endif; ?>
