<?php unset($_SESSION['_old'], $_SESSION['_errors']); ?><!doctype html>
<html lang="en-IN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'Set up') ?></title>
<meta name="robots" content="noindex, nofollow">
<link rel="icon" href="<?= e(url('favicon.svg')) ?>" type="image/svg+xml">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&amp;family=Manrope:wght@600;700;800&amp;display=swap">
<link rel="stylesheet" href="<?= e(asset('assets/css/admin.css')) ?>">
<script src="<?= e(asset('assets/js/admin.js')) ?>" defer></script>
</head>
<body class="bare">
<main class="bare-wrap">
  <?php foreach (flashes() as $f): ?><div class="alert alert-<?= e($f['type']) ?>" role="alert"><?= e($f['msg']) ?></div><?php endforeach; ?>
  <?= $content ?>
</main>
</body>
</html>
