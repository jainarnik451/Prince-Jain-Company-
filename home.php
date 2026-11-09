<?php
$firm = setting('firm_name');
$photo = setting('profile_photo');
$photoSrc = $photo === 'assets/img/prince-jain.jpg' ? 'assets/img/prince-jain-640.jpg' : $photo;
$waText = 'Hello ' . $firm . ', I would like to enquire about your services.';
?>
<section class="hero">
  <div class="hero-bg" aria-hidden="true">
    <svg viewBox="0 0 1200 640" preserveAspectRatio="xMaxYMid slice" fill="none">
      <g stroke="currentColor" stroke-width="1" opacity=".55">
        <?php for ($i = 0; $i < 14; $i++): ?><path d="M0 <?= 40 + $i * 44 ?>H1200"/><?php endfor; ?>
      </g>
      <g stroke="currentColor" stroke-width="1.5" opacity=".9">
        <path d="M720 560V420M780 560V360M840 560V400M900 560V300M960 560V340M1020 560V230M1080 560V270"/>
        <path d="M700 430L780 370L840 405L900 310L960 345L1020 240L1100 280" stroke-linejoin="round"/>
      </g>
    </svg>
  </div>
  <div class="container hero-inner">
    <div class="hero-copy">
      <p class="eyebrow">Chartered Accountants &middot; Thane, Maharashtra</p>
      <h1><?= e(setting('hero_headline')) ?></h1>
      <p class="lead"><?= e(setting('hero_subtext')) ?></p>
      <p class="hero-intro"><?= e(setting('hero_intro')) ?></p>
      <div class="btn-row">
        <a class="btn btn-primary btn-lg" href="<?= e(url('services')) ?>">Explore Our Services <?= icon('arrow', 'icon', 18) ?></a>
        <a class="btn btn-outline btn-lg" href="<?= e(url('book-consultation')) ?>">Book a Consultation</a>
      </div>
    </div>
    <div class="hero-photo">
      <div class="photo-frame">
        <img src="<?= e(img_src($photoSrc)) ?>" alt="<?= e(setting('ca_name')) ?>, <?= e(setting('designation')) ?>, <?= e($firm) ?>, Thane" width="640" height="800" fetchpriority="high">
      </div>
      <div class="photo-badge">
        <strong>CA <?= e(setting('ca_name')) ?></strong>
        <span><?= e(setting('designation')) ?> &middot; Est. <?= e(setting('established')) ?></span>
      </div>
    </div>
  </div>
</section>

<section class="stats" aria-label="Experience highlights">
  <div class="container stats-grid">
    <div class="stat reveal"><span class="stat-value"><?= e(setting('established')) ?></span><span class="stat-label">Established</span></div>
    <div class="stat reveal"><span class="stat-value"><?= e(setting('experience_years')) ?></span><span class="stat-label">Years of professional experience</span></div>
    <div class="stat reveal"><span class="stat-value">CA</span><span class="stat-label"><?= e(setting('designation')) ?></span></div>
    <div class="stat reveal"><span class="stat-value">Thane</span><span class="stat-label">Based in Maharashtra, India</span></div>
  </div>
</section>

<section class="section">
  <div class="container split">
    <div class="reveal">
      <p class="eyebrow">About the Firm</p>
      <h2>A trusted Chartered Accountancy practice in Thane</h2>
    </div>
    <div class="reveal prose">
      <?= md(setting('home_intro_text')) ?>
      <a class="btn btn-outline" href="<?= e(url('about')) ?>">Read More <?= icon('arrow', 'icon', 18) ?></a>
    </div>
  </div>
</section>

<section class="section section-grey" id="services">
  <div class="container">
    <div class="section-head reveal">
      <p class="eyebrow">Our Services</p>
      <h2>Professional services for individuals and businesses</h2>
      <p class="lead">From everyday accounting and taxation to financial planning and business consultancy.</p>
    </div>
    <div class="card-grid grid-3">
      <?php foreach ($cats as $slug => $c): ?>
        <article class="card service-card reveal">
          <span class="card-icon"><?= icon($c['icon'], 'icon', 28) ?></span>
          <h3><?= e($c['label']) ?></h3>
          <p><?= e($c['blurb']) ?></p>
          <a class="link-arrow" href="<?= e(url('services#' . $slug)) ?>">Learn More <?= icon('arrow', 'icon', 16) ?></a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php if ($why): ?>
<section class="section">
  <div class="container">
    <div class="section-head reveal">
      <p class="eyebrow">Why Choose Us</p>
      <h2>A professional approach built on experience</h2>
    </div>
    <div class="why-grid">
      <?php foreach ($why as $i => [$t, $d]): ?>
        <div class="why-item reveal">
          <span class="why-num"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
          <h3><?= e($t) ?></h3>
          <?php if ($d !== ''): ?><p><?= e($d) ?></p><?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($expertise): ?>
<section class="section section-grey">
  <div class="container">
    <div class="section-head reveal">
      <p class="eyebrow">Areas of Expertise</p>
      <h2>The professional expertise of CA <?= e(setting('ca_name')) ?></h2>
    </div>
    <div class="card-grid grid-3">
      <?php foreach ($expertise as $x): ?>
        <div class="card expertise-card reveal">
          <span class="card-icon sm"><?= icon($x['icon'] ?: 'file', 'icon', 24) ?></span>
          <div><h3><?= e($x['title']) ?></h3><p><?= e($x['description']) ?></p></div>
        </div>
      <?php endforeach; ?>
    </div>
    <p class="center mt"><a class="btn btn-outline" href="<?= e(url('expertise')) ?>">View All Areas of Expertise</a></p>
  </div>
</section>
<?php endif; ?>

<section class="section">
  <div class="container">
    <div class="section-head reveal">
      <p class="eyebrow">Insights</p>
      <h2>Latest articles and updates</h2>
    </div>
    <?php if ($articles): ?>
      <div class="card-grid grid-3">
        <?php foreach ($articles as $a) { partial('article_card', ['a' => $a]); } ?>
      </div>
      <p class="center mt"><a class="btn btn-outline" href="<?= e(url('insights')) ?>">View All Insights</a></p>
    <?php else: ?>
      <?php partial('empty_state', ['ic' => 'newspaper', 'heading' => 'Articles are on the way',
        'text' => 'Insights on income tax, GST, accounting and business advisory will be published here.',
        'cta_url' => url('contact'), 'cta_label' => 'Contact Us']); ?>
    <?php endif; ?>
  </div>
</section>

<section class="cta-band">
  <div class="container cta-inner">
    <div>
      <h2><?= e(setting('cta_title')) ?></h2>
      <p><?= e(setting('cta_text')) ?></p>
    </div>
    <div class="btn-row">
      <a class="btn btn-light" href="<?= e(url('contact')) ?>">Contact Us</a>
      <a class="btn btn-gold" href="<?= e(url('book-consultation')) ?>">Book a Consultation</a>
      <a class="btn btn-ghost" href="<?= e(wa_link(setting('whatsapp_1'), $waText)) ?>" target="_blank" rel="noopener noreferrer"><?= icon('whatsapp', 'icon', 18) ?> WhatsApp Us</a>
    </div>
  </div>
</section>
