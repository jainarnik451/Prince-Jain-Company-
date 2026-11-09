<?php
declare(strict_types=1);

/**
 * Prince Jain & Company — application bootstrap.
 * Loads environment, error handling, session, database and helpers.
 */

define('APP_DIR', __DIR__);
define('ROOT_DIR', dirname(__DIR__));
if (!defined('PUBLIC_DIR')) {
    define('PUBLIC_DIR', ROOT_DIR . '/public');
}

date_default_timezone_set('Asia/Kolkata');

require APP_DIR . '/lib/env.php';
env_load(ROOT_DIR . '/.env');

define('IS_LOCAL', env('APP_ENV', 'production') === 'local');

// Errors: never show details to visitors in production.
error_reporting(E_ALL);
ini_set('display_errors', IS_LOCAL ? '1' : '0');
ini_set('log_errors', '1');
$logDir = ROOT_DIR . '/storage/logs';
if (is_dir($logDir) && is_writable($logDir)) {
    ini_set('error_log', $logDir . '/app.log');
}

require APP_DIR . '/lib/helpers.php';
require APP_DIR . '/lib/db.php';
require APP_DIR . '/lib/markdown.php';
require APP_DIR . '/lib/icons.php';
require APP_DIR . '/lib/security.php';
require APP_DIR . '/lib/uploads.php';
require APP_DIR . '/lib/content.php';
require APP_DIR . '/lib/auth.php';

security_headers();
session_boot();
