<?php
unset($_SESSION['_old'], $_SESSION['_errors']);
$c = counts();
$nav = $nav ?? '';
$items = [
    ['dashboard', 'admin', 'Dashboard', 'home', null, 'inbox'],
    ['appointments', 'admin/appointments', 'Appointments', 'calendar', $c['appt_pending'], 'inbox'],
    ['enquiries', 'admin/enquiries', 'Enquiries', 'mail', $c['enq_new'], 'inbox'],
    ['feedback', 'admin/feedback', 'Feedback', 'star', $c['fb_pending'], 'inbox'],
    ['articles', 'admin/articles', 'Articles', 'newspaper', null, 'blog'],
    ['services', 'admin/services', 'Services', 'briefcase', null, 'content'],
    ['expertise', 'admin/expertise', 'Expertise', 'target', null, 'content'],
    ['timeline', 'admin/timeline', 'Firm Timeline', 'clock', null, 'content'],
    ['info', 'admin/info', 'Additional Info', 'info', null, 'content'],
    ['links', 'admin/links', 'Footer Links', 'link', null, 'content'],
    ['legal', 'admin/legal', 'Legal Pages', 'file', null, 'content'],
    ['settings', 'admin/settings', 'Site Settings', 'gear', null, 'settings'],
    ['users', 'admin/users', 'Users', 'users', null, 'users'],
];
?><!doctype html>
<html lang="en-IN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> · Admin</title>
<meta name="robots" content="noindex, nofollow">
<link rel="icon" href="<?= e(url('favicon.svg')) ?>" type="image/svg+xml">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&amp;family=Manrope:wght@600;700;800&amp;display=swap">
<link rel="stylesheet" href="<?= e(asset('assets/css/admin.css')) ?>">
<script src="<?= e(asset('assets/js/admin.js')) ?>" defer></script>
</head>
<body class="admin">
<a class="skip-link" href="#content">Skip to content</a>
<aside class="side" id="side">
  <div class="side-brand"><span class="logo-mark"><?= e(setting('logo_mark', 'PJ')) ?></span><span>Admin<small><?= e(setting('firm_name')) ?></small></span></div>
  <nav aria-label="Admin">
    <ul>
      <?php foreach ($items as [$key, $path, $label, $ic, $badge, $cap]): if (!can($cap)) { continue; } ?>
        <li><a href="<?= e(url($path)) ?>"<?= $nav === $key ? ' aria-current="page"' : '' ?>><?= icon($ic, 'icon', 18) ?><span><?= e($label) ?></span><?php if ($badge): ?><b class="badge"><?= (int)$badge ?></b><?php endif; ?></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>
  <div class="side-foot">
    <a href="<?= e(url('')) ?>" target="_blank" rel="noopener">View website <?= icon('external', 'icon', 14) ?></a>
    <a href="<?= e(url('admin/account')) ?>">My account</a>
    <form method="post" action="<?= e(url('admin/logout')) ?>"><?= csrf_field() ?><button type="submit" class="linklike">Sign out</button></form>
  </div>
</aside>
<div class="main-col">
  <header class="topbar">
    <button class="side-toggle" type="button" aria-controls="side" aria-expanded="false" aria-label="Menu"><?= icon('menu', 'icon', 22) ?></button>
    <h1><?= e($title) ?></h1>
    <span class="who"><?= e($user['name'] ?? '') ?> <small><?= e($user['role'] ?? '') ?></small></span>
  </header>
  <main id="content" class="content">
    <?php foreach (flashes() as $f): ?><div class="alert alert-<?= e($f['type']) ?>" role="<?= $f['type'] === 'error' ? 'alert' : 'status' ?>"><?= e($f['msg']) ?></div><?php endforeach; ?>
    <?= $content ?>
  </main>
</div>
</body>
</html>
