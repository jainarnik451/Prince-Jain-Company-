<?php
$firm = setting('firm_name');
partial('page_head', ['heading' => 'Book a Consultation', 'crumb' => 'Book a Consultation', 'eyebrow' => 'Appointments',
    'lead' => 'Request a consultation with CA ' . setting('ca_name') . '. Your request is confirmed only after the firm reviews and approves it.']);
$opts = ['' => 'Select a service'];
$opts['Not sure / General consultation'] = 'Not sure / General consultation';
foreach (service_titles() as $t) {
    $opts[$t] = $t;
}
$waText = 'Hello ' . $firm . ', I would like to request a consultation.';
$hrs = office_hours_rows();
?>
<section class="section">
  <div class="container form-layout">
    <div class="form-main">
      <?php if ($done): ?>
        <div class="card success-card" role="status">
          <span class="success-icon"><?= icon('check', 'icon', 32) ?></span>
          <h2>Thank you. Your request has been received.</h2>
          <p>Your consultation request has been sent to <?= e($firm) ?> for review. <strong>It is not confirmed yet</strong>; the firm will contact you by phone, WhatsApp or email to confirm or suggest another time.</p>
          <p class="ref">Reference: <strong><?= e($done['ref'] ?? '') ?></strong></p>
          <?php if (!empty($done['date'])): ?><p class="muted">Requested: <?= e(fmt_date($done['date'])) ?>, <?= e($done['time'] ?? '') ?> (<?= e($done['mode'] ?? '') ?>)</p><?php endif; ?>
          <div class="btn-row">
            <a class="btn btn-primary" href="<?= e(url('')) ?>">Back to Home</a>
            <a class="btn btn-outline" href="<?= e(wa_link(setting('whatsapp_1'), $waText . ' My reference is ' . ($done['ref'] ?? '') . '.')) ?>" target="_blank" rel="noopener noreferrer">Message us on WhatsApp</a>
          </div>
        </div>
      <?php else: ?>
        <form class="form card" method="post" action="<?= e(url('book-consultation')) ?>" novalidate data-form="book"
              data-slots='<?= e(json_encode($slots)) ?>'>
          <?= csrf_field() ?><?= spam_fields() ?>
          <h2>Consultation request</h2>
          <p class="muted">Fields marked <span class="req">*</span> are required.</p>
          <div class="field-row">
            <?php partial('field', ['name' => 'name', 'label' => 'Full name', 'required' => true, 'maxlength' => 120, 'autocomplete' => 'name']); ?>
            <?php partial('field', ['name' => 'phone', 'label' => 'Phone number', 'type' => 'tel', 'required' => true, 'maxlength' => 30, 'autocomplete' => 'tel', 'placeholder' => '+91 98765 43210']); ?>
          </div>
          <?php partial('field', ['name' => 'email', 'label' => 'Email address', 'type' => 'email', 'required' => true, 'maxlength' => 190, 'autocomplete' => 'email']); ?>
          <?php partial('field', ['name' => 'service', 'label' => 'Type of service required', 'type' => 'select', 'required' => true, 'options' => $opts, 'value' => $prefService]); ?>
          <div class="field-row">
            <?php partial('field', ['name' => 'pref_date', 'label' => 'Preferred consultation date', 'type' => 'date', 'required' => true,
                'attrs' => 'min="' . e($minDate) . '" max="' . e($maxDate) . '"', 'help' => 'The office is closed on Sundays.']); ?>
            <?php
              $timeOpts = ['' => 'Select a time'];
              foreach ($slots['wk'] as [$v, $l]) { $timeOpts[$v] = $l; }
              partial('field', ['name' => 'pref_time', 'label' => 'Preferred time', 'type' => 'select', 'required' => true, 'options' => $timeOpts]);
            ?>
          </div>
          <fieldset class="field radio-group<?= has_error('mode') ? ' has-error' : '' ?>">
            <legend>Consultation type <span class="req" aria-hidden="true">*</span></legend>
            <?php $m = old_raw('mode', 'In-person'); ?>
            <label class="radio"><input type="radio" name="mode" value="In-person" <?= $m === 'In-person' ? 'checked' : '' ?>> In-person (Thane office)</label>
            <label class="radio"><input type="radio" name="mode" value="Online" <?= $m === 'Online' ? 'checked' : '' ?>> Online</label>
            <?= field_error('mode') ?>
          </fieldset>
          <?php partial('field', ['name' => 'message', 'label' => 'Additional message', 'type' => 'textarea', 'maxlength' => 2000, 'rows' => 4, 'help' => 'Optional. Briefly describe what you would like to discuss.']); ?>
          <button class="btn btn-primary btn-lg" type="submit" data-loading="Sending…">Submit Request</button>
          <p class="fine">Please do not include confidential account numbers or passwords. By submitting, you agree to be contacted about this request.</p>
        </form>
      <?php endif; ?>
    </div>

    <aside class="form-aside">
      <div class="card">
        <h3>Prefer to talk now?</h3>
        <p>Reach us directly during office hours.</p>
        <div class="stack">
          <a class="btn btn-wa" href="<?= e(wa_link(setting('whatsapp_1'), $waText)) ?>" target="_blank" rel="noopener noreferrer"><?= icon('whatsapp', 'icon', 18) ?> WhatsApp</a>
          <a class="btn btn-outline" href="<?= e(tel_link(setting('phone'))) ?>"><?= icon('phone', 'icon', 18) ?> <?= e(setting('phone')) ?></a>
        </div>
      </div>
      <div class="card">
        <h3>Office hours</h3>
        <dl class="hours"><?php foreach ($hrs as [$d, $h]): ?><div><dt><?= e($d) ?></dt><dd><?= e($h) ?></dd></div><?php endforeach; ?></dl>
      </div>
    </aside>
  </div>
</section>
