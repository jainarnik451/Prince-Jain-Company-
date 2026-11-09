<?php
declare(strict_types=1);

/* ============================================================
 * Editable site settings (managed in Admin → Site Settings)
 * Each field: key, label, type (text|textarea|markdown|url|email|image|time), default, help
 * Empty optional values are simply not shown on the public site.
 * ============================================================ */

function settings_groups(): array
{
    $seoDefaults = [
        'home' => ['Home', 'Chartered Accountant in Thane | Prince Jain & Company',
            'Prince Jain & Company: CA firm in Thane offering income tax, GST, audit, accounting, financial advisory and business consultancy services since 2001.'],
        'about' => ['About Us', 'About CA Prince Jain | Prince Jain & Company, Thane',
            'Meet CA Prince Jain, a Chartered Accountant with 25+ years of experience, and learn about Prince Jain & Company, established in Thane in 2001.'],
        'services' => ['Services', 'Accounting, Tax & GST Services in Thane | Prince Jain & Company',
            'Income tax, GST, audit, accounting, business consultancy and financial advisory services from a Chartered Accountant firm in Thane, Maharashtra.'],
        'expertise' => ['Areas of Expertise', 'Areas of Expertise | CA Prince Jain, Thane',
            'Explore the professional expertise of CA Prince Jain across accounting, taxation, financial planning, analysis, budgeting and corporate finance.'],
        'insights' => ['Insights', 'Insights & Articles | Prince Jain & Company',
            'Articles and updates on income tax, GST, accounting, finance and business advisory from Prince Jain & Company, Thane.'],
        'book' => ['Book a Consultation', 'Book a Consultation | Prince Jain & Company, Thane',
            'Request a consultation with CA Prince Jain in Thane. Online and in-person requests are confirmed by the firm after review.'],
        'contact' => ['Contact', 'Contact Prince Jain & Company | CA Firm in Thane',
            'Contact Prince Jain & Company at Tembhi Naka, Thane West. Call, WhatsApp, email or send an enquiry. Open Monday to Saturday.'],
        'feedback' => ['Feedback', 'Share Your Feedback | Prince Jain & Company',
            'Share your feedback or suggestions about your experience with Prince Jain & Company, Chartered Accountants, Thane.'],
        'info' => ['Additional Information', 'Additional Information | Prince Jain & Company',
            'Announcements, FAQs, updates and additional information from Prince Jain & Company, Chartered Accountants in Thane.'],
    ];
    $seoFields = [];
    foreach ($seoDefaults as $k => [$label, $t, $d]) {
        $seoFields[] = ['key' => "seo_{$k}_title", 'label' => "$label — page title", 'type' => 'text', 'default' => $t, 'max' => 70,
            'help' => 'Ideal length: under 60 characters.'];
        $seoFields[] = ['key' => "seo_{$k}_desc", 'label' => "$label — meta description", 'type' => 'textarea', 'default' => $d, 'max' => 200,
            'help' => 'Ideal length: 120–155 characters.'];
    }

    return [
        'site' => [
            'title' => 'Firm & Contact Details',
            'desc' => 'Core business information shown across the website.',
            'fields' => [
                ['key' => 'firm_name', 'label' => 'Firm name', 'type' => 'text', 'default' => 'Prince Jain & Company'],
                ['key' => 'ca_name', 'label' => 'CA name', 'type' => 'text', 'default' => 'Prince Jain'],
                ['key' => 'designation', 'label' => 'Designation', 'type' => 'text', 'default' => 'Chartered Accountant'],
                ['key' => 'established', 'label' => 'Year established', 'type' => 'text', 'default' => '2001'],
                ['key' => 'experience_years', 'label' => 'Years of experience', 'type' => 'text', 'default' => '25+'],
                ['key' => 'qualification', 'label' => 'Qualification', 'type' => 'text', 'default' => 'Chartered Accountant (CA)'],
                ['key' => 'tagline', 'label' => 'Tagline (optional)', 'type' => 'text', 'default' => '', 'help' => 'PLACEHOLDER — shown in the footer only once you add one.'],
                ['key' => 'icai_membership', 'label' => 'ICAI membership number (optional)', 'type' => 'text', 'default' => '', 'help' => 'PLACEHOLDER — shown on About and Professional Disclosures only once you add it. Enter only your real, verified number.'],
                ['key' => 'additional_qualifications', 'label' => 'Additional qualifications (optional)', 'type' => 'textarea', 'default' => '', 'help' => 'PLACEHOLDER — one per line. Shown on the About page only when filled in.'],
                ['key' => 'achievements', 'label' => 'Professional achievements (optional)', 'type' => 'textarea', 'default' => '', 'help' => 'PLACEHOLDER — one per line. Shown on the About page only when filled in. Add only verified facts.'],
                ['key' => 'address', 'label' => 'Office address', 'type' => 'textarea', 'default' => 'Sukhi Paras Niwas, 7, Agyari Lane, Tembhi Naka, Charai, Thane West, Thane, Maharashtra 400601'],
                ['key' => 'phone', 'label' => 'Official phone number', 'type' => 'text', 'default' => '+91 98694 81384'],
                ['key' => 'whatsapp_1', 'label' => 'WhatsApp number 1 (used for floating button)', 'type' => 'text', 'default' => '+91 74987 32698'],
                ['key' => 'whatsapp_2', 'label' => 'WhatsApp number 2', 'type' => 'text', 'default' => '+91 98694 81384'],
                ['key' => 'email', 'label' => 'Professional email', 'type' => 'email', 'default' => 'pj4@yahoo.com'],
                ['key' => 'maps_url', 'label' => 'Google Maps link', 'type' => 'url', 'default' => 'https://share.google/RnZg5hZUCICEqjBow'],
                ['key' => 'hours_wk_open', 'label' => 'Monday–Friday opens', 'type' => 'time', 'default' => '10:00'],
                ['key' => 'hours_wk_close', 'label' => 'Monday–Friday closes', 'type' => 'time', 'default' => '20:00'],
                ['key' => 'hours_sat_open', 'label' => 'Saturday opens', 'type' => 'time', 'default' => '10:00'],
                ['key' => 'hours_sat_close', 'label' => 'Saturday closes', 'type' => 'time', 'default' => '19:00', 'help' => 'Sunday is treated as closed. Consultation time slots follow these hours.'],
            ],
        ],
        'social' => [
            'title' => 'Social Media Links',
            'desc' => 'Leave blank to hide a link.',
            'fields' => [
                ['key' => 'linkedin_url', 'label' => 'LinkedIn', 'type' => 'url', 'default' => 'https://in.linkedin.com/in/prince-jain-0bb6272b'],
                ['key' => 'facebook_url', 'label' => 'Facebook', 'type' => 'url', 'default' => '', 'help' => 'PLACEHOLDER'],
                ['key' => 'instagram_url', 'label' => 'Instagram', 'type' => 'url', 'default' => '', 'help' => 'PLACEHOLDER'],
                ['key' => 'x_url', 'label' => 'X (Twitter)', 'type' => 'url', 'default' => '', 'help' => 'PLACEHOLDER'],
                ['key' => 'youtube_url', 'label' => 'YouTube', 'type' => 'url', 'default' => '', 'help' => 'PLACEHOLDER'],
            ],
        ],
        'home' => [
            'title' => 'Homepage Content',
            'desc' => 'Text shown on the homepage.',
            'fields' => [
                ['key' => 'hero_headline', 'label' => 'Hero headline', 'type' => 'text', 'default' => 'Strategic Financial Guidance. Trusted Professional Excellence.', 'max' => 120],
                ['key' => 'hero_subtext', 'label' => 'Hero supporting text', 'type' => 'textarea', 'default' => 'Delivering professional accounting, taxation, financial planning and business advisory services backed by decades of experience.', 'max' => 260],
                ['key' => 'hero_intro', 'label' => 'Hero introduction (under photo / badge)', 'type' => 'text', 'default' => 'CA Prince Jain, Chartered Accountant with 25+ years of professional experience, serving clients from Thane since 2001.', 'max' => 200],
                ['key' => 'home_intro_text', 'label' => 'About introduction', 'type' => 'markdown', 'default' => "Prince Jain & Company is a Chartered Accountancy practice based in Thane, Maharashtra, established in 2001.\n\nLed by CA Prince Jain, the firm is committed to professional accounting, taxation, financial management and business consultancy, delivered with attention to professional standards and a client-focused approach."],
                ['key' => 'home_why', 'label' => 'Why choose us (one per line: Title | Description)', 'type' => 'textarea', 'default' => implode("\n", [
                    'Extensive professional experience | Over 25 years in practice as a Chartered Accountant, with the firm established in 2001.',
                    'Strategic financial approach | Advice that looks beyond compliance to support sound financial decisions.',
                    'Professional accounting expertise | Careful, accurate accounting, taxation and financial reporting work.',
                    "Client-focused services | Clear communication and services shaped around each client's situation.",
                    'Business and financial planning | Support with budgeting, planning and financial analysis.',
                    'Commitment to professional standards | Work carried out in line with the standards of the Chartered Accountancy profession.',
                ]), 'help' => 'Keep these factual. Avoid guarantees or unverifiable claims.'],
                ['key' => 'cta_title', 'label' => 'Contact banner title', 'type' => 'text', 'default' => 'Looking for Professional Financial Guidance?'],
                ['key' => 'cta_text', 'label' => 'Contact banner text', 'type' => 'textarea', 'default' => "Let's discuss how we can assist you with your accounting, taxation and business requirements."],
            ],
        ],
        'about' => [
            'title' => 'About Page Content',
            'desc' => 'Draft copy is based only on verified facts. Please review and refine it before launch.',
            'fields' => [
                ['key' => 'about_bio', 'label' => 'About Prince Jain', 'type' => 'markdown', 'default' => "Prince Jain is a Chartered Accountant with over 25 years of professional experience in accounting, taxation, financial management and business consultancy.\n\nHe established Prince Jain & Company in 2001 in Thane, Maharashtra, and the firm serves individuals and businesses with their accounting, compliance and financial planning needs."],
                ['key' => 'about_journey', 'label' => 'Professional journey / firm history', 'type' => 'markdown', 'default' => "Prince Jain & Company was established in 2001 with a focus on dependable professional accounting and taxation services.\n\nToday the firm's practice covers taxation, GST, audit and compliance, accounting, business consultancy and financial advisory."],
                ['key' => 'about_approach', 'label' => 'Professional philosophy / approach', 'type' => 'markdown', 'default' => "Every engagement begins with understanding the client's situation. The firm aims to give clear, practical and compliant advice, explained in plain language, so that clients can make informed decisions about their finances and business."],
                ['key' => 'mission', 'label' => 'Mission (optional)', 'type' => 'markdown', 'default' => '', 'help' => 'PLACEHOLDER — shown on the About page only once you add it.'],
                ['key' => 'vision', 'label' => 'Vision (optional)', 'type' => 'markdown', 'default' => '', 'help' => 'PLACEHOLDER — shown on the About page only once you add it.'],
            ],
        ],
        'branding' => [
            'title' => 'Logo & Photographs',
            'desc' => 'Upload JPG, PNG or WebP images (max 5 MB each).',
            'fields' => [
                ['key' => 'logo_mark', 'label' => 'Text logo initials', 'type' => 'text', 'default' => 'PJ', 'max' => 3, 'help' => 'Used for the text-based logo until an image logo is uploaded.'],
                ['key' => 'logo_image', 'label' => 'Logo image (optional)', 'type' => 'image', 'default' => '', 'help' => 'When uploaded, replaces the text logo in the header and footer. Transparent PNG recommended.'],
                ['key' => 'profile_photo', 'label' => 'Profile photograph', 'type' => 'image', 'default' => 'assets/img/prince-jain.jpg', 'help' => 'Portrait orientation (4:5) works best. Currently using the supplied photograph.'],
                ['key' => 'office_photo_1', 'label' => 'Office photograph 1 (optional)', 'type' => 'image', 'default' => '', 'help' => 'PLACEHOLDER — a gallery appears on the About page when photos are added.'],
                ['key' => 'office_photo_2', 'label' => 'Office photograph 2 (optional)', 'type' => 'image', 'default' => ''],
                ['key' => 'office_photo_3', 'label' => 'Office photograph 3 (optional)', 'type' => 'image', 'default' => ''],
            ],
        ],
        'blog' => [
            'title' => 'Blog Settings',
            'desc' => 'Categories available when writing articles.',
            'fields' => [
                ['key' => 'blog_categories', 'label' => 'Article categories (one per line)', 'type' => 'textarea', 'default' => implode("\n", [
                    'Income Tax', 'GST', 'Accounting', 'Finance', 'Business Advisory', 'Financial Planning', 'Regulatory Updates',
                ])],
            ],
        ],
        'seo' => [
            'title' => 'SEO Titles & Descriptions',
            'desc' => 'Controls how each page appears in Google results and social shares. No ranking is guaranteed.',
            'fields' => array_merge($seoFields, [
                ['key' => 'seo_og_image', 'label' => 'Default social share image', 'type' => 'image', 'default' => 'assets/img/og-default.jpg', 'help' => 'Recommended 1200×630.'],
            ]),
        ],
    ];
}

function setting_defaults(): array
{
    static $d = null;
    if ($d === null) {
        $d = [];
        foreach (settings_groups() as $g) {
            foreach ($g['fields'] as $f) {
                $d[$f['key']] = $f['default'];
            }
        }
    }
    return $d;
}

function settings_all(bool $refresh = false): array
{
    static $cache = null;
    if ($cache === null || $refresh) {
        $cache = setting_defaults();
        try {
            foreach (db_all('SELECT skey, svalue FROM settings') as $r) {
                if (array_key_exists($r['skey'], $cache)) {
                    $cache[$r['skey']] = (string)$r['svalue'];
                }
            }
        } catch (Throwable $e) {
            // Database not ready; defaults are used.
        }
    }
    return $cache;
}

function setting(string $key, string $default = ''): string
{
    $all = settings_all();
    return isset($all[$key]) && $all[$key] !== '' ? (string)$all[$key] : $default;
}

function setting_save(string $key, string $value): void
{
    $exists = db_one('SELECT skey FROM settings WHERE skey = ?', [$key]);
    if ($exists) {
        db_update('settings', ['svalue' => $value, 'updated_at' => now()], 'skey = ?', [$key]);
    } else {
        db_insert('settings', ['skey' => $key, 'svalue' => $value, 'updated_at' => now()]);
    }
    settings_all(true);
}

/* ---------- Derived helpers ---------- */

function lines(string $text): array
{
    return array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", '', $text))), fn($l) => $l !== ''));
}

function hours_config(): array
{
    return [
        'weekday' => [setting('hours_wk_open', '10:00'), setting('hours_wk_close', '20:00')],
        'saturday' => [setting('hours_sat_open', '10:00'), setting('hours_sat_close', '19:00')],
    ];
}

function office_hours_rows(): array
{
    $h = hours_config();
    return [
        ['Monday – Friday', time_label($h['weekday'][0]) . ' – ' . time_label($h['weekday'][1])],
        ['Saturday', time_label($h['saturday'][0]) . ' – ' . time_label($h['saturday'][1])],
        ['Sunday', 'Closed'],
    ];
}

/** 30-minute consultation slots for a given Y-m-d date; empty on Sundays. */
function time_slots(string $ymd): array
{
    $t = strtotime($ymd);
    if (!$t) {
        return [];
    }
    $dow = (int)date('N', $t);
    if ($dow === 7) {
        return [];
    }
    [$open, $close] = $dow === 6 ? hours_config()['saturday'] : hours_config()['weekday'];
    $slots = [];
    $cur = strtotime("2000-01-01 $open");
    $end = strtotime("2000-01-01 $close") - 1800;
    while ($cur <= $end) {
        $slots[date('H:i', $cur)] = date('g:i A', $cur);
        $cur += 1800;
    }
    return $slots;
}

function link_href(string $u): string
{
    if ($u === '{linkedin}') {
        return setting('linkedin_url');
    }
    if (preg_match('#^(https?:|mailto:|tel:)#i', $u)) {
        return $u;
    }
    return url($u);
}

function social_links(): array
{
    $out = [];
    foreach ([
        'linkedin_url' => ['LinkedIn', 'linkedin'],
        'facebook_url' => ['Facebook', 'facebook'],
        'instagram_url' => ['Instagram', 'instagram'],
        'x_url' => ['X', 'x'],
        'youtube_url' => ['YouTube', 'youtube'],
    ] as $k => [$label, $ic]) {
        $u = setting($k);
        if ($u !== '' && preg_match('#^https?://#i', $u)) {
            $out[] = ['label' => $label, 'icon' => $ic, 'url' => $u];
        }
    }
    return $out;
}

function service_categories(): array
{
    return [
        'taxation' => ['label' => 'Taxation Services', 'short' => 'Income Tax', 'icon' => 'receipt',
            'blurb' => 'Income tax returns, tax planning, TDS and tax advisory for individuals and businesses.'],
        'gst' => ['label' => 'GST Services', 'short' => 'GST Services', 'icon' => 'percent',
            'blurb' => 'GST registration, return filing, advisory and compliance support.'],
        'audit' => ['label' => 'Audit & Compliance', 'short' => 'Audit', 'icon' => 'shield',
            'blurb' => 'Statutory, internal and tax audit along with ROC and regulatory compliance.'],
        'accounting' => ['label' => 'Accounting Services', 'short' => 'Accounting', 'icon' => 'calculator',
            'blurb' => 'Bookkeeping, payroll accounting, financial statements and reporting.'],
        'business' => ['label' => 'Business Consultancy', 'short' => 'Business Consultancy', 'icon' => 'briefcase',
            'blurb' => 'Start-up support, company and LLP registration, business and strategic planning.'],
        'advisory' => ['label' => 'Financial Advisory', 'short' => 'Financial Advisory', 'icon' => 'trending',
            'blurb' => 'Financial planning and analysis, budgeting, corporate finance and due diligence.'],
        'other' => ['label' => 'Other Professional Services', 'short' => 'Other Services', 'icon' => 'layers',
            'blurb' => 'Additional professional and consultancy services.'],
    ];
}

function blog_categories(): array
{
    return lines(setting('blog_categories'));
}

function service_titles(): array
{
    try {
        return array_column(db_all('SELECT title FROM services WHERE published = 1 ORDER BY category, sort_order, id'), 'title');
    } catch (Throwable $e) {
        return [];
    }
}

function legal_slugs(): array
{
    return [
        'privacy-policy' => 'Privacy Policy',
        'terms-and-conditions' => 'Terms and Conditions',
        'disclaimer' => 'Website Disclaimer',
        'professional-disclosures' => 'Professional Disclosures',
    ];
}

function info_sections(): array
{
    return [
        'testimonial' => ['Client Testimonials', 'quote', 'Testimonials appear here once the firm has verified client permission.'],
        'faq' => ['Frequently Asked Questions', 'help', 'Frequently asked questions will be added here soon.'],
        'announcement' => ['Important Announcements', 'megaphone', 'There are no announcements at the moment.'],
        'update' => ['Professional Updates', 'file', 'Professional updates will be shared here.'],
        'news' => ['Firm News', 'newspaper', 'Firm news will be shared here.'],
        'additional' => ['Additional Professional Information', 'info', 'Additional information will be added here.'],
    ];
}

function page_seo(string $key): array
{
    return [setting("seo_{$key}_title"), setting("seo_{$key}_desc")];
}

function local_business_schema(): array
{
    $addr = setting('address');
    $d = [
        '@context' => 'https://schema.org',
        '@type' => 'AccountingService',
        'name' => setting('firm_name'),
        'url' => abs_url(),
        'telephone' => '+' . intl_digits(setting('phone')),
        'email' => setting('email'),
        'foundingDate' => setting('established'),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => 'Sukhi Paras Niwas, 7, Agyari Lane, Tembhi Naka, Charai, Thane West',
            'addressLocality' => 'Thane',
            'addressRegion' => 'Maharashtra',
            'postalCode' => '400601',
            'addressCountry' => 'IN',
        ],
        'openingHoursSpecification' => [
            ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'opens' => hours_config()['weekday'][0], 'closes' => hours_config()['weekday'][1]],
            ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => 'Saturday',
                'opens' => hours_config()['saturday'][0], 'closes' => hours_config()['saturday'][1]],
        ],
        'founder' => ['@type' => 'Person', 'name' => setting('ca_name'), 'jobTitle' => setting('designation')],
    ];
    $same = array_column(social_links(), 'url');
    if ($same) {
        $d['sameAs'] = $same;
    }
    if ($img = setting('profile_photo')) {
        $d['image'] = abs_url($img);
    }
    return $d;
}
