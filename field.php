<?php
/**
 * Generic form field.
 * Params: name, label, type (text|email|tel|date|textarea|select), required, options (for select, [value=>label]),
 * placeholder, maxlength, rows, autocomplete, help, value (default), attrs (raw attribute string)
 */
$type = $type ?? 'text';
$id = 'f-' . $name . (isset($idsuffix) ? '-' . $idsuffix : '');
$cur = old_raw($name, $value ?? '');
$cur = is_string($cur) ? $cur : '';
$req = !empty($required);
$err = has_error($name);
$common = ' id="' . e($id) . '" name="' . e($name) . '"' . ($req ? ' required' : '')
    . (!empty($maxlength) ? ' maxlength="' . (int)$maxlength . '"' : '')
    . (!empty($autocomplete) ? ' autocomplete="' . e($autocomplete) . '"' : '')
    . (!empty($placeholder) ? ' placeholder="' . e($placeholder) . '"' : '')
    . ($err ? ' aria-invalid="true" aria-describedby="' . e($id) . '-err"' : '')
    . ' ' . ($attrs ?? '');
?>
<div class="field<?= $err ? ' has-error' : '' ?>">
  <label for="<?= e($id) ?>"><?= e($label) ?><?= $req ? ' <span class="req" aria-hidden="true">*</span>' : '' ?></label>
  <?php if ($type === 'textarea'): ?>
    <textarea<?= $common ?> rows="<?= (int)($rows ?? 5) ?>"><?= e($cur) ?></textarea>
  <?php elseif ($type === 'select'): ?>
    <select<?= $common ?>>
      <?php foreach ($options as $v => $l): ?>
        <?php if (is_array($l)): ?>
          <optgroup label="<?= e($v) ?>"><?php foreach ($l as $v2 => $l2): ?><option value="<?= e($v2) ?>"<?= (string)$cur === (string)$v2 ? ' selected' : '' ?>><?= e($l2) ?></option><?php endforeach; ?></optgroup>
        <?php else: ?>
          <option value="<?= e($v) ?>"<?= (string)$cur === (string)$v ? ' selected' : '' ?>><?= e($l) ?></option>
        <?php endif; ?>
      <?php endforeach; ?>
    </select>
  <?php else: ?>
    <input type="<?= e($type) ?>" value="<?= e($cur) ?>"<?= $common ?>>
  <?php endif; ?>
  <?php if (!empty($help)): ?><p class="help"><?= e($help) ?></p><?php endif; ?>
  <?php if ($err): ?><p class="field-error" id="<?= e($id) ?>-err" role="alert"><?= e($_SESSION['_errors'][$name]) ?></p><?php endif; ?>
</div>
