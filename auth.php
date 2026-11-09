<?php
declare(strict_types=1);

const ADMIN_IDLE_SECONDS = 7200;

/** Capability map: which roles may do what. */
function role_can(string $role, string $cap): bool
{
    $map = [
        'admin' => ['content', 'inbox', 'blog', 'users', 'settings'],
        'staff' => ['inbox'],
    ];
    return in_array($cap, $map[$role] ?? [], true);
}

function current_user(): ?array
{
    static $cache = false;
    if ($cache !== false) {
        return $cache;
    }
    $id = (int)($_SESSION['uid'] ?? 0);
    if ($id <= 0) {
        return $cache = null;
    }
    $last = (int)($_SESSION['last_seen'] ?? 0);
    if ($last && time() - $last > ADMIN_IDLE_SECONDS) {
        unset($_SESSION['uid'], $_SESSION['last_seen']);
        return $cache = null;
    }
    $u = db_one('SELECT id, name, email, role, active FROM users WHERE id = ?', [$id]);
    if (!$u || !(int)$u['active']) {
        unset($_SESSION['uid'], $_SESSION['last_seen']);
        return $cache = null;
    }
    $_SESSION['last_seen'] = time();
    return $cache = $u;
}

function can(string $cap): bool
{
    $u = current_user();
    return $u !== null && role_can($u['role'], $cap);
}

function require_login(): array
{
    $u = current_user();
    if (!$u) {
        flash('info', 'Please sign in to continue.');
        redirect('admin/login');
    }
    return $u;
}

function require_cap(string $cap): array
{
    $u = require_login();
    if (!role_can($u['role'], $cap)) {
        http_error(403, 'Your account does not have permission to open this section.');
    }
    return $u;
}

function login_attempt(string $email, string $password): bool
{
    $bucket = 'login';
    if (rate_count($bucket, 900) >= 6) {
        flash('error', 'Too many failed sign-in attempts. Please wait 15 minutes and try again.');
        return false;
    }
    $u = db_one('SELECT * FROM users WHERE email = ?', [strtolower($email)]);
    // Always run a hash check to keep timing similar for unknown users.
    $hash = $u['password_hash'] ?? password_hash('placeholder', PASSWORD_DEFAULT);
    $ok = password_verify($password, $hash) && $u && (int)$u['active'] === 1;
    if (!$ok) {
        rate_hit($bucket);
        flash('error', 'Incorrect email or password.');
        return false;
    }
    if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
        db_update('users', ['password_hash' => password_hash($password, PASSWORD_DEFAULT)], 'id = ?', [$u['id']]);
    }
    rate_clear($bucket);
    session_regenerate_id(true);
    $_SESSION['uid'] = (int)$u['id'];
    $_SESSION['last_seen'] = time();
    $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    db_update('users', ['last_login' => now()], 'id = ?', [$u['id']]);
    return true;
}

function logout_now(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'] ?? '', (bool)$p['secure'], true);
    }
    session_destroy();
}

function password_problem(string $pw): ?string
{
    if (strlen($pw) < 10) {
        return 'Password must be at least 10 characters long.';
    }
    if (!preg_match('/[A-Za-z]/', $pw) || !preg_match('/[0-9]/', $pw)) {
        return 'Password must contain both letters and numbers.';
    }
    return null;
}
