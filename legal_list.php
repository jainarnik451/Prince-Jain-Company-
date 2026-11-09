<p class="muted">Each page ships as an editable template. A page can only be published once all <code><?= e(REVIEW_MARKER) ?></code> markers have been completed and removed. Until then visitors see a neutral "being prepared" notice.</p>
<div class="panel flush"><div class="table-wrap"><table class="table">
  <thead><tr><th>Page</th><th>Status</th><th>Updated</th><th></th></tr></thead><tbody>
  <?php foreach ($rows as $r): ?>
    <tr><td><a href="<?= e(url('admin/legal/' . $r['slug'])) ?>"><?= e($r['title']) ?></a></td>
      <td><?php if ($r['published']): ?><span class="pill s-approved">Published</span><?php elseif ($r['pending']): ?><span class="pill s-rejected">Needs completion</span><?php else: ?><span class="pill s-pending">Draft</span><?php endif; ?></td>
      <td><?= e(fmt_date($r['updated_at'])) ?></td>
      <td><a class="btn btn-sm btn-outline" href="<?= e(url('admin/legal/' . $r['slug'])) ?>">Edit</a></td></tr>
  <?php endforeach; ?></tbody></table></div></div>
