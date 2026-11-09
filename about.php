<?php
$firm = setting('firm_name');
$photo = setting('profile_photo');
$photoSrc = $photo === 'assets/img/prince-jain.jpg' ? 'assets/img/prince-jain-640.jpg' : $photo;
$addl = lines(setting('additional_qualifications'));
$ach = lines(setting('achievements'));
$icai = setting('icai_membership');
$mission = setting('mission');
$vision = setting('vision');
$office = array_values(array_filter([setting('office_photo_1'), setting('office_photo_2'), setting('office_photo_3')]));
partial('page_head', ['heading' => 'About Us', 'eyebrow' => 'Our Profile',
    'lead' => 'Professional accounting, taxation and advisory services from Thane since ' . setting('established') . '.']);
?>
<section class="section">
  <div class="container about-grid">
    <aside class="about-aside reveal">
      <div class="photo-frame">
        <img src="<?= e(img_src($photoSrc)) ?>" alt="<?= e(setting('ca_name')) ?>, <?= e(setting('designation')) ?>" width="640" height="800" loading="lazy">
      </div>
      <div class="card profile-card">
        <h2>CA <?= e(setting('ca_name')) ?></h2>
        <dl class="facts">
          <div><dt>Designation</dt><dd><?= e(setting('designation')) ?></dd></div>
          <div><dt>Qualification</dt><dd><?= e(setting('qualification')) ?></dd></div>
          <div><dt>Experience</dt><dd><?= e(setting('experience_years')) ?> years</dd></div>
          <div><dt>Firm established</dt><dd><?= e(setting('established')) ?></dd></div>
          <div><dt>Location</dt><dd>Thane, Maharashtra</dd></div>
          <?php if ($icai !== ''): ?><div><dt>ICAI membership no.</dt><dd><?= e($icai) ?></dd></div><?php endif; ?>
        </dl>
        <?php if (setting('linkedin_url') !== ''): ?>
          <a class="link-arrow" href="<?= e(setting('linkedin_url')) ?>" target="_blank" rel="noopener noreferrer"><?= icon('linkedin', 'icon', 18) ?> View LinkedIn profile</a>
        <?php endif; ?>
      </div>
    </aside>

    <div class="about-main">
      <div class="reveal">
        <h2>About Prince Jain</h2>
        <div class="prose"><?= md(setting('about_bio')) ?></div>
      </div>
      <div class="reveal mt">
        <h2>Professional Journey</h2>
        <div class="prose"><?= md(setting('about_journey')) ?></div>
      </div>
      <div class="reveal mt">
        <h2>Professional Philosophy</h2>
        <div class="prose"><?= md(setting('about_approach')) ?></div>
      </div>

      <?php if ($addl): ?>
        <div class="reveal mt"><h2>Additional Qualifications</h2>
          <ul class="check-list"><?php foreach ($addl as $l): ?><li><?= icon('check', 'icon', 18) ?><?= e($l) ?></li><?php endforeach; ?></ul></div>
      <?php endif; ?>
      <?php if ($ach): ?>
        <div class="reveal mt"><h2>Professional Achievements</h2>
          <ul class="check-list"><?php foreach ($ach as $l): ?><li><?= icon('award', 'icon', 18) ?><?= e($l) ?></li><?php endforeach; ?></ul></div>
      <?php endif; ?>

      <?php if ($mission !== '' || $vision !== ''): ?>
        <div class="mv-grid mt">
          <?php if ($mission !== ''): ?><div class="card reveal"><h3>Our Mission</h3><div class="prose"><?= md($mission) ?></div></div><?php endif; ?>
          <?php if ($vision !== ''): ?><div class="card reveal"><h3>Our Vision</h3><div class="prose"><?= md($vision) ?></div></div><?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php if ($timeline): ?>
<section class="section section-grey">
  <div class="container">
    <div class="section-head reveal"><p class="eyebrow">Firm History</p><h2>Our journey</h2></div>
    <ol class="timeline">
      <?php foreach ($timeline as $t): ?>
        <li class="reveal">
          <span class="tl-year"><?= e($t['year']) ?></span>
          <div class="tl-body"><h3><?= e($t['title']) ?></h3><?php if (!empty($t['description'])): ?><p><?= e($t['description']) ?></p><?php endif; ?></div>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>
<?php endif; ?>

<?php if ($expertise): ?>
<section class="section">
  <div class="container">
    <div class="section-head reveal"><p class="eyebrow">Areas of Expertise</p><h2>Where we can help</h2></div>
    <ul class="chip-list reveal">
      <?php foreach ($expertise as $x): ?><li><?= icon($x['icon'] ?: 'file', 'icon', 18) ?><?= e($x['title']) ?></li><?php endforeach; ?>
    </ul>
    <p class="center mt"><a class="btn btn-outline" href="<?= e(url('expertise')) ?>">Explore Areas of Expertise</a></p>
  </div>
</section>
<?php endif; ?>

<?php if ($office): ?>
<section class="section section-grey">
  <div class="container">
    <div class="section-head reveal"><p class="eyebrow">Our Office</p><h2>Visit us in Thane</h2></div>
    <div class="gallery">
      <?php foreach ($office as $i => $p): ?><img src="<?= e(img_src($p)) ?>" alt="<?= e($firm) ?> office, photograph <?= $i + 1 ?>" loading="lazy"><?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="cta-band">
  <div class="container cta-inner">
    <div><h2><?= e(setting('cta_title')) ?></h2><p><?= e(setting('cta_text')) ?></p></div>
    <div class="btn-row">
      <a class="btn btn-gold" href="<?= e(url('book-consultation')) ?>">Book a Consultation</a>
      <a class="btn btn-light" href="<?= e(url('contact')) ?>">Contact Us</a>
    </div>
  </div>
</section>
