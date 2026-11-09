<?php
declare(strict_types=1);

function request_path(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $path = rawurldecode($path);
    $bp = base_path();
    if ($bp !== '' && str_starts_with($path, $bp)) {
        $path = substr($path, strlen($bp));
    }
    $path = preg_replace('#/index\.php$#', '/', $path);
    return trim($path, '/');
}

function routes(): array
{
    return [
        // Public
        ['GET', '', 'page_home'],
        ['GET', 'about', 'page_about'],
        ['GET', 'services', 'page_services'],
        ['GET', 'expertise', 'page_expertise'],
        ['GET', 'insights', 'page_insights'],
        ['GET', 'insights/([a-z0-9\-]+)', 'page_article'],
        ['GET', 'book-consultation', 'page_book'],
        ['POST', 'book-consultation', 'post_book'],
        ['GET', 'contact', 'page_contact'],
        ['POST', 'contact', 'post_contact'],
        ['GET', 'feedback', 'page_feedback'],
        ['POST', 'feedback', 'post_feedback'],
        ['GET', 'additional-information', 'page_info'],
        ['GET', '(privacy-policy|terms-and-conditions|disclaimer|professional-disclosures)', 'page_legal'],
        ['GET', 'sitemap\.xml', 'page_sitemap'],
        ['GET', 'robots\.txt', 'page_robots'],

        // Installer
        ['GET', 'install', 'page_install'],
        ['POST', 'install', 'post_install'],

        // Admin
        ['GET', 'admin', 'admin_dashboard'],
        ['GET', 'admin/login', 'admin_login_form'],
        ['POST', 'admin/login', 'admin_login_post'],
        ['POST', 'admin/logout', 'admin_logout'],
        ['GET', 'admin/account', 'admin_account'],
        ['POST', 'admin/account', 'admin_account_post'],

        ['GET', 'admin/appointments', 'admin_appointments'],
        ['GET', 'admin/appointments/(\d+)', 'admin_appointment_view'],
        ['POST', 'admin/appointments/(\d+)', 'admin_appointment_save'],
        ['POST', 'admin/appointments/(\d+)/delete', 'admin_appointment_delete'],

        ['GET', 'admin/enquiries', 'admin_enquiries'],
        ['GET', 'admin/enquiries/(\d+)', 'admin_enquiry_view'],
        ['POST', 'admin/enquiries/(\d+)', 'admin_enquiry_save'],
        ['POST', 'admin/enquiries/(\d+)/delete', 'admin_enquiry_delete'],

        ['GET', 'admin/feedback', 'admin_feedback'],
        ['POST', 'admin/feedback/(\d+)', 'admin_feedback_action'],

        ['GET', 'admin/articles', 'admin_articles'],
        ['GET', 'admin/articles/new', 'admin_article_form'],
        ['GET', 'admin/articles/(\d+)', 'admin_article_form'],
        ['POST', 'admin/articles/save', 'admin_article_save'],
        ['POST', 'admin/articles/(\d+)/toggle', 'admin_article_toggle'],
        ['POST', 'admin/articles/(\d+)/delete', 'admin_article_delete'],

        ['GET', 'admin/(services|expertise|timeline|info|links)', 'admin_entity_list'],
        ['GET', 'admin/(services|expertise|timeline|info|links)/(new|\d+)', 'admin_entity_form'],
        ['POST', 'admin/(services|expertise|timeline|info|links)/save', 'admin_entity_save'],
        ['POST', 'admin/(services|expertise|timeline|info|links)/(\d+)/delete', 'admin_entity_delete'],
        ['POST', 'admin/(services|expertise|timeline|info|links)/(\d+)/toggle', 'admin_entity_toggle'],

        ['GET', 'admin/legal', 'admin_legal_list'],
        ['GET', 'admin/legal/([a-z\-]+)', 'admin_legal_form'],
        ['POST', 'admin/legal/([a-z\-]+)', 'admin_legal_save'],

        ['GET', 'admin/settings', 'admin_settings_index'],
        ['GET', 'admin/settings/([a-z]+)', 'admin_settings_form'],
        ['POST', 'admin/settings/([a-z]+)', 'admin_settings_save'],

        ['GET', 'admin/users', 'admin_users'],
        ['POST', 'admin/users', 'admin_users_post'],
    ];
}

function dispatch(): void
{
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    if ($method === 'HEAD') {
        $method = 'GET';
    }
    $path = request_path();
    $pathMatched = false;

    try {
        // Fresh deployment: send everything to the one-time installer.
        if ($path !== 'install' && $path !== 'robots.txt' && !db_installed()) {
            redirect('install');
        }
        foreach (routes() as [$m, $pattern, $handler]) {
            if (preg_match('#^' . $pattern . '$#', $path, $mm)) {
                $pathMatched = true;
                if ($m !== $method) {
                    continue;
                }
                array_shift($mm);
                if ($method === 'POST') {
                    csrf_check();
                }
                $handler(...$mm);
                return;
            }
        }
        if ($pathMatched) {
            header('Allow: GET, POST');
            http_error(405, 'This page does not support that request.');
        }
        http_error(404);
    } catch (Throwable $e) {
        error_log('[' . date('c') . '] ' . $e::class . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        if (IS_LOCAL) {
            http_response_code(500);
            echo '<pre style="white-space:pre-wrap;padding:1rem">' . e((string)$e) . '</pre>';
            return;
        }
        if (!headers_sent()) {
            http_response_code(500);
        }
        try {
            render('pages/error', ['title' => 'Something went wrong', 'noindex' => true, 'description' => '', 'code' => 500,
                'message' => 'We are sorry, something went wrong on our side. Please try again shortly or contact us directly.']);
        } catch (Throwable $e2) {
            echo 'Something went wrong. Please try again later.';
        }
    }
}
