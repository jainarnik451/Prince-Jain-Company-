<?php
declare(strict_types=1);

function app_secret(): string
{
    $s = env('APP_SECRET');
    if ($s) {
        return (string)$s;
    }
    // Fallback so the site never runs with an empty key; set APP_SECRET in .env for production.
    return hash('sha256', ROOT_DIR . '|' . (env('DB_NAME', '') . env('DB_USER', '')) . '|pjco-fallback');
}

function security_headers(): void
{
    if (headers_sent()) {
        return;
    }
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    header("Content-Security-Policy: default-src 'self'; img-src 'self' data: https:; "
        . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; "
        . "script-src 'self'; frame-src https://www.google.com https://maps.google.com; "
        . "form-action 'self'; base-uri 'self'; frame-ancestors 'self'; object-src 'none'");
    if (is_https()) {
        header('Strict-Transport-Security: max-age=31536000');
    }
}

function session_boot(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $name = preg_replace('/[^A-Za-z0-9_]/', '', (string)env('SESSION_NAME', 'pjco_session'));
    session_name($name ?: 'pjco_session');
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => base_path() ?: '/',
        'secure' => is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

/* ---------- CSRF ---------- */

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): void
{
    $sent = (string)($_POST['_csrf'] ?? '');
    if ($sent === '' || !hash_equals(csrf_token(), $sent)) {
        http_error(419, 'Your session expired. Please go back, refresh the page and try again.');
    }
}

/* ---------- Spam protection (honeypot + timing + rate limit) ---------- */

function ip_hash(): string
{
    return hash_hmac('sha256', (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown'), app_secret());
}

function spam_fields(): string
{
    $ts = (string)time();
    $sig = hash_hmac('sha256', $ts, app_secret());
    return '<div class="hp-field" aria-hidden="true"><label>Leave this field empty'
        . '<input type="text" name="website_url" tabindex="-1" autocomplete="off"></label></div>'
        . '<input type="hidden" name="_ts" value="' . e($ts . '.' . $sig) . '">';
}

/**
 * Returns 'ok', 'bot' (silently pretend success), or an error string for the user.
 */
function spam_check(string $bucket, int $maxPerHour = 6): string
{
    if (trim((string)($_POST['website_url'] ?? '')) !== '') {
        return 'bot';
    }
    $parts = explode('.', (string)($_POST['_ts'] ?? ''), 2);
    if (count($parts) !== 2 || !hash_equals(hash_hmac('sha256', $parts[0], app_secret()), $parts[1])) {
        return 'Your session expired. Please refresh the page and try again.';
    }
    $age = time() - (int)$parts[0];
    if ($age < 3) {
        return 'That was very quick. Please check the form and submit again.';
    }
    if ($age > 6 * 3600) {
        return 'This form has expired. Please refresh the page and try again.';
    }
    if (rate_count($bucket, 3600) >= $maxPerHour) {
        return 'Too many submissions from your connection. Please try again later or contact us by phone.';
    }
    return 'ok';
}

function rate_hit(string $bucket): void
{
    db_insert('rate_limits', ['bucket' => $bucket, 'ip_hash' => ip_hash(), 'created_at' => now()]);
    if (random_int(1, 40) === 1) {
        db_q('DELETE FROM rate_limits WHERE created_at < ?', [date('Y-m-d H:i:s', time() - 86400)]);
    }
}

function rate_count(string $bucket, int $windowSeconds): int
{
    return (int)db_val(
        'SELECT COUNT(*) FROM rate_limits WHERE bucket = ? AND ip_hash = ? AND created_at > ?',
        [$bucket, ip_hash(), date('Y-m-d H:i:s', time() - $windowSeconds)]
    );
}

function rate_clear(string $bucket): void
{
    db_q('DELETE FROM rate_limits WHERE bucket = ? AND ip_hash = ?', [$bucket, ip_hash()]);
}

/* ---------- Input cleaning / validation ---------- */

function post_str(string $key, int $max = 255): string
{
    $v = $_POST[$key] ?? '';
    if (!is_string($v)) {
        return '';
    }
    $v = trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $v));
    return mb_substr($v, 0, $max);
}

function post_text(string $key, int $max = 5000): string
{
    $v = $_POST[$key] ?? '';
    if (!is_string($v)) {
        return '';
    }
    $v = str_replace(["\r\n", "\r"], "\n", $v);
    $v = trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $v));
    return mb_substr($v, 0, $max);
}

function valid_email(string $v): bool
{
    return $v !== '' && strlen($v) <= 190 && filter_var($v, FILTER_VALIDATE_EMAIL) !== false;
}

function valid_phone(string $v): bool
{
    return (bool)preg_match('/^\+?[0-9][0-9\s\-]{7,17}$/', $v) && strlen(digits($v)) >= 8 && strlen(digits($v)) <= 15;
}

/** Block header injection in values that may reach mail headers. */
function no_newlines(string $v): string
{
    return trim(str_replace(["\r", "\n", "%0a", "%0d"], ' ', $v));
}

function notify_admin(string $subject, string $body, ?string $replyTo = null): void
{
    $to = env('NOTIFY_EMAIL');
    if (!$to) {
        return;
    }
    $from = env('MAIL_FROM', 'no-reply@' . preg_replace('/^www\./', '', parse_url(site_origin(), PHP_URL_HOST) ?: 'localhost'));
    $headers = 'From: Prince Jain & Company <' . no_newlines((string)$from) . ">\r\n"
        . 'Content-Type: text/plain; charset=UTF-8';
    if ($replyTo && valid_email($replyTo)) {
        $headers .= "\r\nReply-To: " . no_newlines($replyTo);
    }
    try {
        @mail((string)$to, no_newlines($subject), $body, $headers);
    } catch (Throwable $e) {
        error_log('Mail failed: ' . $e->getMessage());
    }
}
