<?php
declare(strict_types=1);

require_once APP_DIR . '/seed.php';

function partial(string $__partial, array $__vars = []): void
{
    // Parameter names are deliberately unusual so they never collide with view variables such as $name.
    extract($__vars, EXTR_SKIP);
    unset($__vars);
    require APP_DIR . '/views/partials/' . $__partial . '.php';
}

/** Render a public page with SEO values from settings. */
function public_page(string $view, string $seoKey, array $data = []): void
{
    [$t, $d] = page_seo($seoKey);
    render('pages/' . $view, array_merge([
        'title' => $t,
        'description' => $d,
        'seoKey' => $seoKey,
    ], $data));
}

function published_articles_sql(): string
{
    return "status = 'published' AND published_at <= '" . now() . "'";
}

function approved_feedback(int $limit = 24): array
{
    return db_all("SELECT name, service, rating, message, created_at FROM feedback WHERE status = 'approved' AND consent = 1 ORDER BY created_at DESC LIMIT " . (int)$limit);
}

/* ---------------- Pages ---------------- */

function page_home(): void
{
    $cats = service_categories();
    unset($cats['other']);
    public_page('home', 'home', [
        'cats' => $cats,
        'expertise' => db_all('SELECT title, description, icon FROM expertise WHERE published = 1 ORDER BY sort_order, id LIMIT 6'),
        'articles' => db_all('SELECT title, slug, excerpt, category, image, image_alt, published_at FROM articles WHERE ' . published_articles_sql() . ' ORDER BY published_at DESC LIMIT 3'),
        'why' => array_map(fn($l) => array_map('trim', explode('|', $l, 2)) + [1 => ''], lines(setting('home_why'))),
        'jsonld' => local_business_schema(),
    ]);
}

function page_about(): void
{
    public_page('about', 'about', [
        'timeline' => db_all('SELECT year, title, description FROM timeline WHERE published = 1 ORDER BY sort_order, id'),
        'expertise' => db_all('SELECT title, icon FROM expertise WHERE published = 1 ORDER BY sort_order, id'),
    ]);
}

function page_services(): void
{
    $rows = db_all('SELECT * FROM services WHERE published = 1 ORDER BY sort_order, id');
    $byCat = [];
    foreach ($rows as $r) {
        $byCat[$r['category']][] = $r;
    }
    public_page('services', 'services', ['byCat' => $byCat, 'cats' => service_categories()]);
}

function page_expertise(): void
{
    public_page('expertise', 'expertise', [
        'items' => db_all('SELECT title, description, icon FROM expertise WHERE published = 1 ORDER BY sort_order, id'),
    ]);
}

function page_insights(): void
{
    $q = trim((string)($_GET['q'] ?? ''));
    $q = mb_substr($q, 0, 80);
    $cat = trim((string)($_GET['cat'] ?? ''));
    $cats = blog_categories();
    if ($cat !== '' && !in_array($cat, $cats, true)) {
        $cat = '';
    }
    $page = max(1, (int)($_GET['page'] ?? 1));
    $per = 9;

    $where = [published_articles_sql()];
    $params = [];
    if ($cat !== '') {
        $where[] = 'category = ?';
        $params[] = $cat;
    }
    if ($q !== '') {
        $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $q) . '%';
        $where[] = "(title LIKE ? ESCAPE '\\' OR excerpt LIKE ? ESCAPE '\\' OR body LIKE ? ESCAPE '\\')";
        array_push($params, $like, $like, $like);
    }
    $w = implode(' AND ', $where);
    $total = (int)db_val("SELECT COUNT(*) FROM articles WHERE $w", $params);
    $pages = max(1, (int)ceil($total / $per));
    $page = min($page, $pages);
    $off = ($page - 1) * $per;
    $articles = db_all("SELECT title, slug, excerpt, category, image, image_alt, featured, published_at FROM articles WHERE $w ORDER BY published_at DESC LIMIT $per OFFSET $off", $params);

    $featured = [];
    if ($q === '' && $cat === '' && $page === 1) {
        $featured = db_all('SELECT title, slug, excerpt, category, image, image_alt, published_at FROM articles WHERE ' . published_articles_sql() . ' AND featured = 1 ORDER BY published_at DESC LIMIT 2');
    }
    $totalAll = (int)db_val('SELECT COUNT(*) FROM articles WHERE ' . published_articles_sql());
    public_page('insights', 'insights', [
        'articles' => $articles, 'featured' => $featured, 'cats' => $cats, 'cat' => $cat, 'q' => $q,
        'page' => $page, 'pages' => $pages, 'total' => $total, 'totalAll' => $totalAll,
        'noindex' => ($q !== '' || $page > 1),
    ]);
}

function page_article(string $slug): void
{
    $a = db_one('SELECT * FROM articles WHERE slug = ? AND ' . published_articles_sql(), [$slug]);
    if (!$a) {
        http_error(404);
    }
    $related = db_all('SELECT title, slug, excerpt, category, image, image_alt, published_at FROM articles WHERE ' . published_articles_sql()
        . ' AND id <> ? ' . ($a['category'] ? 'AND category = ? ' : '') . 'ORDER BY published_at DESC LIMIT 3',
        $a['category'] ? [$a['id'], $a['category']] : [$a['id']]);
    if (count($related) < 3) {
        $have = array_merge([(int)$a['id']], array_map(fn($r) => 0, $related));
        $extra = db_all('SELECT title, slug, excerpt, category, image, image_alt, published_at FROM articles WHERE ' . published_articles_sql()
            . ' AND id <> ? ORDER BY published_at DESC LIMIT 6', [$a['id']]);
        $slugs = array_column($related, 'slug');
        foreach ($extra as $x) {
            if (count($related) >= 3) {
                break;
            }
            if (!in_array($x['slug'], $slugs, true)) {
                $related[] = $x;
            }
        }
    }
    $desc = $a['seo_description'] ?: ($a['excerpt'] ?: str_limit(md_plain($a['body']), 155));
    $ld = [
        '@context' => 'https://schema.org', '@type' => 'Article',
        'headline' => $a['title'], 'datePublished' => date('c', strtotime($a['published_at'])),
        'dateModified' => date('c', strtotime($a['updated_at'])),
        'author' => ['@type' => 'Person', 'name' => $a['author'] ?: setting('ca_name')],
        'publisher' => ['@type' => 'Organization', 'name' => setting('firm_name')],
        'mainEntityOfPage' => abs_url('insights/' . $a['slug']),
    ];
    if ($a['image']) {
        $ld['image'] = abs_url($a['image']);
    }
    render('pages/article', [
        'title' => ($a['seo_title'] ?: $a['title']) . ' | ' . setting('firm_name'),
        'description' => $desc,
        'article' => $a, 'related' => $related, 'jsonld' => $ld,
        'og_image' => $a['image'] ?: null, 'seoKey' => 'insights', 'og_type' => 'article',
    ]);
}

function slot_data(): array
{
    $mk = function (string $ymd) {
        $out = [];
        foreach (time_slots($ymd) as $v => $l) {
            $out[] = [$v, $l];
        }
        return $out;
    };
    // 2030-01-07 is a Monday, 2030-01-12 a Saturday.
    return ['wk' => $mk('2030-01-07'), 'sat' => $mk('2030-01-12')];
}

function take_done(string $type): ?array
{
    $d = $_SESSION['_done'] ?? null;
    if ($d && ($d['type'] ?? '') === $type) {
        unset($_SESSION['_done']);
        return $d;
    }
    return null;
}

function page_book(): void
{
    $done = isset($_GET['submitted']) ? take_done('appointment') : null;
    $prefService = trim((string)($_GET['service'] ?? ''));
    public_page('book', 'book', [
        'done' => $done, 'prefService' => mb_substr($prefService, 0, 160), 'slots' => slot_data(),
        'minDate' => date('Y-m-d', strtotime('+1 day')), 'maxDate' => date('Y-m-d', strtotime('+120 days')),
        'noindex' => (bool)$done,
    ]);
}

function fail_form(string $to, array $errors): never
{
    keep_old($_POST);
    set_errors($errors);
    flash('error', 'Please correct the highlighted fields and try again.');
    redirect($to);
}

function post_book(): void
{
    $spam = spam_check('book', 5);
    if ($spam === 'bot') {
        $_SESSION['_done'] = ['type' => 'appointment', 'ref' => 'PJC-A-' . random_int(10000, 99999)];
        redirect('book-consultation?submitted=1');
    }
    if ($spam !== 'ok') {
        keep_old($_POST);
        flash('error', $spam);
        redirect('book-consultation');
    }
    $name = post_str('name', 120);
    $phone = post_str('phone', 30);
    $email = strtolower(post_str('email', 190));
    $service = post_str('service', 160);
    $date = post_str('pref_date', 10);
    $time = post_str('pref_time', 8);
    $mode = post_str('mode', 12);
    $msg = post_text('message', 2000);

    $err = [];
    if (mb_strlen($name) < 2) {
        $err['name'] = 'Please enter your full name.';
    }
    if (!valid_phone($phone)) {
        $err['phone'] = 'Please enter a valid phone number.';
    }
    if (!valid_email($email)) {
        $err['email'] = 'Please enter a valid email address.';
    }
    $valid = array_merge(service_titles(), ['Not sure / General consultation']);
    if ($service === '' || !in_array($service, $valid, true)) {
        $err['service'] = 'Please choose the type of service required.';
    }
    $dt = DateTime::createFromFormat('Y-m-d', $date);
    if (!$dt || $dt->format('Y-m-d') !== $date) {
        $err['pref_date'] = 'Please choose a preferred date.';
    } elseif ($date < date('Y-m-d', strtotime('+1 day')) || $date > date('Y-m-d', strtotime('+120 days'))) {
        $err['pref_date'] = 'Please choose a date from tomorrow up to the next four months.';
    } elseif ((int)$dt->format('N') === 7) {
        $err['pref_date'] = 'The office is closed on Sundays. Please choose another day.';
    } elseif (!isset(time_slots($date)[$time])) {
        $err['pref_time'] = 'Please choose a time within office hours.';
    }
    if (!in_array($mode, ['Online', 'In-person'], true)) {
        $err['mode'] = 'Please choose Online or In-person.';
    }
    if ($err) {
        fail_form('book-consultation', $err);
    }
    $t = now();
    $id = db_insert('appointments', [
        'name' => $name, 'phone' => $phone, 'email' => $email, 'service' => $service, 'pref_date' => $date,
        'pref_time' => $time, 'mode' => $mode, 'message' => $msg, 'status' => 'pending', 'is_read' => 0,
        'ip_hash' => ip_hash(), 'created_at' => $t, 'updated_at' => $t,
    ]);
    $ref = 'PJC-A-' . str_pad((string)$id, 5, '0', STR_PAD_LEFT);
    db_update('appointments', ['ref' => $ref], 'id = ?', [$id]);
    rate_hit('book');
    notify_admin("New consultation request $ref", "Name: $name\nPhone: $phone\nEmail: $email\nService: $service\nPreferred: $date $time ($mode)\n\n$msg\n\nReview: " . abs_url('admin/appointments/' . $id), $email);
    clear_old();
    $_SESSION['_done'] = ['type' => 'appointment', 'ref' => $ref, 'date' => $date, 'time' => time_slots($date)[$time], 'mode' => $mode];
    redirect('book-consultation?submitted=1');
}

function page_contact(): void
{
    $done = isset($_GET['sent']) ? take_done('enquiry') : null;
    public_page('contact', 'contact', [
        'done' => $done, 'prefService' => mb_substr(trim((string)($_GET['service'] ?? '')), 0, 160),
        'noindex' => (bool)$done, 'jsonld' => local_business_schema(),
    ]);
}

function post_contact(): void
{
    $spam = spam_check('contact', 6);
    $back = 'contact';
    if ($spam === 'bot') {
        $_SESSION['_done'] = ['type' => 'enquiry'];
        redirect('contact?sent=1');
    }
    if ($spam !== 'ok') {
        keep_old($_POST);
        flash('error', $spam);
        redirect($back);
    }
    $name = post_str('name', 120);
    $email = strtolower(post_str('email', 190));
    $phone = post_str('phone', 30);
    $subject = post_str('subject', 200);
    $service = post_str('service', 160);
    $msg = post_text('message', 4000);
    $err = [];
    if (mb_strlen($name) < 2) {
        $err['name'] = 'Please enter your full name.';
    }
    if (!valid_email($email)) {
        $err['email'] = 'Please enter a valid email address.';
    }
    if ($phone !== '' && !valid_phone($phone)) {
        $err['phone'] = 'Please enter a valid phone number or leave it blank.';
    }
    if (mb_strlen($subject) < 3) {
        $err['subject'] = 'Please enter a subject.';
    }
    if (mb_strlen($msg) < 10) {
        $err['message'] = 'Please enter a message of at least 10 characters.';
    }
    if ($service !== '' && !in_array($service, array_merge(service_titles(), ['Not sure / General enquiry']), true)) {
        $service = '';
    }
    if ($err) {
        fail_form($back, $err);
    }
    $t = now();
    $id = db_insert('enquiries', [
        'name' => $name, 'email' => $email, 'phone' => $phone, 'subject' => $subject, 'service' => $service,
        'message' => $msg, 'status' => 'new', 'is_read' => 0, 'ip_hash' => ip_hash(), 'created_at' => $t, 'updated_at' => $t,
    ]);
    rate_hit('contact');
    notify_admin('New website enquiry: ' . $subject, "Name: $name\nEmail: $email\nPhone: $phone\nService: $service\n\n$msg\n\nReview: " . abs_url('admin/enquiries/' . $id), $email);
    clear_old();
    $_SESSION['_done'] = ['type' => 'enquiry'];
    redirect('contact?sent=1');
}

function page_feedback(): void
{
    $done = isset($_GET['thanks']) ? take_done('feedback') : null;
    public_page('feedback', 'feedback', ['done' => $done, 'reviews' => approved_feedback(12), 'noindex' => (bool)$done]);
}

function post_feedback(): void
{
    $spam = spam_check('feedback', 4);
    if ($spam === 'bot') {
        $_SESSION['_done'] = ['type' => 'feedback'];
        redirect('feedback?thanks=1');
    }
    if ($spam !== 'ok') {
        keep_old($_POST);
        flash('error', $spam);
        redirect('feedback');
    }
    $name = post_str('name', 120);
    $email = strtolower(post_str('email', 190));
    $service = post_str('service', 160);
    $rating = (int)($_POST['rating'] ?? 0);
    $msg = post_text('message', 2000);
    $consent = !empty($_POST['consent']) ? 1 : 0;
    $err = [];
    if (mb_strlen($name) < 2) {
        $err['name'] = 'Please enter your name.';
    }
    if ($email !== '' && !valid_email($email)) {
        $err['email'] = 'Please enter a valid email address or leave it blank.';
    }
    if ($rating < 1 || $rating > 5) {
        $err['rating'] = 'Please choose a rating from 1 to 5.';
    }
    if (mb_strlen($msg) < 10) {
        $err['message'] = 'Please share a few words of feedback (at least 10 characters).';
    }
    if ($service !== '' && !in_array($service, array_merge(array_column(service_categories(), 'label'), service_titles(), ['Other']), true)) {
        $service = '';
    }
    if ($err) {
        fail_form('feedback', $err);
    }
    $t = now();
    db_insert('feedback', [
        'name' => $name, 'email' => $email ?: null, 'service' => $service, 'rating' => $rating, 'message' => $msg,
        'consent' => $consent, 'status' => 'pending', 'ip_hash' => ip_hash(), 'created_at' => $t, 'updated_at' => $t,
    ]);
    rate_hit('feedback');
    notify_admin('New website feedback (' . $rating . '/5)', "From: $name\nService: $service\n\n$msg\n\nReview: " . abs_url('admin/feedback'));
    clear_old();
    $_SESSION['_done'] = ['type' => 'feedback'];
    redirect('feedback?thanks=1');
}

function page_info(): void
{
    $items = [];
    foreach (db_all('SELECT * FROM info_items WHERE published = 1 ORDER BY sort_order, id') as $r) {
        $items[$r['section']][] = $r;
    }
    public_page('info', 'info', ['items' => $items, 'sections' => info_sections(), 'reviews' => approved_feedback(12)]);
}

function page_legal(string $slug): void
{
    $row = db_one('SELECT * FROM legal_pages WHERE slug = ?', [$slug]);
    $title = legal_slugs()[$slug];
    if (!$row || !(int)$row['published'] || str_contains((string)$row['body'], REVIEW_MARKER)) {
        http_response_code(200);
        render('pages/legal', [
            'title' => $title . ' | ' . setting('firm_name'), 'description' => '', 'noindex' => true,
            'heading' => $title, 'body' => '', 'ready' => false, 'seoKey' => 'info',
        ]);
        return;
    }
    render('pages/legal', [
        'title' => $title . ' | ' . setting('firm_name'),
        'description' => $title . ' of ' . setting('firm_name') . ', Chartered Accountants, Thane.',
        'heading' => $title, 'body' => $row['body'], 'ready' => true, 'updated' => $row['updated_at'], 'seoKey' => 'info',
    ]);
}

function page_sitemap(): void
{
    $urls = [];
    foreach (['', 'about', 'services', 'expertise', 'insights', 'book-consultation', 'contact', 'feedback', 'additional-information'] as $p) {
        $urls[] = [abs_url($p), null];
    }
    foreach (db_all('SELECT slug, body FROM legal_pages WHERE published = 1') as $l) {
        if (!str_contains((string)$l['body'], REVIEW_MARKER)) {
            $urls[] = [abs_url($l['slug']), null];
        }
    }
    foreach (db_all('SELECT slug, updated_at FROM articles WHERE ' . published_articles_sql() . ' ORDER BY published_at DESC') as $a) {
        $urls[] = [abs_url('insights/' . $a['slug']), date('Y-m-d', strtotime($a['updated_at']))];
    }
    header('Content-Type: application/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($urls as [$loc, $mod]) {
        echo '  <url><loc>' . e($loc) . '</loc>' . ($mod ? '<lastmod>' . e($mod) . '</lastmod>' : '') . "</url>\n";
    }
    echo '</urlset>';
}

function page_robots(): void
{
    header('Content-Type: text/plain; charset=utf-8');
    echo "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /install\n\nSitemap: " . abs_url('sitemap.xml') . "\n";
}

/* ---------------- One-time installer ---------------- */

function page_install(): void
{
    $installed = db_installed();
    $dbOk = true;
    try {
        db()->query('SELECT 1');
    } catch (Throwable $e) {
        $dbOk = false;
    }
    render('pages/install', [
        'title' => 'Set up website', 'description' => '', 'noindex' => true,
        'installed' => $installed, 'dbOk' => $dbOk, 'hasKey' => (bool)env('INSTALL_KEY'),
    ], 'layout_bare');
}

function post_install(): void
{
    if (db_installed()) {
        http_error(403, 'The website has already been set up.');
    }
    $key = (string)env('INSTALL_KEY', '');
    if ($key === '') {
        http_error(403, 'Set INSTALL_KEY in your .env file first.');
    }
    if (rate_count_safe('install') >= 8) {
        http_error(429, 'Too many attempts. Please wait and try again later.');
    }
    $name = post_str('name', 120);
    $email = strtolower(post_str('email', 190));
    $pw = (string)($_POST['password'] ?? '');
    $pw2 = (string)($_POST['password2'] ?? '');
    if (!hash_equals($key, (string)($_POST['install_key'] ?? ''))) {
        rate_hit_safe('install');
        flash('error', 'The setup key is incorrect.');
        redirect('install');
    }
    $err = null;
    if (mb_strlen($name) < 2) {
        $err = 'Please enter the administrator name.';
    } elseif (!valid_email($email)) {
        $err = 'Please enter a valid email address.';
    } elseif ($pw !== $pw2) {
        $err = 'The two passwords do not match.';
    } else {
        $err = password_problem($pw);
    }
    if ($err) {
        flash('error', $err);
        redirect('install');
    }
    db_create_schema();
    seed_content();
    db_insert('users', ['name' => $name, 'email' => $email, 'password_hash' => password_hash($pw, PASSWORD_DEFAULT),
        'role' => 'admin', 'active' => 1, 'created_at' => now()]);
    flash('success', 'Setup complete. Please sign in.');
    redirect('admin/login');
}

function rate_count_safe(string $b): int
{
    try {
        return rate_count($b, 3600);
    } catch (Throwable $e) {
        return 0;
    }
}

function rate_hit_safe(string $b): void
{
    try {
        rate_hit($b);
    } catch (Throwable $e) {
    }
}
