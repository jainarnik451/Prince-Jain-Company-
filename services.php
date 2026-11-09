<?php
partial('page_head', ['heading' => 'Our Services', 'eyebrow' => 'What We Do',
    'lead' => 'Accounting, taxation, audit, GST, business consultancy and financial advisory services from a Chartered Accountant firm in Thane.']);
$shown = array_filter($cats, fn($k) => !empty($byCat[$k]), ARRAY_FILTER_USE_KEY);
?>
<section class="section">
  <div class="container services-layout">
    <nav class="cat-nav" aria-label="Service categories">
      <ul>
        <?php foreach ($shown as $slug => $c): ?>
          <li><a href="#<?= e($slug) ?>"><?= icon($c['icon'], 'icon', 18) ?><span><?= e($c['label']) ?></span></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="services-list">
      <?php if (!$shown): ?>
        <?php partial('empty_state', ['ic' => 'briefcase', 'heading' => 'Services are being updated',
          'text' => 'Please contact us to discuss your requirements.', 'cta_url' => url('contact'), 'cta_label' => 'Contact Us']); ?>
      <?php endif; ?>
      <?php foreach ($shown as $slug => $c): ?>
        <section class="svc-cat" id="<?= e($slug) ?>">
          <header class="svc-cat-head reveal">
            <span class="card-icon"><?= icon($c['icon'], 'icon', 26) ?></span>
            <div><h2><?= e($c['label']) ?></h2><p><?= e($c['blurb']) ?></p></div>
          </header>
          <div class="svc-items">
            <?php foreach ($byCat[$slug] as $s): ?>
              <details class="svc reveal" id="svc-<?= e($s['slug']) ?>">
                <summary>
                  <span class="svc-title"><?= e($s['title']) ?></span>
                  <span class="svc-short"><?= e($s['short_desc']) ?></span>
                  <span class="svc-toggle" aria-hidden="true"><?= icon('chevron', 'icon', 20) ?></span>
                </summary>
                <div class="svc-body">
                  <?php if ($s['description']): ?><div class="prose"><?= md($s['description']) ?></div><?php endif; ?>
                  <div class="svc-cols">
                    <?php if ($s['who_benefits']): ?><div><h4>Who can benefit</h4><p><?= e($s['who_benefits']) ?></p></div><?php endif; ?>
                    <?php if ($s['key_info']): ?><div><h4>Key information</h4>
                      <ul class="check-list sm"><?php foreach (lines($s['key_info']) as $k): ?><li><?= icon('check', 'icon', 16) ?><?= e($k) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
                  </div>
                  <div class="btn-row">
                    <a class="btn btn-primary btn-sm" href="<?= e(url('contact?service=' . rawurlencode($s['title']))) ?>">Contact Us</a>
                    <a class="btn btn-outline btn-sm js-enquire" href="<?= e(url('contact?service=' . rawurlencode($s['title']))) ?>" data-service="<?= e($s['title']) ?>">Enquire About This Service</a>
                  </div>
                </div>
              </details>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endforeach; ?>

      <p class="note">Service descriptions are for general information. The scope of each engagement is confirmed with you in writing before work begins.</p>
    </div>
  </div>
</section>

<dialog id="enquiry-dialog" class="dialog" aria-labelledby="enquiry-title">
  <form method="post" action="<?= e(url('contact')) ?>" class="form" novalidate>
    <div class="dialog-head"><h2 id="enquiry-title">Quick Enquiry</h2>
      <button type="button" class="icon-btn js-dialog-close" aria-label="Close"><?= icon('close', 'icon', 20) ?></button></div>
    <p class="muted" id="enquiry-service-label"></p>
    <?= csrf_field() ?><?= spam_fields() ?>
    <input type="hidden" name="service" id="enquiry-service" value="">
    <input type="hidden" name="subject" id="enquiry-subject" value="">
    <div class="field"><label for="q-name">Full name *</label><input id="q-name" name="name" required maxlength="120" autocomplete="name"></div>
    <div class="field-row">
      <div class="field"><label for="q-phone">Phone</label><input id="q-phone" name="phone" type="tel" maxlength="30" autocomplete="tel"></div>
      <div class="field"><label for="q-email">Email *</label><input id="q-email" name="email" type="email" required maxlength="190" autocomplete="email"></div>
    </div>
    <div class="field"><label for="q-msg">Message *</label><textarea id="q-msg" name="message" rows="4" required minlength="10" maxlength="4000"></textarea></div>
    <button class="btn btn-primary" type="submit">Send Enquiry</button>
  </form>
</dialog>

<section class="cta-band">
  <div class="container cta-inner">
    <div><h2>Not sure which service you need?</h2><p>Tell us about your requirement and we will guide you to the right next step.</p></div>
    <div class="btn-row">
      <a class="btn btn-gold" href="<?= e(url('book-consultation')) ?>">Book a Consultation</a>
      <a class="btn btn-light" href="<?= e(url('contact')) ?>">Contact Us</a>
    </div>
  </div>
</section>
