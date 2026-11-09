<?php
$firm = setting('firm_name');
$cats = service_categories();
unset($cats['other']);
$links = db_all("SELECT label, url FROM links WHERE grp = 'important' AND published = 1 ORDER BY sort_order, id");
$socials = social_links();
$quick = [['', 'Home'], ['about', 'About Us'], ['services', 'Services'], ['expertise', 'Areas of Expertise'],
    ['insights', 'Insights'], ['feedback', 'Feedback'], ['contact', 'Contact Us']];
?>
<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-col footer-brand">
      <?php partial('logo', ['class' => 'logo-footer']); ?>
      <p><?= e($firm) ?> is a Chartered Accountancy practice in Thane, Maharashtra, established in <?= e(setting('established')) ?>, providing accounting, taxation, financial and business advisory services.</p>
      <?php if (setting('tagline') !== ''): ?><p class="footer-tagline"><?= e(setting('tagline')) ?></p><?php endif; ?>
      <?php if ($socials): ?>
        <ul class="social-list">
          <?php foreach ($socials as $s): ?>
            <li><a href="<?= e($s['url']) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= e($s['label']) ?>"><?= icon($s['icon'], 'icon', 20) ?></a></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

    <div class="footer-col">
      <h2>Quick Links</h2>
      <ul>
        <?php foreach ($quick as [$p, $l]): ?><li><a href="<?= e(url($p)) ?>"><?= e($l) ?></a></li><?php endforeach; ?>
      </ul>
    </div>

    <div class="footer-col">
      <h2>Our Services</h2>
      <ul>
        <?php foreach ($cats as $slug => $c): ?><li><a href="<?= e(url('services#' . $slug)) ?>"><?= e($c['short']) ?></a></li><?php endforeach; ?>
      </ul>
    </div>

    <div class="footer-col">
      <h2>Important Links</h2>
      <ul>
        <?php foreach ($links as $l):
            $href = link_href($l['url']);
            if ($href === '') { continue; }
            $ext = preg_match('#^https?://#i', $href); ?>
          <li><a href="<?= e($href) ?>"<?= $ext ? ' target="_blank" rel="noopener noreferrer"' : '' ?>><?= e($l['label']) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <ul class="footer-contact">
        <li><?= icon('phone', 'icon', 16) ?><a href="<?= e(tel_link(setting('phone'))) ?>"><?= e(setting('phone')) ?></a></li>
        <li><?= icon('mail', 'icon', 16) ?><a href="mailto:<?= e(setting('email')) ?>"><?= e(setting('email')) ?></a></li>
      </ul>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="container footer-bottom-inner">
      <p>&copy; <?= date('Y') ?> <?= e($firm) ?>. All Rights Reserved.</p>
      <p class="footer-note">Information on this website is for general guidance only and is not professional advice. <a href="<?= e(url('disclaimer')) ?>">Read the disclaimer</a>.</p>
    </div>
  </div>
</footer>
