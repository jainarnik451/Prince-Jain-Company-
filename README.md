# Prince Jain & Company: Website

A complete, database-driven website with a secure admin dashboard.
**Stack:** PHP 8.1+ and MySQL. No Node.js, no build step, no paid third-party accounts. This is the architecture that works on GoDaddy shared (cPanel) Linux hosting.

## What is included
* 10 public page groups: Home, About, Services (40 listed), Expertise, Insights (blog + article pages), Book a Consultation, Contact, Feedback, Additional Information, 4 legal pages, custom 404.
* Admin dashboard at `/admin`: dashboard counts, appointments (approve / reject / complete / notes), enquiries (read, status, delete), feedback moderation (approve / hide / delete), articles (draft / publish / unpublish / delete / image upload), services, expertise, timeline, additional information, footer links, legal pages, site settings (contact details, hours, social links, SEO, logo, photos), user accounts.
* Security: password hashing, login lockout, CSRF tokens, honeypot + timing + rate-limit spam protection, server-side validation, prepared SQL statements, safe image re-encoding, security headers, role-based access (admin / staff).
* SEO: unique titles and descriptions (editable), `sitemap.xml`, `robots.txt`, Open Graph, structured data (LocalBusiness with verified details only).

## Content safeguards (by design)
* Nothing invented: no ICAI number, awards, client counts or testimonials. Those fields are empty and hidden until you fill them in.
* Services in regulated areas (Tax Litigation, Asset Management, Investment-related, Debt-related, Business Restructuring) and the Asset Management / Investments expertise items are saved as **drafts**. They are not public until you publish them.
* The 4 legal pages are templates containing `[[CONFIRM]]` markers. The site **refuses to publish** a page while any marker remains. Visitors see a neutral "being prepared" notice until you finish.
* Feedback is never shown unless you approve it AND the person ticked the permission box. Emails are never published.
* Appointments are never auto-confirmed; the visitor is told the request is pending.

## Folder structure
```
app/            PHP code (keep OUTSIDE public_html)
  controllers/  public.php, admin.php
  lib/          helpers, db, security, auth, uploads, content settings
  views/        pages, partials, admin screens
  seed.php      starter content (editable later in admin)
public/         website files (goes INTO public_html)
  index.php     front controller
  assets/       css, js, images (photo cropped for the site)
  uploads/      images uploaded from admin (must be writable)
storage/        logs (outside public_html)
.env.example    settings template
```

## GoDaddy deployment (cPanel hosting)

1. **PHP version:** cPanel → *Select PHP Version* → choose 8.1 or newer. Make sure `pdo_mysql`, `mbstring`, `gd`, `fileinfo` are ticked.
2. **Database:** cPanel → *MySQL Databases* → create a database and a user, add the user to the database with ALL PRIVILEGES. Note the full names (they have your cPanel username prefix).
3. **Upload files** (cPanel File Manager or FTP):
   * Everything inside `public/` → into `public_html/` (include the hidden `.htaccess` files; in File Manager enable "Show Hidden Files").
   * `app/` and `storage/` → into the folder **above** `public_html` (your home folder, e.g. `/home/youruser/`).
   * Copy `.env.example` to `.env` in that same home folder (next to `app/`) and fill it in. Make `storage/logs` writable (chmod 755; use 775 if errors are not logged).
   * `public_html/uploads` must be writable (755, or 775 if uploads fail).
   * *If you cannot place folders above public_html:* put `app/`, `storage/` and `.env` inside `public_html/`. The included `.htaccess` files block public access to them, and the site still works.
4. **.env values:** set `APP_URL` (e.g. `https://www.yourdomain.com`), `DB_NAME`, `DB_USER`, `DB_PASS`, a long random `APP_SECRET` and `INSTALL_KEY`, and your `MAIL_FROM` (an address on your own domain).
5. **Install:** open `https://yourdomain.com/install`, enter the `INSTALL_KEY`, create the administrator account (password 10+ characters, letters and numbers). This creates the tables and the starter content. The installer disables itself afterwards; you may also delete `INSTALL_KEY` from `.env`.
6. **Sign in:** `https://yourdomain.com/admin`.
7. **HTTPS:** cPanel → *SSL/TLS Status* → run AutoSSL. Then un-comment the two HTTPS redirect lines in `public_html/.htaccess`.

### Connecting your domain
* **Domain registered at GoDaddy:** cPanel hosting on the same account normally points automatically. Check *Domains* in cPanel that the domain's document root is `public_html`.
* **Domain elsewhere:** point the nameservers to GoDaddy's, or set an A record to your hosting IP (shown in cPanel's right sidebar). DNS changes can take up to 24 hours.
* Add both `domain.com` and `www.domain.com`, and set `APP_URL` to the one you prefer.
* **Not on cPanel?** GoDaddy's *WordPress* plans and *Websites + Marketing* builder cannot run this PHP application; use a cPanel *Web Hosting* plan.

## After launch checklist
1. Admin → **Legal Pages**: complete and publish the four documents (have a professional review them).
2. Admin → **Services**: review wording against ICAI guidelines; publish the draft services only if they fall within your authorised scope.
3. Admin → **Site Settings**: add ICAI membership no., qualifications, tagline, mission/vision, office photos (all optional and hidden until filled).
4. Upload the final logo (Site Settings → Logo & Photographs).
5. Check an enquiry arrives in the dashboard and as an email (needs `NOTIFY_EMAIL` and `MAIL_FROM`). If the host blocks `mail()`, enquiries are still saved in the dashboard.
6. Submit `https://yourdomain.com/sitemap.xml` in Google Search Console and claim your Google Business Profile. No ranking is guaranteed.

## Local testing
```
cp .env.example .env     # set APP_ENV=local, DB_DRIVER=sqlite, INSTALL_KEY=...
touch storage/site.sqlite
php -S 127.0.0.1:8000 -t public
```
Then visit `/install`. SQLite is for testing only; production uses MySQL.

## Backups and updates
Back up the database (cPanel → *phpMyAdmin* → Export) and `public_html/uploads` regularly. Content changes are made in the admin; no code editing needed.
