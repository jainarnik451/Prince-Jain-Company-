<?php
declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;
    if ($pdo) {
        return $pdo;
    }
    $driver = strtolower((string)env('DB_DRIVER', 'mysql'));
    $opts = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    if ($driver === 'sqlite') {
        $path = (string)env('DB_SQLITE_PATH', ROOT_DIR . '/storage/site.sqlite');
        $pdo = new PDO('sqlite:' . $path, null, null, $opts);
        $pdo->exec('PRAGMA foreign_keys = ON');
    } else {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            env('DB_HOST', 'localhost'),
            (int)env('DB_PORT', 3306),
            env('DB_NAME', '')
        );
        $opts[PDO::ATTR_EMULATE_PREPARES] = false;
        $pdo = new PDO($dsn, (string)env('DB_USER', ''), (string)env('DB_PASS', ''), $opts);
        $pdo->exec("SET time_zone = '+05:30'");
    }
    return $pdo;
}

function db_driver(): string
{
    return strtolower((string)env('DB_DRIVER', 'mysql')) === 'sqlite' ? 'sqlite' : 'mysql';
}

function db_q(string $sql, array $params = []): PDOStatement
{
    $st = db()->prepare($sql);
    $st->execute(array_values($params));
    return $st;
}

function db_all(string $sql, array $params = []): array
{
    return db_q($sql, $params)->fetchAll();
}

function db_one(string $sql, array $params = []): ?array
{
    $r = db_q($sql, $params)->fetch();
    return $r === false ? null : $r;
}

function db_val(string $sql, array $params = [])
{
    $r = db_q($sql, $params)->fetchColumn();
    return $r === false ? null : $r;
}

function db_insert(string $table, array $data): int
{
    $cols = array_keys($data);
    $sql = 'INSERT INTO ' . $table . ' (' . implode(',', $cols) . ') VALUES (' . implode(',', array_fill(0, count($cols), '?')) . ')';
    db_q($sql, array_values($data));
    return (int)db()->lastInsertId();
}

function db_update(string $table, array $data, string $where, array $whereParams = []): void
{
    $set = implode(', ', array_map(fn($c) => $c . ' = ?', array_keys($data)));
    db_q('UPDATE ' . $table . ' SET ' . $set . ' WHERE ' . $where, array_merge(array_values($data), $whereParams));
}

function now(): string
{
    return date('Y-m-d H:i:s');
}

/** Whether the database has been installed (users table exists and has rows). */
function db_installed(): bool
{
    try {
        return (int)db_val('SELECT COUNT(*) FROM users') > 0;
    } catch (Throwable $e) {
        return false;
    }
}

/* ---------- Schema (portable between MySQL and SQLite) ---------- */

function schema_statements(string $driver): array
{
    $sqlite = $driver === 'sqlite';
    $pk = $sqlite ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY';
    $long = $sqlite ? 'TEXT' : 'LONGTEXT';
    $dt = $sqlite ? 'TEXT' : 'DATETIME';
    $tail = $sqlite ? '' : ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

    $t = [];
    $t[] = "CREATE TABLE IF NOT EXISTS users (
        id $pk,
        name VARCHAR(120) NOT NULL,
        email VARCHAR(190) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        role VARCHAR(20) NOT NULL DEFAULT 'staff',
        active TINYINT NOT NULL DEFAULT 1,
        last_login $dt NULL,
        created_at $dt NOT NULL
    )$tail";
    $t[] = "CREATE TABLE IF NOT EXISTS settings (
        skey VARCHAR(100) NOT NULL PRIMARY KEY,
        svalue $long NULL,
        updated_at $dt NULL
    )$tail";
    $t[] = "CREATE TABLE IF NOT EXISTS services (
        id $pk,
        category VARCHAR(40) NOT NULL,
        title VARCHAR(160) NOT NULL,
        slug VARCHAR(160) NOT NULL UNIQUE,
        short_desc VARCHAR(320) NULL,
        description $long NULL,
        who_benefits $long NULL,
        key_info $long NULL,
        icon VARCHAR(30) NULL,
        sort_order INT NOT NULL DEFAULT 0,
        published TINYINT NOT NULL DEFAULT 1,
        admin_note VARCHAR(255) NULL,
        created_at $dt NOT NULL,
        updated_at $dt NOT NULL
    )$tail";
    $t[] = "CREATE TABLE IF NOT EXISTS expertise (
        id $pk,
        title VARCHAR(140) NOT NULL,
        description $long NULL,
        icon VARCHAR(30) NULL,
        sort_order INT NOT NULL DEFAULT 0,
        published TINYINT NOT NULL DEFAULT 1,
        admin_note VARCHAR(255) NULL,
        created_at $dt NOT NULL,
        updated_at $dt NOT NULL
    )$tail";
    $t[] = "CREATE TABLE IF NOT EXISTS timeline (
        id $pk,
        year VARCHAR(30) NOT NULL,
        title VARCHAR(160) NOT NULL,
        description $long NULL,
        sort_order INT NOT NULL DEFAULT 0,
        published TINYINT NOT NULL DEFAULT 1,
        created_at $dt NOT NULL,
        updated_at $dt NOT NULL
    )$tail";
    $t[] = "CREATE TABLE IF NOT EXISTS articles (
        id $pk,
        title VARCHAR(200) NOT NULL,
        slug VARCHAR(200) NOT NULL UNIQUE,
        excerpt VARCHAR(420) NULL,
        body $long NULL,
        category VARCHAR(80) NULL,
        image VARCHAR(255) NULL,
        image_alt VARCHAR(200) NULL,
        featured TINYINT NOT NULL DEFAULT 0,
        status VARCHAR(12) NOT NULL DEFAULT 'draft',
        published_at $dt NULL,
        author VARCHAR(120) NULL,
        seo_title VARCHAR(200) NULL,
        seo_description VARCHAR(320) NULL,
        created_at $dt NOT NULL,
        updated_at $dt NOT NULL
    )$tail";
    $t[] = "CREATE TABLE IF NOT EXISTS appointments (
        id $pk,
        ref VARCHAR(24) NULL,
        name VARCHAR(120) NOT NULL,
        phone VARCHAR(30) NOT NULL,
        email VARCHAR(190) NOT NULL,
        service VARCHAR(160) NULL,
        pref_date VARCHAR(10) NOT NULL,
        pref_time VARCHAR(8) NOT NULL,
        mode VARCHAR(12) NOT NULL,
        message $long NULL,
        status VARCHAR(16) NOT NULL DEFAULT 'pending',
        is_read TINYINT NOT NULL DEFAULT 0,
        admin_notes $long NULL,
        ip_hash VARCHAR(64) NULL,
        created_at $dt NOT NULL,
        updated_at $dt NOT NULL
    )$tail";
    $t[] = "CREATE TABLE IF NOT EXISTS enquiries (
        id $pk,
        name VARCHAR(120) NOT NULL,
        email VARCHAR(190) NOT NULL,
        phone VARCHAR(30) NULL,
        subject VARCHAR(200) NOT NULL,
        service VARCHAR(160) NULL,
        message $long NOT NULL,
        status VARCHAR(16) NOT NULL DEFAULT 'new',
        is_read TINYINT NOT NULL DEFAULT 0,
        admin_notes $long NULL,
        ip_hash VARCHAR(64) NULL,
        created_at $dt NOT NULL,
        updated_at $dt NOT NULL
    )$tail";
    $t[] = "CREATE TABLE IF NOT EXISTS feedback (
        id $pk,
        name VARCHAR(120) NOT NULL,
        email VARCHAR(190) NULL,
        service VARCHAR(160) NULL,
        rating TINYINT NOT NULL,
        message $long NOT NULL,
        consent TINYINT NOT NULL DEFAULT 0,
        status VARCHAR(12) NOT NULL DEFAULT 'pending',
        ip_hash VARCHAR(64) NULL,
        created_at $dt NOT NULL,
        updated_at $dt NOT NULL
    )$tail";
    $t[] = "CREATE TABLE IF NOT EXISTS info_items (
        id $pk,
        section VARCHAR(30) NOT NULL,
        title VARCHAR(200) NOT NULL,
        body $long NULL,
        link_url VARCHAR(255) NULL,
        sort_order INT NOT NULL DEFAULT 0,
        published TINYINT NOT NULL DEFAULT 0,
        created_at $dt NOT NULL,
        updated_at $dt NOT NULL
    )$tail";
    $t[] = "CREATE TABLE IF NOT EXISTS legal_pages (
        id $pk,
        slug VARCHAR(60) NOT NULL UNIQUE,
        title VARCHAR(160) NOT NULL,
        body $long NULL,
        published TINYINT NOT NULL DEFAULT 0,
        updated_at $dt NULL
    )$tail";
    $t[] = "CREATE TABLE IF NOT EXISTS links (
        id $pk,
        grp VARCHAR(30) NOT NULL DEFAULT 'important',
        label VARCHAR(120) NOT NULL,
        url VARCHAR(255) NOT NULL,
        sort_order INT NOT NULL DEFAULT 0,
        published TINYINT NOT NULL DEFAULT 1,
        created_at $dt NOT NULL,
        updated_at $dt NOT NULL
    )$tail";
    $t[] = "CREATE TABLE IF NOT EXISTS rate_limits (
        id $pk,
        bucket VARCHAR(60) NOT NULL,
        ip_hash VARCHAR(64) NOT NULL,
        created_at $dt NOT NULL
    )$tail";
    $t[] = 'CREATE INDEX idx_articles_status ON articles (status, published_at)';
    $t[] = 'CREATE INDEX idx_services_cat ON services (category, sort_order)';
    $t[] = 'CREATE INDEX idx_appt_status ON appointments (status, created_at)';
    $t[] = 'CREATE INDEX idx_enq_status ON enquiries (status, created_at)';
    $t[] = 'CREATE INDEX idx_fb_status ON feedback (status, created_at)';
    $t[] = 'CREATE INDEX idx_info_section ON info_items (section, sort_order)';
    $t[] = 'CREATE INDEX idx_rate ON rate_limits (bucket, ip_hash, created_at)';
    return $t;
}

function db_create_schema(): void
{
    foreach (schema_statements(db_driver()) as $sql) {
        try {
            db()->exec($sql);
        } catch (Throwable $e) {
            // Re-running the installer: ignore "index already exists".
            if (!str_starts_with($sql, 'CREATE INDEX')) {
                throw $e;
            }
        }
    }
}
