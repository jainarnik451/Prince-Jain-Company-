<p><a href="<?= e(url('admin/legal')) ?>">&larr; All legal pages</a></p>
<section class="panel">
  <div class="alert alert-info">Template text only. The Chartered Accountant must review, complete and approve this content before publication. Do not publish statements you have not verified.</div>
  <form method="post" action="<?= e(url('admin/legal/' . $slug)) ?>" class="form">
    <?= csrf_field() ?>
    <div class="field"><label for="body">Page text</label><textarea id="body" name="body" rows="26" class="mono"><?= e($_SESSION['_old']['body'] ?? ($r['body'] ?? '')) ?></textarea>
      <p class="help">Formatting: <code>## Heading</code>, <code>- bullet</code>, <code>**bold**</code>, <code>[text](https://…)</code>.</p></div>
    <label class="checkbox"><input type="checkbox" name="published" value="1" <?= !empty($r['published']) ? 'checked' : '' ?>> <span>Published on the website</span></label>
    <button class="btn btn-primary" type="submit">Save</button>
    <?php if (!empty($r['published'])): ?><a class="btn btn-outline" href="<?= e(url($slug)) ?>" target="_blank" rel="noopener">View page</a><?php endif; ?>
  </form>
</section>
