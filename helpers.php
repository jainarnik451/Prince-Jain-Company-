<?php
declare(strict_types=1);

/* ---------- Escaping & URLs ---------- */

function e($v): string
{
    return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function base_path(): string
{
    static $bp = null;
    if ($bp !== null) {
        return $bp;
    }
    $override = env('APP_BASE_PATH');
    if ($override !== null) {
        return $bp = rtrim((string)$override, '/');
    }
    $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    return $bp = ($dir === '/' || $dir === '.') ? '' : rtrim($dir, '/');
}

function url(string $path = ''): string
{
    return base_path() . '/' . ltrim($path, '/');
}

function is_https(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
        || (int)($_SERVER['SERVER_PORT'] ?? 0) === 443;
}

function site_origin(): string
{
    $cfg = env('APP_URL');
    if ($cfg) {
        return rtrim((string)$cfg, '/');
    }
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return (is_https() ? 'https' : 'http') . '://' . preg_replace('/[^A-Za-z0-9.:\-]/', '', $host) . base_path();
}

function abs_url(string $path = ''): string
{
    return site_origin() . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    $file = PUBLIC_DIR . '/' . ltrim($path, '/');
    $v = is_file($file) ? filemtime($file) : 1;
    return url($path) . '?v=' . $v;
}

function img_src(?string $path): string
{
    $path = (string)$path;
    if ($path === '') {
        return '';
    }
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }
    return url($path);
}

function redirect(string $path, int $code = 302): never
{
    header('Location: ' . (preg_match('#^https?://#', $path) ? $path : url($path)), true, $code);
    exit;
}

/* ---------- Flash, old input ---------- */

function flash(string $type, string $msg): void
{
    $_SESSION['_flash'][] = ['type' => $type, 'msg' => $msg];
}

function flashes(): array
{
    $f = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);
    return $f;
}

function old(string $key, $default = ''): string
{
    return e($_SESSION['_old'][$key] ?? $default);
}

function old_raw(string $key, $default = '')
{
    return $_SESSION['_old'][$key] ?? $default;
}

function keep_old(array $data): void
{
    $_SESSION['_old'] = $data;
}

function clear_old(): void
{
    unset($_SESSION['_old'], $_SESSION['_errors']);
}

function set_errors(array $errors): void
{
    $_SESSION['_errors'] = $errors;
}

function field_error(string $key): string
{
    $err = $_SESSION['_errors'][$key] ?? '';
    return $err !== '' ? '<p class="field-error" role="alert">' . e($err) . '</p>' : '';
}

function has_error(string $key): bool
{
    return !empty($_SESSION['_errors'][$key]);
}

/* ---------- Strings & formats ---------- */

function slugify(string $s): string
{
    $s = strtolower(trim($s));
    $s = preg_replace('/&/', ' and ', $s);
    $s = preg_replace('/[^a-z0-9]+/', '-', $s);
    $s = trim((string)$s, '-');
    return $s !== '' ? substr($s, 0, 120) : 'item';
}

function unique_slug(string $table, string $base, ?int $ignoreId = null): string
{
    $slug = slugify($base);
    $try = $slug;
    $n = 2;
    while (true) {
        $row = db_one("SELECT id FROM {$table} WHERE slug = ?", [$try]);
        if (!$row || ($ignoreId !== null && (int)$row['id'] === $ignoreId)) {
            return $try;
        }
        $try = $slug . '-' . $n++;
    }
}

function str_limit(string $s, int $len): string
{
    $s = trim(preg_replace('/\s+/', ' ', $s));
    if (mb_strlen($s) <= $len) {
        return $s;
    }
    return rtrim(mb_substr($s, 0, $len - 1), " ,.;:-") . '…';
}

function fmt_date(?string $d, bool $withTime = false): string
{
    if (!$d) {
        return '';
    }
    $t = strtotime($d);
    if (!$t) {
        return '';
    }
    return $withTime ? date('d M Y, h:i A', $t) : date('d M Y', $t);
}

function digits(string $s): string
{
    return preg_replace('/\D+/', '', $s);
}

/** Convert a phone number to international digits for tel:/wa.me links (India default). */
function intl_digits(string $phone): string
{
    $d = digits($phone);
    if (strlen($d) === 10) {
        return '91' . $d;
    }
    if (strlen($d) === 11 && $d[0] === '0') {
        return '91' . substr($d, 1);
    }
    return $d;
}

function tel_link(string $phone): string
{
    return 'tel:+' . intl_digits($phone);
}

function wa_link(string $phone, string $text = ''): string
{
    $u = 'https://wa.me/' . intl_digits($phone);
    return $text !== '' ? $u . '?text=' . rawurlencode($text) : $u;
}

function time_label(string $hhmm): string
{
    $t = strtotime('2000-01-01 ' . $hhmm);
    return $t ? date('g:i A', $t) : $hhmm;
}

function json_ld(array $data): string
{
    return '<script type="application/ld+json">' .
        json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) .
        '</script>';
}

/* ---------- Views ---------- */

function render(string $__view, array $__data = [], string $__layout = 'layout'): void
{
    extract($__data, EXTR_SKIP);
    unset($__data);
    ob_start();
    require APP_DIR . '/views/' . $__view . '.php';
    $content = ob_get_clean();
    require APP_DIR . '/views/' . $__layout . '.php';
}

function http_error(int $code, string $message = ''): never
{
    http_response_code($code);
    if ($code === 404) {
        render('pages/404', ['title' => 'Page not found', 'noindex' => true, 'description' => '']);
    } else {
        render('pages/error', [
            'title' => 'Something went wrong',
            'noindex' => true,
            'description' => '',
            'code' => $code,
            'message' => $message ?: 'An unexpected error occurred. Please try again in a moment.',
        ]);
    }
    exit;
}

/* ---------- Pagination ---------- */

function paginate_links(int $page, int $pages, string $baseUrl, array $query = []): string
{
    if ($pages <= 1) {
        return '';
    }
    $out = '<nav class="pagination" aria-label="Pagination">';
    for ($i = 1; $i <= $pages; $i++) {
        $q = $query;
        if ($i > 1) {
            $q['page'] = $i;
        }
        $href = $baseUrl . ($q ? '?' . http_build_query($q) : '');
        $cur = $i === $page ? ' aria-current="page" class="is-current"' : '';
        $out .= '<a href="' . e($href) . '"' . $cur . '>' . $i . '</a>';
    }
    return $out . '</nav>';
}
