<?php
declare(strict_types=1);

/**
 * Front controller. Every request that is not a real file is routed here.
 * Works whether the "app" folder sits next to this folder (recommended on GoDaddy:
 * app/ beside public_html/) or inside it.
 */
define('PUBLIC_DIR', __DIR__);

$candidates = [__DIR__ . '/../app/bootstrap.php', __DIR__ . '/app/bootstrap.php'];
$boot = null;
foreach ($candidates as $c) {
    if (is_file($c)) {
        $boot = $c;
        break;
    }
}
if ($boot === null) {
    http_response_code(500);
    echo 'Application files not found. Please check the deployment instructions in README.md.';
    exit;
}
require $boot;
require APP_DIR . '/controllers/public.php';
require APP_DIR . '/controllers/admin.php';
require APP_DIR . '/router.php';

dispatch();
