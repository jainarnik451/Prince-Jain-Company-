<article class="card article-card<?= !empty($big) ? ' article-card-big' : '' ?>">
  <a class="article-thumb" href="<?= e(url('insights/' . $a['slug'])) ?>" tabindex="-1" aria-hidden="true">
    <?php if (!empty($a['image'])): ?>
      <img src="<?= e(img_src($a['image'])) ?>" alt="" loading="lazy" width="640" height="400">
    <?php else: ?>
      <span class="thumb-fallback"><?= icon('file', 'icon', 36) ?></span>
    <?php endif; ?>
  </a>
  <div class="article-body">
    <p class="meta"><?php if (!empty($a['category'])): ?><span class="tag"><?= e($a['category']) ?></span><?php endif; ?>
      <time datetime="<?= e(date('Y-m-d', strtotime($a['published_at']))) ?>"><?= e(fmt_date($a['published_at'])) ?></time></p>
    <h3><a href="<?= e(url('insights/' . $a['slug'])) ?>"><?= e($a['title']) ?></a></h3>
    <?php if (!empty($a['excerpt'])): ?><p><?= e(str_limit($a['excerpt'], 150)) ?></p><?php endif; ?>
    <a class="link-arrow" href="<?= e(url('insights/' . $a['slug'])) ?>">Read article <?= icon('arrow', 'icon', 16) ?></a>
  </div>
</article>
