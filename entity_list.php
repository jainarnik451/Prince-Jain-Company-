<p><a class="btn btn-primary" href="<?= e(url('admin/' . $e . '/new')) ?>">+ Add <?= e($d['singular']) ?></a></p>
<?php if (!$rows): ?><div class="panel"><p class="muted">Nothing here yet.</p></div><?php else: ?>
<div class="panel flush"><div class="table-wrap"><table class="table">
  <thead><tr><?php foreach ($d['list'] as $col): ?><th><?= e(ucfirst($col)) ?></th><?php endforeach; ?><th>Order</th><th>Visibility</th><th>Actions</th></tr></thead>
  <tbody>
  <?php foreach ($rows as $r): ?>
    <tr>
      <?php foreach ($d['list'] as $i => $col):
          $val = (string)$r[$col];
          if ($col === 'category') { $val = service_categories()[$val]['label'] ?? $val; }
          if ($col === 'section') { $val = info_sections()[$val][0] ?? $val; } ?>
        <td><?php if ($i === 0 || ($i === 1 && count($d['list']) === 2 && $col === 'title')): ?><a href="<?= e(url('admin/' . $e . '/' . $r['id'])) ?>"><?= e(str_limit($val, 70)) ?></a><?php else: ?><?= e(str_limit($val, 70)) ?><?php endif; ?>
          <?php if ($i === 0 && !empty($r['admin_note']) && !$r['published']): ?><br><small class="warn"><?= e($r['admin_note']) ?></small><?php endif; ?></td>
      <?php endforeach; ?>
      <td><?= (int)$r['sort_order'] ?></td>
      <td><span class="pill s-<?= $r['published'] ? 'approved' : 'pending' ?>"><?= $r['published'] ? 'Published' : 'Hidden / draft' ?></span></td>
      <td class="actions">
        <a class="btn btn-sm btn-outline" href="<?= e(url('admin/' . $e . '/' . $r['id'])) ?>">Edit</a>
        <form method="post" action="<?= e(url('admin/' . $e . '/' . $r['id'] . '/toggle')) ?>"><?= csrf_field() ?><button class="btn btn-sm btn-outline" type="submit"><?= $r['published'] ? 'Hide' : 'Publish' ?></button></form>
        <form method="post" action="<?= e(url('admin/' . $e . '/' . $r['id'] . '/delete')) ?>" data-confirm="Delete this <?= e($d['singular']) ?> permanently?"><?= csrf_field() ?><button class="btn btn-sm btn-danger-outline" type="submit">Delete</button></form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody></table></div></div>
<?php endif; ?>
