<div class="review-grid">
  <?php foreach ($reviews as $r): ?>
    <figure class="card review-card">
      <div class="stars" role="img" aria-label="Rated <?= (int)$r['rating'] ?> out of 5">
        <?php for ($i = 1; $i <= 5; $i++): ?><span class="<?= $i <= (int)$r['rating'] ? 'on' : 'off' ?>"><?= icon('star', 'icon', 18) ?></span><?php endfor; ?>
      </div>
      <blockquote><?= nl2br(e($r['message'])) ?></blockquote>
      <figcaption><strong><?= e($r['name']) ?></strong><?php if (!empty($r['service'])): ?> <span><?= e($r['service']) ?></span><?php endif; ?></figcaption>
    </figure>
  <?php endforeach; ?>
</div>
