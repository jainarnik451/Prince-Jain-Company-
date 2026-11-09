<?php
$v = fn($k, $d = '') => $_SESSION['_old'][$k] ?? ($a[$k] ?? $d);
$id = $a['id'] ?? 0;
$published = ($a['status'] ?? '') === 'published';
$pubDate = $v('published_at', '') ? substr((string)$v('published_at'), 0, 10) : date('Y-m-d');
?>
<p><a href="<?= e(url('admin/articles')) ?>">&larr; All articles</a></p>
<form method="post" action="<?= e(url('admin/articles/save')) ?>" enctype="multipart/form-data" class="form article-form">
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= (int)$id ?>">
  <div class="two-col wide-left">
    <section class="panel">
      <div class="field"><label for="title">Title *</label><input id="title" name="title" required maxlength="200" value="<?= e($v('title')) ?>"></div>
      <div class="field"><label for="excerpt">Short summary</label><textarea id="excerpt" name="excerpt" rows="2" maxlength="420"><?= e($v('excerpt')) ?></textarea><p class="help">Shown on article cards and in search results.</p></div>
      <div class="field"><label for="body">Article text</label><textarea id="body" name="body" rows="22" class="mono"><?= e($v('body')) ?></textarea>
        <p class="help">Formatting: <code>## Heading</code>, <code>### Subheading</code>, <code>**bold**</code>, <code>*italic*</code>, <code>- bullet</code>, <code>1. numbered</code>, <code>&gt; quote</code>, <code>[link text](https://…)</code>. Blank line between paragraphs.</p></div>
    </section>
    <aside class="panel">
      <h2>Publish</h2>
      <p>Status: <span class="pill s-<?= $published ? 'approved' : 'pending' ?>"><?= $published ? 'Published' : 'Draft' ?></span></p>
      <div class="field"><label for="published_at">Publication date</label><input id="published_at" name="published_at" type="date" value="<?= e($pubDate) ?>"><p class="help">A future date schedules the article.</p></div>
      <div class="quick-actions">
        <button class="btn btn-outline" type="submit" name="action" value="draft">Save draft</button>
        <button class="btn btn-primary" type="submit" name="action" value="publish"><?= $published ? 'Update &amp; keep published' : 'Publish' ?></button>
        <?php if ($published): ?><button class="btn btn-outline" type="submit" name="action" value="unpublish">Unpublish</button><?php endif; ?>
      </div>
      <hr>
      <div class="field"><label for="category">Category</label>
        <select id="category" name="category"><option value="">None</option><?php foreach ($cats as $c): ?><option<?= $v('category') === $c ? ' selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?></select></div>
      <label class="checkbox"><input type="checkbox" name="featured" value="1" <?= $v('featured') ? 'checked' : '' ?>> <span>Featured article</span></label>
      <div class="field"><label for="author">Author</label><input id="author" name="author" maxlength="120" value="<?= e($v('author', 'CA ' . setting('ca_name'))) ?>"></div>
      <hr>
      <div class="field"><label for="image">Thumbnail / cover image</label>
        <?php if (!empty($a['image'])): ?><img class="thumb-prev" src="<?= e(img_src($a['image'])) ?>" alt=""><label class="checkbox"><input type="checkbox" name="remove_image" value="1"> <span>Remove current image</span></label><?php endif; ?>
        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp"><p class="help">JPG, PNG or WebP, up to 5 MB. 16:9 works best.</p></div>
      <div class="field"><label for="image_alt">Image description (for accessibility)</label><input id="image_alt" name="image_alt" maxlength="200" value="<?= e($v('image_alt')) ?>"></div>
      <hr>
      <details><summary>SEO (optional)</summary>
        <div class="field"><label for="slug">URL slug</label><input id="slug" name="slug" maxlength="120" value="<?= e($v('slug')) ?>"><p class="help">Leave blank to generate from the title.</p></div>
        <div class="field"><label for="seo_title">SEO title</label><input id="seo_title" name="seo_title" maxlength="200" value="<?= e($v('seo_title')) ?>"></div>
        <div class="field"><label for="seo_description">Meta description</label><textarea id="seo_description" name="seo_description" rows="3" maxlength="320"><?= e($v('seo_description')) ?></textarea></div>
      </details>
    </aside>
  </div>
</form>
