<?php
declare(strict_types=1);

/** Minimal .env loader (no dependencies). */
function env_store(?array $set = null): array
{
    static $store = [];
    if ($set !== null) {
        $store = $set;
    }
    return $store;
}

function env_load(string $file): void
{
    $vars = [];
    if (is_readable($file)) {
        foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) {
                continue;
            }
            [$k, $v] = explode('=', $line, 2);
            $k = trim($k);
            $v = trim($v);
            if (strlen($v) >= 2 && ($v[0] === '"' || $v[0] === "'") && substr($v, -1) === $v[0]) {
                $v = substr($v, 1, -1);
            }
            $vars[$k] = $v;
        }
    }
    env_store($vars);
}

function env(string $key, $default = null)
{
    $store = env_store();
    if (array_key_exists($key, $store) && $store[$key] !== '') {
        return $store[$key];
    }
    $v = getenv($key);
    return ($v !== false && $v !== '') ? $v : $default;
}
