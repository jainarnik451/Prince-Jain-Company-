<?php
declare(strict_types=1);

/* ============ helpers ============ */

function admin_render(string $view, array $data = []): void
{
    header('Cache-Control: no-store');
    render('admin/' . $view, array_merge(['title' => 'Admin', 'user' => current_user()], $data), 'admin/layout');
}

function back_to(string $path): never
{
    redirect($path);
}

function counts(): array
{
    return [
        'enq_total' => (int)db_val('SELECT COUNT(*) FROM enquiries'),
        'enq_new' => (int)db_val("SELECT COUNT(*) FROM enquiries WHERE is_read = 0"),
        'appt_total' => (int)db_val('SELECT COUNT(*) FROM appointments'),
        'appt_pending' => (int)db_val("SELECT COUNT(*) FROM appointments WHERE status = 'pending'"),
        'art_total' => (int)db_val('SELECT COUNT(*) FROM articles'),
        'art_pub' => (int)db_val("SELECT COUNT(*) FROM articles WHERE status = 'published'"),
        'fb_total' => (int)db_val('SELECT COUNT(*) FROM feedback'),
        'fb_pending' => (int)db_val("SELECT COUNT(*) FROM feedback WHERE status = 'pending'"),
    ];
}

/* ============ auth ============ */

function admin_login_form(): void
{
    if (current_user()) {
        redirect('admin');
    }
    render('admin/login', ['title' => 'Admin sign in', 'description' => '', 'noindex' => true], 'layout_bare');
}

function admin_login_post(): void
{
    $email = strtolower(post_str('email', 190));
    $pw = (string)($_POST['password'] ?? '');
    if ($email !== '' && $pw !== '' && login_attempt($email, $pw)) {
        redirect('admin');
    }
    if ($email === '' || $pw === '') {
        flash('error', 'Please enter your email and password.');
    }
    redirect('admin/login');
}

function admin_logout(): void
{
    logout_now();
    session_boot();
    flash('success', 'You have been signed out.');
    redirect('admin/login');
}

function admin_dashboard(): void
{
    require_login();
    $recentAppt = db_all('SELECT id, ref, name, service, pref_date, pref_time, status, created_at FROM appointments ORDER BY created_at DESC LIMIT 5');
    $recentEnq = db_all('SELECT id, name, subject, is_read, status, created_at FROM enquiries ORDER BY created_at DESC LIMIT 5');
    admin_render('dashboard', ['title' => 'Dashboard', 'c' => counts(), 'recentAppt' => $recentAppt, 'recentEnq' => $recentEnq, 'nav' => 'dashboard']);
}

function admin_account(): void
{
    require_login();
    admin_render('account', ['title' => 'My account', 'nav' => 'account']);
}

function admin_account_post(): void
{
    $u = require_login();
    $row = db_one('SELECT * FROM users WHERE id = ?', [$u['id']]);
    $cur = (string)($_POST['current'] ?? '');
    $new = (string)($_POST['new'] ?? '');
    $new2 = (string)($_POST['new2'] ?? '');
    if (!password_verify($cur, $row['password_hash'])) {
        flash('error', 'Your current password is incorrect.');
    } elseif ($new !== $new2) {
        flash('error', 'The new passwords do not match.');
    } elseif ($p = password_problem($new)) {
        flash('error', $p);
    } else {
        db_update('users', ['password_hash' => password_hash($new, PASSWORD_DEFAULT)], 'id = ?', [$u['id']]);
        session_regenerate_id(true);
        flash('success', 'Password updated.');
    }
    redirect('admin/account');
}

/* ============ appointments ============ */

function appt_statuses(): array
{
    return ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'completed' => 'Completed', 'cancelled' => 'Cancelled'];
}

function admin_appointments(): void
{
    require_cap('inbox');
    $st = (string)($_GET['status'] ?? '');
    $where = isset(appt_statuses()[$st]) ? 'WHERE status = ?' : '';
    $rows = db_all("SELECT * FROM appointments $where ORDER BY created_at DESC LIMIT 300", $where ? [$st] : []);
    admin_render('appointments', ['title' => 'Appointments', 'rows' => $rows, 'status' => $st, 'nav' => 'appointments']);
}

function admin_appointment_view(string $id): void
{
    require_cap('inbox');
    $r = db_one('SELECT * FROM appointments WHERE id = ?', [(int)$id]) ?? http_error(404);
    if (!(int)$r['is_read']) {
        db_update('appointments', ['is_read' => 1], 'id = ?', [$r['id']]);
    }
    admin_render('appointment', ['title' => 'Appointment ' . $r['ref'], 'r' => $r, 'nav' => 'appointments']);
}

function admin_appointment_save(string $id): void
{
    require_cap('inbox');
    $r = db_one('SELECT id FROM appointments WHERE id = ?', [(int)$id]) ?? http_error(404);
    $status = post_str('quick', 16) ?: post_str('status', 16);
    if (!isset(appt_statuses()[$status])) {
        flash('error', 'Invalid status.');
        redirect('admin/appointments/' . $id);
    }
    db_update('appointments', ['status' => $status, 'admin_notes' => post_text('admin_notes', 4000), 'updated_at' => now(), 'is_read' => 1], 'id = ?', [$r['id']]);
    flash('success', 'Appointment updated.');
    redirect('admin/appointments/' . $id);
}

function admin_appointment_delete(string $id): void
{
    require_cap('inbox');
    db_q('DELETE FROM appointments WHERE id = ?', [(int)$id]);
    flash('success', 'Appointment deleted.');
    redirect('admin/appointments');
}

/* ============ enquiries ============ */

function enq_statuses(): array
{
    return ['new' => 'New', 'in_progress' => 'In progress', 'replied' => 'Replied', 'closed' => 'Closed'];
}

function admin_enquiries(): void
{
    require_cap('inbox');
    $st = (string)($_GET['status'] ?? '');
    $where = isset(enq_statuses()[$st]) ? 'WHERE status = ?' : '';
    $rows = db_all("SELECT * FROM enquiries $where ORDER BY created_at DESC LIMIT 300", $where ? [$st] : []);
    admin_render('enquiries', ['title' => 'Enquiries', 'rows' => $rows, 'status' => $st, 'nav' => 'enquiries']);
}

function admin_enquiry_view(string $id): void
{
    require_cap('inbox');
    $r = db_one('SELECT * FROM enquiries WHERE id = ?', [(int)$id]) ?? http_error(404);
    if (!(int)$r['is_read']) {
        db_update('enquiries', ['is_read' => 1], 'id = ?', [$r['id']]);
        $r['is_read'] = 1;
    }
    admin_render('enquiry', ['title' => 'Enquiry', 'r' => $r, 'nav' => 'enquiries']);
}

function admin_enquiry_save(string $id): void
{
    require_cap('inbox');
    $r = db_one('SELECT id FROM enquiries WHERE id = ?', [(int)$id]) ?? http_error(404);
    $status = post_str('status', 16);
    if (!isset(enq_statuses()[$status])) {
        flash('error', 'Invalid status.');
        redirect('admin/enquiries/' . $id);
    }
    $read = isset($_POST['mark_unread']) ? 0 : 1;
    db_update('enquiries', ['status' => $status, 'admin_notes' => post_text('admin_notes', 4000), 'is_read' => $read, 'updated_at' => now()], 'id = ?', [$r['id']]);
    flash('success', 'Enquiry updated.');
    redirect($read ? 'admin/enquiries/' . $id : 'admin/enquiries');
}

function admin_enquiry_delete(string $id): void
{
    require_cap('inbox');
    db_q('DELETE FROM enquiries WHERE id = ?', [(int)$id]);
    flash('success', 'Enquiry deleted.');
    redirect('admin/enquiries');
}

/* ============ feedback ============ */

function admin_feedback(): void
{
    require_cap('inbox');
    $st = (string)($_GET['status'] ?? '');
    $ok = in_array($st, ['pending', 'approved', 'hidden'], true);
    $rows = db_all('SELECT * FROM feedback ' . ($ok ? 'WHERE status = ? ' : '') . 'ORDER BY created_at DESC LIMIT 300', $ok ? [$st] : []);
    admin_render('feedback', ['title' => 'Feedback', 'rows' => $rows, 'status' => $st, 'nav' => 'feedback']);
}

function admin_feedback_action(string $id): void
{
    require_cap('inbox');
    $r = db_one('SELECT * FROM feedback WHERE id = ?', [(int)$id]) ?? http_error(404);
    $act = post_str('action', 10);
    if ($act === 'approve') {
        if (!(int)$r['consent']) {
            flash('error', 'This person did not give permission to publish their feedback, so it cannot be approved.');
        } else {
            db_update('feedback', ['status' => 'approved', 'updated_at' => now()], 'id = ?', [$r['id']]);
            flash('success', 'Feedback approved and now visible on the website.');
        }
    } elseif ($act === 'hide') {
        db_update('feedback', ['status' => 'hidden', 'updated_at' => now()], 'id = ?', [$r['id']]);
        flash('success', 'Feedback hidden from the website.');
    } elseif ($act === 'delete') {
        db_q('DELETE FROM feedback WHERE id = ?', [$r['id']]);
        flash('success', 'Feedback deleted.');
    }
    redirect('admin/feedback' . (!empty($_POST['back']) ? '?status=' . rawurlencode(post_str('back', 12)) : ''));
}

/* ============ articles ============ */

function admin_articles(): void
{
    require_cap('blog');
    $rows = db_all('SELECT id, title, slug, category, status, featured, published_at, updated_at FROM articles ORDER BY updated_at DESC');
    admin_render('articles', ['title' => 'Articles', 'rows' => $rows, 'nav' => 'articles']);
}

function admin_article_form(string $id = ''): void
{
    require_cap('blog');
    $a = null;
    if ($id !== '') {
        $a = db_one('SELECT * FROM articles WHERE id = ?', [(int)$id]) ?? http_error(404);
    }
    admin_render('article_form', ['title' => $a ? 'Edit article' : 'New article', 'a' => $a, 'cats' => blog_categories(), 'nav' => 'articles']);
}

function admin_article_save(): void
{
    require_cap('blog');
    $id = (int)($_POST['id'] ?? 0);
    $old = $id ? db_one('SELECT * FROM articles WHERE id = ?', [$id]) : null;
    if ($id && !$old) {
        http_error(404);
    }
    $title = post_str('title', 200);
    $body = post_text('body', 60000);
    $action = post_str('action', 12); // draft | publish | unpublish
    $err = null;
    if (mb_strlen($title) < 3) {
        $err = 'Please enter a title.';
    }
    if (!$err && $action === 'publish' && mb_strlen($body) < 20) {
        $err = 'Please write the article text before publishing.';
    }
    [$img, $imgErr] = save_image_upload('image', 1600);
    if (!$err && $imgErr) {
        $err = $imgErr;
    }
    if ($err) {
        if ($img) {
            delete_upload($img);
        }
        keep_old($_POST);
        flash('error', $err);
        redirect($id ? 'admin/articles/' . $id : 'admin/articles/new');
    }
    $cat = post_str('category', 80);
    if ($cat !== '' && !in_array($cat, blog_categories(), true)) {
        $cat = '';
    }
    $image = $old['image'] ?? null;
    if ($img) {
        delete_upload($image);
        $image = $img;
    } elseif (!empty($_POST['remove_image'])) {
        delete_upload($image);
        $image = null;
    }
    $status = $old['status'] ?? 'draft';
    $pub = $old['published_at'] ?? null;
    if ($action === 'publish') {
        $status = 'published';
        $d = post_str('published_at', 10);
        $pub = ($d && DateTime::createFromFormat('Y-m-d', $d)) ? $d . ' ' . date('H:i:s') : ($pub ?: now());
        if ($pub > now() && substr($pub, 0, 10) === date('Y-m-d')) {
            $pub = now();
        }
    } elseif ($action === 'unpublish') {
        $status = 'draft';
    }
    $data = [
        'title' => $title,
        'excerpt' => post_str('excerpt', 420),
        'body' => $body,
        'category' => $cat,
        'image' => $image,
        'image_alt' => post_str('image_alt', 200),
        'featured' => !empty($_POST['featured']) ? 1 : 0,
        'status' => $status,
        'published_at' => $pub,
        'author' => post_str('author', 120),
        'seo_title' => post_str('seo_title', 200),
        'seo_description' => post_str('seo_description', 320),
        'updated_at' => now(),
    ];
    if ($old) {
        $data['slug'] = $old['slug'];
        $rawSlug = post_str('slug', 120);
        $custom = $rawSlug !== '' ? slugify($rawSlug) : '';
        if ($custom !== '' && $custom !== $old['slug']) {
            $data['slug'] = unique_slug('articles', $custom, $id);
        }
        db_update('articles', $data, 'id = ?', [$id]);
    } else {
        $data['slug'] = unique_slug('articles', post_str('slug', 120) ?: $title);
        $data['created_at'] = now();
        $id = db_insert('articles', $data);
    }
    clear_old();
    flash('success', match ($action) {
        'publish' => 'Article published.',
        'unpublish' => 'Article moved back to drafts.',
        default => 'Draft saved.',
    });
    redirect('admin/articles/' . $id);
}

function admin_article_toggle(string $id): void
{
    require_cap('blog');
    $a = db_one('SELECT * FROM articles WHERE id = ?', [(int)$id]) ?? http_error(404);
    if ($a['status'] === 'published') {
        db_update('articles', ['status' => 'draft', 'updated_at' => now()], 'id = ?', [$a['id']]);
        flash('success', 'Article unpublished.');
    } else {
        if (mb_strlen((string)$a['body']) < 20) {
            flash('error', 'Add the article text before publishing.');
            redirect('admin/articles/' . $a['id']);
        }
        db_update('articles', ['status' => 'published', 'published_at' => $a['published_at'] ?: now(), 'updated_at' => now()], 'id = ?', [$a['id']]);
        flash('success', 'Article published.');
    }
    redirect('admin/articles');
}

function admin_article_delete(string $id): void
{
    require_cap('blog');
    $a = db_one('SELECT * FROM articles WHERE id = ?', [(int)$id]) ?? http_error(404);
    delete_upload($a['image']);
    db_q('DELETE FROM articles WHERE id = ?', [$a['id']]);
    flash('success', 'Article deleted.');
    redirect('admin/articles');
}

/* ============ generic content entities ============ */

function entity_defs(): array
{
    $cats = [];
    foreach (service_categories() as $k => $c) {
        $cats[$k] = $c['label'];
    }
    $icons = array_combine(icon_names(), icon_names());
    return [
        'services' => [
            'label' => 'Services', 'singular' => 'service', 'table' => 'services', 'list' => ['title', 'category'], 'slug' => 'title',
            'order' => 'sort_order, id',
            'fields' => [
                ['category', 'Category', 'select', ['options' => $cats, 'required' => true]],
                ['title', 'Service name', 'text', ['required' => true, 'max' => 160]],
                ['short_desc', 'Short description (shown on cards)', 'text', ['max' => 320]],
                ['description', 'Full description', 'markdown', []],
                ['who_benefits', 'Who can benefit', 'textarea', ['max' => 1000]],
                ['key_info', 'Key information (one point per line)', 'textarea', ['max' => 2000]],
                ['sort_order', 'Sort order', 'number', []],
                ['admin_note', 'Internal note (never shown publicly)', 'text', ['max' => 255]],
            ],
        ],
        'expertise' => [
            'label' => 'Areas of Expertise', 'singular' => 'expertise item', 'table' => 'expertise', 'list' => ['title'], 'order' => 'sort_order, id',
            'fields' => [
                ['title', 'Title', 'text', ['required' => true, 'max' => 140]],
                ['description', 'Description', 'textarea', ['max' => 500]],
                ['icon', 'Icon', 'select', ['options' => $icons]],
                ['sort_order', 'Sort order', 'number', []],
                ['admin_note', 'Internal note (never shown publicly)', 'text', ['max' => 255]],
            ],
        ],
        'timeline' => [
            'label' => 'Firm Timeline', 'singular' => 'timeline entry', 'table' => 'timeline', 'list' => ['year', 'title'], 'order' => 'sort_order, id',
            'fields' => [
                ['year', 'Year / label', 'text', ['required' => true, 'max' => 30, 'help' => 'For example 2001, or Today.']],
                ['title', 'Title', 'text', ['required' => true, 'max' => 160]],
                ['description', 'Description', 'textarea', ['max' => 600]],
                ['sort_order', 'Sort order', 'number', []],
            ],
        ],
        'info' => [
            'label' => 'Additional Information', 'singular' => 'item', 'table' => 'info_items', 'list' => ['section', 'title'], 'order' => 'section, sort_order, id',
            'default_unpublished' => true,
            'fields' => [
                ['section', 'Section', 'select', ['options' => array_map(fn($s) => $s[0], info_sections()), 'required' => true]],
                ['title', 'Title / question / name', 'text', ['required' => true, 'max' => 200, 'help' => 'For FAQs this is the question; for testimonials, the client name as they approved it.']],
                ['body', 'Text / answer', 'markdown', []],
                ['link_url', 'Link (optional)', 'url', ['max' => 255]],
                ['sort_order', 'Sort order', 'number', []],
            ],
        ],
        'links' => [
            'label' => 'Footer Links', 'singular' => 'link', 'table' => 'links', 'list' => ['label', 'url'], 'order' => 'sort_order, id',
            'fields' => [
                ['label', 'Link text', 'text', ['required' => true, 'max' => 120]],
                ['url', 'Link address', 'text', ['required' => true, 'max' => 255, 'help' => 'A full web address (https://…), a site page such as privacy-policy, or {linkedin} for the LinkedIn link in Social Media settings.']],
                ['sort_order', 'Sort order', 'number', []],
            ],
        ],
    ];
}

function entity_def(string $e): array
{
    return entity_defs()[$e] ?? http_error(404);
}

function admin_entity_list(string $e): void
{
    require_cap('content');
    $d = entity_def($e);
    $rows = db_all('SELECT * FROM ' . $d['table'] . ' ORDER BY ' . $d['order']);
    admin_render('entity_list', ['title' => $d['label'], 'e' => $e, 'd' => $d, 'rows' => $rows, 'nav' => $e]);
}

function admin_entity_form(string $e, string $id): void
{
    require_cap('content');
    $d = entity_def($e);
    $row = null;
    if ($id !== 'new') {
        $row = db_one('SELECT * FROM ' . $d['table'] . ' WHERE id = ?', [(int)$id]) ?? http_error(404);
    }
    admin_render('entity_form', ['title' => ($row ? 'Edit ' : 'Add ') . $d['singular'], 'e' => $e, 'd' => $d, 'row' => $row, 'nav' => $e]);
}

function admin_entity_save(string $e): void
{
    require_cap('content');
    $d = entity_def($e);
    $id = (int)($_POST['id'] ?? 0);
    $old = $id ? db_one('SELECT * FROM ' . $d['table'] . ' WHERE id = ?', [$id]) : null;
    if ($id && !$old) {
        http_error(404);
    }
    $data = [];
    $err = null;
    foreach ($d['fields'] as [$name, $label, $type, $o]) {
        $max = $o['max'] ?? 5000;
        $v = match ($type) {
            'number' => (string)(int)($_POST[$name] ?? 0),
            'markdown' => post_text($name, 20000),
            'textarea' => post_text($name, $max),
            default => post_str($name, $max),
        };
        if (!empty($o['required']) && $v === '') {
            $err = "Please fill in: $label.";
        }
        if ($type === 'select' && $v !== '' && !isset($o['options'][$v])) {
            $err = "Invalid choice for: $label.";
        }
        if ($type === 'url' && $v !== '' && !preg_match('#^https?://#i', $v)) {
            $err = "$label must start with http:// or https://";
        }
        $data[$name] = $type === 'number' ? (int)$v : $v;
    }
    if ($e === 'links' && !$err) {
        $u = $data['url'];
        if (preg_match('#^\s*(javascript|data|vbscript):#i', $u)) {
            $err = 'That link address is not allowed.';
        }
    }
    if ($err) {
        keep_old($_POST);
        flash('error', $err);
        redirect('admin/' . $e . '/' . ($id ?: 'new'));
    }
    $data['published'] = !empty($_POST['published']) ? 1 : 0;
    $data['updated_at'] = now();
    if ($e === 'services') {
        $data['icon'] = service_categories()[$data['category']]['icon'] ?? 'file';
    }
    if ($old) {
        if ($e === 'services' && $data['title'] !== $old['title']) {
            $data['slug'] = unique_slug('services', $data['title'], $id);
        }
        db_update($d['table'], $data, 'id = ?', [$id]);
    } else {
        if ($e === 'services') {
            $data['slug'] = unique_slug('services', $data['title']);
        }
        if ($e === 'links') {
            $data['grp'] = 'important';
        }
        if (empty($data['sort_order'])) {
            $data['sort_order'] = (int)db_val('SELECT COALESCE(MAX(sort_order),0) FROM ' . $d['table']) + 10;
        }
        $data['created_at'] = now();
        db_insert($d['table'], $data);
    }
    clear_old();
    flash('success', ucfirst($d['singular']) . ' saved.');
    redirect('admin/' . $e);
}

function admin_entity_toggle(string $e, string $id): void
{
    require_cap('content');
    $d = entity_def($e);
    $row = db_one('SELECT id, published FROM ' . $d['table'] . ' WHERE id = ?', [(int)$id]) ?? http_error(404);
    db_update($d['table'], ['published' => $row['published'] ? 0 : 1, 'updated_at' => now()], 'id = ?', [$row['id']]);
    flash('success', $row['published'] ? 'Hidden from the website.' : 'Now published on the website.');
    redirect('admin/' . $e);
}

function admin_entity_delete(string $e, string $id): void
{
    require_cap('content');
    $d = entity_def($e);
    db_q('DELETE FROM ' . $d['table'] . ' WHERE id = ?', [(int)$id]);
    flash('success', 'Deleted.');
    redirect('admin/' . $e);
}

/* ============ legal pages ============ */

function admin_legal_list(): void
{
    require_cap('content');
    $rows = [];
    foreach (legal_slugs() as $slug => $title) {
        $r = db_one('SELECT * FROM legal_pages WHERE slug = ?', [$slug]);
        $rows[] = ['slug' => $slug, 'title' => $title, 'published' => (int)($r['published'] ?? 0),
            'pending' => str_contains((string)($r['body'] ?? ''), REVIEW_MARKER), 'updated_at' => $r['updated_at'] ?? null];
    }
    admin_render('legal_list', ['title' => 'Legal Pages', 'rows' => $rows, 'nav' => 'legal']);
}

function admin_legal_form(string $slug): void
{
    require_cap('content');
    isset(legal_slugs()[$slug]) || http_error(404);
    $r = db_one('SELECT * FROM legal_pages WHERE slug = ?', [$slug]);
    admin_render('legal_form', ['title' => legal_slugs()[$slug], 'slug' => $slug, 'r' => $r, 'nav' => 'legal']);
}

function admin_legal_save(string $slug): void
{
    require_cap('content');
    isset(legal_slugs()[$slug]) || http_error(404);
    $body = post_text('body', 80000);
    $publish = !empty($_POST['published']) ? 1 : 0;
    if ($publish && str_contains($body, REVIEW_MARKER)) {
        $publish = 0;
        flash('error', 'Saved, but not published: the text still contains ' . REVIEW_MARKER . ' markers. Complete those parts, remove the markers, then publish.');
    } else {
        flash('success', $publish ? 'Saved and published.' : 'Saved as draft (not visible on the website).');
    }
    $exists = db_one('SELECT id FROM legal_pages WHERE slug = ?', [$slug]);
    $data = ['body' => $body, 'published' => $publish, 'updated_at' => now()];
    if ($exists) {
        db_update('legal_pages', $data, 'slug = ?', [$slug]);
    } else {
        db_insert('legal_pages', $data + ['slug' => $slug, 'title' => legal_slugs()[$slug]]);
    }
    redirect('admin/legal/' . $slug);
}

/* ============ site settings ============ */

function admin_settings_index(): void
{
    require_cap('settings');
    admin_render('settings_index', ['title' => 'Site Settings', 'groups' => settings_groups(), 'nav' => 'settings']);
}

function admin_settings_form(string $group): void
{
    require_cap('settings');
    $g = settings_groups()[$group] ?? http_error(404);
    admin_render('settings_form', ['title' => $g['title'], 'group' => $group, 'g' => $g, 'values' => settings_all(), 'nav' => 'settings']);
}

function admin_settings_save(string $group): void
{
    require_cap('settings');
    $g = settings_groups()[$group] ?? http_error(404);
    $err = null;
    $newVals = [];
    $uploaded = [];
    foreach ($g['fields'] as $f) {
        $k = $f['key'];
        $max = $f['max'] ?? 5000;
        if ($f['type'] === 'image') {
            [$path, $e] = save_image_upload('img_' . $k, 1600);
            if ($e) {
                $err = $f['label'] . ': ' . $e;
                break;
            }
            if ($path) {
                $uploaded[] = $path;
                $newVals[$k] = $path;
            } elseif (!empty($_POST['clear_' . $k])) {
                $newVals[$k] = '';
            }
            continue;
        }
        $v = in_array($f['type'], ['textarea', 'markdown'], true) ? post_text($k, $max) : post_str($k, $max);
        if ($f['type'] === 'url' && $v !== '' && !preg_match('#^https?://#i', $v)) {
            $err = $f['label'] . ' must start with http:// or https://';
            break;
        }
        if ($f['type'] === 'email' && $v !== '' && !valid_email($v)) {
            $err = $f['label'] . ' is not a valid email address.';
            break;
        }
        if ($f['type'] === 'time' && $v !== '' && !preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $v)) {
            $err = $f['label'] . ' must be a valid time.';
            break;
        }
        $newVals[$k] = $v;
    }
    if ($err) {
        foreach ($uploaded as $p) {
            delete_upload($p);
        }
        keep_old($_POST);
        flash('error', $err);
        redirect('admin/settings/' . $group);
    }
    $current = settings_all();
    foreach ($newVals as $k => $v) {
        $oldVal = $current[$k] ?? '';
        if (str_starts_with($oldVal, 'uploads/') && $oldVal !== $v) {
            delete_upload($oldVal);
        }
        setting_save($k, $v);
    }
    clear_old();
    flash('success', 'Settings saved.');
    redirect('admin/settings/' . $group);
}

/* ============ users ============ */

function admin_users(): void
{
    require_cap('users');
    admin_render('users', ['title' => 'Users', 'rows' => db_all('SELECT id, name, email, role, active, last_login FROM users ORDER BY id'), 'nav' => 'users']);
}

function admin_users_post(): void
{
    $me = require_cap('users');
    $act = post_str('action', 12);
    if ($act === 'create') {
        $name = post_str('name', 120);
        $email = strtolower(post_str('email', 190));
        $role = post_str('role', 10) === 'admin' ? 'admin' : 'staff';
        $pw = (string)($_POST['password'] ?? '');
        if (mb_strlen($name) < 2 || !valid_email($email)) {
            flash('error', 'Please enter a name and a valid email address.');
        } elseif ($p = password_problem($pw)) {
            flash('error', $p);
        } elseif (db_one('SELECT id FROM users WHERE email = ?', [$email])) {
            flash('error', 'A user with that email already exists.');
        } else {
            db_insert('users', ['name' => $name, 'email' => $email, 'password_hash' => password_hash($pw, PASSWORD_DEFAULT), 'role' => $role, 'active' => 1, 'created_at' => now()]);
            flash('success', 'User created.');
        }
    } elseif (in_array($act, ['deactivate', 'activate', 'delete', 'reset'], true)) {
        $uid = (int)($_POST['uid'] ?? 0);
        $u = db_one('SELECT * FROM users WHERE id = ?', [$uid]);
        if (!$u) {
            flash('error', 'User not found.');
        } elseif ($uid === (int)$me['id'] && $act !== 'reset') {
            flash('error', 'You cannot change your own account here.');
        } elseif ($act === 'reset') {
            $pw = (string)($_POST['password'] ?? '');
            if ($p = password_problem($pw)) {
                flash('error', $p);
            } else {
                db_update('users', ['password_hash' => password_hash($pw, PASSWORD_DEFAULT)], 'id = ?', [$uid]);
                flash('success', 'Password reset.');
            }
        } elseif ($act === 'delete') {
            db_q('DELETE FROM users WHERE id = ?', [$uid]);
            flash('success', 'User deleted.');
        } else {
            db_update('users', ['active' => $act === 'activate' ? 1 : 0], 'id = ?', [$uid]);
            flash('success', 'User updated.');
        }
    }
    redirect('admin/users');
}
