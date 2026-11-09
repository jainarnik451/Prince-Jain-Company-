<p><a class="btn btn-primary" href="<?= e(url('admin/articles/new')) ?>">+ New article</a></p>
<?php if (!$rows): ?>
  <div class="panel"><p class="muted">No articles yet. The public Insights page shows a friendly placeholder until you publish your first article.</p></div>
<?php else: ?>
<div class="panel flush"><div class="table-wrap"><table class="table">
  <thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Published</th><th>Updated</th><th>Actions</th></tr></thead>
  <tbody>
  <?php foreach ($rows as $r): ?>
    <tr>
      <td><a href="<?= e(url('admin/articles/' . $r['id'])) ?>"><?= e($r['title']) ?></a><?= $r['featured'] ? ' <span class="pill s-approved">Featured</span>' : '' ?></td>
      <td><?= e($r['category']) ?></td>
      <td><span class="pill s-<?= $r['status'] === 'published' ? 'approved' : 'pending' ?>"><?= $r['status'] === 'published' ? 'Published' : 'Draft' ?></span></td>
      <td><?= e(fmt_date($r['published_at'])) ?></td>
      <td><?= e(fmt_date($r['updated_at'])) ?></td>
      <td class="actions">
        <a class="btn btn-sm btn-outline" href="<?= e(url('admin/articles/' . $r['id'])) ?>">Edit</a>
        <?php if ($r['status'] === 'published'): ?><a class="btn btn-sm btn-outline" href="<?= e(url('insights/' . $r['slug'])) ?>" target="_blank" rel="noopener">View</a><?php endif; ?>
        <form method="post" action="<?= e(url('admin/articles/' . $r['id'] . '/toggle')) ?>"><?= csrf_field() ?><button class="btn btn-sm btn-outline" type="submit"><?= $r['status'] === 'published' ? 'Unpublish' : 'Publish' ?></button></form>
        <form method="post" action="<?= e(url('admin/articles/' . $r['id'] . '/delete')) ?>" data-confirm="Delete this article permanently?"><?= csrf_field() ?><button class="btn btn-sm btn-danger-outline" type="submit">Delete</button></form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody></table></div></div>
<?php endif; ?>
