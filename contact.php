<?php
$firm = setting('firm_name');
partial('page_head', ['heading' => 'Contact Us', 'eyebrow' => 'Get in Touch',
    'lead' => 'Call, message or visit us in Thane. We usually respond to enquiries during office hours.']);
$addr = setting('address');
$mapQ = rawurlencode($firm . ', ' . $addr);
$directions = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode($addr);
$waText = 'Hello ' . $firm . ', I would like to enquire about your services.';
$opts = ['' => 'Select (optional)', 'Not sure / General enquiry' => 'Not sure / General enquiry'];
foreach (service_titles() as $t) { $opts[$t] = $t; }
$hrs = office_hours_rows();
?>
<section class="section">
  <div class="container contact-grid">
    <div class="contact-info">
      <div class="card contact-card">
        <h2><?= e($firm) ?></h2>
        <ul class="contact-list">
          <li><?= icon('pin', 'icon', 22) ?><div><strong>Office address</strong><span><?= nl2br(e($addr)) ?></span></div></li>
          <li><?= icon('phone', 'icon', 22) ?><div><strong>Phone</strong><a href="<?= e(tel_link(setting('phone'))) ?>"><?= e(setting('phone')) ?></a></div></li>
          <li><?= icon('whatsapp', 'icon', 22) ?><div><strong>WhatsApp</strong>
            <a href="<?= e(wa_link(setting('whatsapp_1'), $waText)) ?>" target="_blank" rel="noopener noreferrer"><?= e(setting('whatsapp_1')) ?></a>
            <?php if (digits(setting('whatsapp_2')) !== digits(setting('whatsapp_1')) && setting('whatsapp_2') !== ''): ?>
              <a href="<?= e(wa_link(setting('whatsapp_2'), $waText)) ?>" target="_blank" rel="noopener noreferrer"><?= e(setting('whatsapp_2')) ?></a><?php endif; ?></div></li>
          <li><?= icon('mail', 'icon', 22) ?><div><strong>Email</strong><a href="mailto:<?= e(setting('email')) ?>"><?= e(setting('email')) ?></a></div></li>
          <li><?= icon('clock', 'icon', 22) ?><div><strong>Office hours</strong>
            <dl class="hours"><?php foreach ($hrs as [$d, $h]): ?><div><dt><?= e($d) ?></dt><dd><?= e($h) ?></dd></div><?php endforeach; ?></dl></div></li>
          <?php if (setting('linkedin_url') !== ''): ?>
            <li><?= icon('linkedin', 'icon', 22) ?><div><strong>LinkedIn</strong><a href="<?= e(setting('linkedin_url')) ?>" target="_blank" rel="noopener noreferrer">CA <?= e(setting('ca_name')) ?></a></div></li>
          <?php endif; ?>
        </ul>
        <div class="btn-row">
          <a class="btn btn-primary" href="<?= e(tel_link(setting('phone'))) ?>"><?= icon('phone', 'icon', 18) ?> Call Now</a>
          <a class="btn btn-wa" href="<?= e(wa_link(setting('whatsapp_1'), $waText)) ?>" target="_blank" rel="noopener noreferrer"><?= icon('whatsapp', 'icon', 18) ?> WhatsApp</a>
          <a class="btn btn-outline" href="mailto:<?= e(setting('email')) ?>"><?= icon('mail', 'icon', 18) ?> Email</a>
          <a class="btn btn-outline" href="<?= e($directions) ?>" target="_blank" rel="noopener noreferrer"><?= icon('pin', 'icon', 18) ?> Get Directions</a>
        </div>
      </div>
    </div>

    <div class="contact-form-wrap">
      <?php if ($done): ?>
        <div class="card success-card" role="status">
          <span class="success-icon"><?= icon('check', 'icon', 32) ?></span>
          <h2>Thank you. Your enquiry has been sent.</h2>
          <p>We have received your message and will get back to you during office hours.</p>
          <div class="btn-row"><a class="btn btn-primary" href="<?= e(url('')) ?>">Back to Home</a>
            <a class="btn btn-outline" href="<?= e(url('book-consultation')) ?>">Book a Consultation</a></div>
        </div>
      <?php else: ?>
        <form class="form card" method="post" action="<?= e(url('contact')) ?>" novalidate data-form="contact">
          <?= csrf_field() ?><?= spam_fields() ?>
          <h2>Send an enquiry</h2>
          <p class="muted">Fields marked <span class="req">*</span> are required.</p>
          <div class="field-row">
            <?php partial('field', ['name' => 'name', 'label' => 'Full name', 'required' => true, 'maxlength' => 120, 'autocomplete' => 'name']); ?>
            <?php partial('field', ['name' => 'email', 'label' => 'Email address', 'type' => 'email', 'required' => true, 'maxlength' => 190, 'autocomplete' => 'email']); ?>
          </div>
          <div class="field-row">
            <?php partial('field', ['name' => 'phone', 'label' => 'Phone number', 'type' => 'tel', 'maxlength' => 30, 'autocomplete' => 'tel']); ?>
            <?php partial('field', ['name' => 'service', 'label' => 'Service interested in', 'type' => 'select', 'options' => $opts, 'value' => $prefService]); ?>
          </div>
          <?php partial('field', ['name' => 'subject', 'label' => 'Subject', 'required' => true, 'maxlength' => 200, 'value' => $prefService !== '' ? 'Enquiry: ' . $prefService : '']); ?>
          <?php partial('field', ['name' => 'message', 'label' => 'Message', 'type' => 'textarea', 'required' => true, 'maxlength' => 4000, 'rows' => 6]); ?>
          <button class="btn btn-primary btn-lg" type="submit" data-loading="Sending…">Send Enquiry</button>
          <p class="fine">Please do not include confidential account numbers or passwords.</p>
        </form>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="map-section" aria-label="Office location map">
  <iframe title="Map showing the location of <?= e($firm) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
    src="https://www.google.com/maps?q=<?= $mapQ ?>&amp;output=embed" allowfullscreen></iframe>
  <div class="container map-links">
    <a href="<?= e(setting('maps_url')) ?>" target="_blank" rel="noopener noreferrer">Open in Google Maps <?= icon('external', 'icon', 16) ?></a>
  </div>
</section>
