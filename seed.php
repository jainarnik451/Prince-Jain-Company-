<?php
declare(strict_types=1);

/**
 * Initial content. Everything here is editable from the admin dashboard.
 * Services that touch regulated areas (litigation, investments, asset management,
 * debt and restructuring) are seeded as DRAFTS so nothing is published until
 * the CA confirms the firm's authorised scope.
 */

function seed_services(): array
{
    $S = fn($c, $t, $short, $desc, $who, $key, $pub = 1, $note = '') => [
        'category' => $c, 'title' => $t, 'short_desc' => $short, 'description' => $desc,
        'who_benefits' => $who, 'key_info' => implode("\n", $key), 'published' => $pub, 'admin_note' => $note,
    ];
    $review = 'DRAFT: confirm this falls within the firm\'s authorised professional scope (ICAI / other regulators) before publishing.';

    return [
        // ---- Taxation ----
        $S('taxation', 'Individual Income Tax Return Preparation',
            'Careful preparation of income tax returns for salaried individuals, professionals and investors.',
            'We prepare your income tax return from the documents and information you provide, considering the income heads, deductions and exemptions that apply to your situation.',
            'Salaried individuals, professionals, senior citizens, investors and people with more than one source of income.',
            ['Review of income details and supporting documents', 'Applicable deductions and exemptions considered', 'Return prepared for your review before filing']),
        $S('taxation', 'Business Tax Return Preparation',
            'Income tax return preparation for proprietors, partnership firms, LLPs and companies.',
            'We compile the financial information of your business and prepare its income tax return in line with the applicable provisions.',
            'Proprietary concerns, partnership firms, LLPs and companies.',
            ['Preparation based on books of account and financial statements', 'Computation of business income and tax', 'Coordination with audit and accounting where applicable']),
        $S('taxation', 'Income Tax Return Filing',
            'Electronic filing of income tax returns with acknowledgement and verification guidance.',
            'Once the return is prepared and approved by you, we file it electronically on the income tax portal and guide you through the verification step.',
            'Individuals and businesses who want their returns filed correctly and on time.',
            ['Electronic filing on the official portal', 'Guidance on return verification', 'Copy of the filed return and acknowledgement']),
        $S('taxation', 'Individual Tax Planning',
            'Planning your personal finances with taxes in mind, within the framework of the law.',
            'We review your income and investments to explain the tax-saving provisions available to you and help you plan ahead for the year.',
            'Salaried individuals, professionals, business owners and families planning their finances.',
            ['Review of your income and commitments', 'Explanation of applicable provisions', 'Planning done within the law']),
        $S('taxation', 'Business Tax Planning',
            'Structured planning of business taxes to support informed decisions.',
            'We help businesses understand their tax position and plan transactions and compliance calendars so that decisions are made with the tax impact in view.',
            'Business owners, partners and directors.',
            ['Review of the business tax position', 'Advance planning of tax outflows', 'Compliant, documented approach']),
        $S('taxation', 'Tax Advisory',
            'Professional guidance on income tax questions for individuals and businesses.',
            'We help you understand how tax provisions apply to specific transactions, income and decisions before you act.',
            'Anyone who needs clarity on the tax treatment of a transaction or decision.',
            ['Advice based on your individual circumstances', 'Clear explanation of the applicable provisions', 'Written summary on request']),
        $S('taxation', 'TDS Return Filing',
            'Preparation and filing of TDS returns for deductors.',
            'We help deductors prepare and file their TDS returns and reconcile the details with the information available on the portal.',
            'Businesses, firms, professionals and organisations that deduct tax at source.',
            ['Preparation of periodic TDS returns', 'Reconciliation of deduction and deposit details', 'Support with corrections where needed']),
        $S('taxation', 'Tax Authority Representation and Resolution',
            'Help in understanding notices and preparing responses to tax authorities.',
            'If you receive a notice or intimation, we help you understand what is being asked, gather the right information and prepare an appropriate response.',
            'Individuals and businesses who have received a communication from a tax authority.',
            ['Review of the notice or intimation', 'Preparation of a documented response', 'Follow-up as required'], 1,
            'Check wording against the firm\'s authorised scope of representation before launch.'),
        $S('taxation', 'Tax Litigation Assistance',
            'Assistance in tax disputes, subject to the firm\'s authorised professional scope.',
            'Support with tax assessments and appeals to the extent permitted within the firm\'s authorised professional scope.',
            'Taxpayers involved in assessments or disputes.',
            ['Subject to authorised professional scope', 'Case-by-case assessment'], 0, $review),

        // ---- GST ----
        $S('gst', 'GST Registration',
            'Assistance with obtaining GST registration for your business.',
            'We help you determine whether registration applies to you and support the application process with the required documents.',
            'New businesses, traders, service providers and e-commerce sellers.',
            ['Assessment of registration requirements', 'Preparation and submission of the application', 'Guidance on documents needed']),
        $S('gst', 'GST Return Filing',
            'Periodic GST return preparation and filing.',
            'We prepare and file your periodic GST returns based on your sales and purchase records and reconcile them with portal data.',
            'Registered businesses, traders and service providers.',
            ['Periodic preparation and filing', 'Reconciliation of input tax credit data', 'Reminders for upcoming compliance']),
        $S('gst', 'GST Advisory',
            'Guidance on how GST applies to your transactions.',
            'We help you understand GST treatment, rates and documentation for your business activities and specific transactions.',
            'Businesses with questions on GST applicability, invoicing or input tax credit.',
            ['Advice on specific transactions', 'Support with invoicing and documentation', 'Clear written guidance on request']),
        $S('gst', 'GST Compliance',
            'Support with ongoing GST compliance and record-keeping.',
            'We help you keep the records, reconciliations and filings needed to stay compliant with GST requirements.',
            'Registered businesses wanting a dependable compliance routine.',
            ['Record-keeping guidance', 'Regular reconciliations', 'Compliance calendar support']),

        // ---- Audit & Compliance ----
        $S('audit', 'Statutory Audit',
            'Independent audit of financial statements as required by law.',
            'We carry out statutory audits in line with the auditing standards and professional requirements applicable to Chartered Accountants.',
            'Companies, LLPs and other entities that require a statutory audit.',
            ['Conducted in line with applicable auditing standards', 'Subject to eligibility and independence requirements', 'Report issued on completion'], 1,
            'Confirm engagement acceptance criteria and wording.'),
        $S('audit', 'Internal Audit',
            'Review of internal processes and controls to support better management.',
            'We review operations, processes and controls and report observations that help management improve discipline and accuracy.',
            'Businesses and organisations that want an independent review of internal processes.',
            ['Scope agreed with management', 'Observations and recommendations reported', 'Periodic or one-time reviews']),
        $S('audit', 'Tax Audit',
            'Audit of accounts where required under the Income-tax Act.',
            'Where a tax audit is applicable, we examine the books of account and prepare the report in the prescribed manner.',
            'Businesses and professionals whose turnover or receipts require a tax audit.',
            ['Examination of books and records', 'Report in the prescribed form', 'Coordination with return filing']),
        $S('audit', 'ROC Compliance',
            'Support with annual and event-based filings with the Registrar of Companies.',
            'We assist companies and LLPs with their periodic filings and statutory records under company law.',
            'Private limited companies, other companies and LLPs.',
            ['Annual filing support', 'Event-based filings', 'Guidance on statutory records']),
        $S('audit', 'Regulatory Compliance',
            'Guidance on compliance requirements applicable to your business.',
            'We help you understand which regulatory requirements apply to you and keep track of the filings and records they involve.',
            'Business owners who want clarity on their compliance obligations.',
            ['Identification of applicable requirements', 'Compliance calendar support', 'Documentation guidance']),

        // ---- Accounting ----
        $S('accounting', 'Accounting and Bookkeeping',
            'Accurate day-to-day accounting and bookkeeping for businesses.',
            'We maintain your books of account so that your financial records are accurate, organised and ready for tax, GST and audit requirements.',
            'Small and medium businesses, professionals, firms and start-ups.',
            ['Regular recording of transactions', 'Reconciliation of bank and ledger balances', 'Records ready for compliance and audit']),
        $S('accounting', 'Payroll Accounting',
            'Accounting support for salary processing and related statutory records.',
            'We help with salary computation, accounting entries and the records related to payroll deductions.',
            'Employers and businesses with a team on payroll.',
            ['Salary computation support', 'Payroll entries in the books', 'Records for statutory deductions']),
        $S('accounting', 'Financial Statements',
            'Preparation of financial statements from your books of account.',
            'We prepare balance sheets and profit and loss statements that present your financial position clearly.',
            'Businesses, firms, LLPs and companies.',
            ['Prepared from verified books', 'Suitable for filing, banking and audit purposes', 'Clear presentation']),
        $S('accounting', 'Financial Reporting',
            'Periodic financial reports that help you understand how the business is doing.',
            'We prepare reports and summaries that make your numbers easier to read and use for decisions.',
            'Owners, partners and management teams who want regular visibility of their numbers.',
            ['Periodic management reports', 'Easy-to-read summaries', 'Aligned with your books of account']),

        // ---- Business Consultancy ----
        $S('business', 'Business Consulting',
            'Practical advice on financial and operational aspects of your business.',
            'We work with you to understand your business, identify financial priorities and plan sensible next steps.',
            'Owners and managers of growing and established businesses.',
            ['Discussion-based and practical', 'Focus on finance and compliance', 'Tailored to your business']),
        $S('business', 'Business Start-up Services',
            'Guidance for setting up a new business correctly from day one.',
            'We help you choose a suitable structure, understand registrations and compliance, and set up basic accounting for your new venture.',
            'First-time entrepreneurs and start-ups.',
            ['Structure and registration guidance', 'Initial compliance roadmap', 'Accounting set-up']),
        $S('business', 'Company Registration',
            'Assistance with incorporating a company.',
            'We help with the documentation and filings needed to incorporate a company and complete the initial post-incorporation steps.',
            'Entrepreneurs and groups of promoters planning to incorporate.',
            ['Guidance on documents and approvals', 'Filing support', 'Post-incorporation compliance pointers']),
        $S('business', 'LLP Registration',
            'Assistance with registering a Limited Liability Partnership.',
            'We help with the documentation and filings for forming an LLP and executing the LLP agreement.',
            'Professionals and entrepreneurs who prefer an LLP structure.',
            ['Guidance on structure and documents', 'Filing support', 'Compliance pointers']),
        $S('business', 'Business Planning',
            'Structured business plans that put your goals and numbers on paper.',
            'We help you prepare a business plan with financial projections that can support internal decisions or discussions with lenders.',
            'Business owners planning a new venture, expansion or funding discussion.',
            ['Clear goals and assumptions', 'Financial projections', 'Presentable format']),
        $S('business', 'Strategic Planning',
            'Longer-term planning aligned with your financial position.',
            'We help you think through medium and long-term priorities and connect them with what the numbers can support.',
            'Owners and leadership teams planning ahead.',
            ['Structured discussion and review', 'Financial lens on strategy', 'Written summary on request']),
        $S('business', 'Business Financial Planning',
            'Planning cash flows, funding and financial priorities for your business.',
            'We help you plan for working capital, major expenses and financing needs so that the business remains on a sound footing.',
            'Businesses managing cash flows, expansion or funding needs.',
            ['Cash flow and working capital review', 'Funding needs assessment', 'Periodic review']),

        // ---- Financial Advisory ----
        $S('advisory', 'Financial Advising',
            'General financial guidance on planning, cash flow and business finances.',
            'We help you understand your financial position and think through practical decisions. This is general financial and accounting guidance.',
            'Individuals, families and business owners who want a professional sounding board.',
            ['General guidance based on your circumstances', 'Not a substitute for regulated investment advice'], 1,
            'Confirm wording; avoid implying investment advisory services.'),
        $S('advisory', 'Strategic Financial Planning',
            'Planning that connects financial decisions to long-term goals.',
            'We help you set financial priorities, review your position and plan the steps ahead.',
            'Business owners and professionals with medium and long-term goals.',
            ['Goal-oriented planning', 'Periodic reviews', 'Clear, practical recommendations']),
        $S('advisory', 'Financial Analysis',
            'Analysis of financial statements and performance.',
            'We analyse your financial statements and key ratios to explain what they say about performance, liquidity and financial health.',
            'Business owners, partners and lenders\' counterparts who need clarity on the numbers.',
            ['Ratio and trend analysis', 'Plain-language explanation', 'Supports decision-making']),
        $S('advisory', 'Corporate Finance',
            'Support with financial structuring and funding-related questions for businesses.',
            'We help businesses think through capital needs, financing options and the financial implications of corporate decisions.',
            'Companies, LLPs and growing businesses.',
            ['Financing needs review', 'Financial modelling support', 'Documentation support']),
        $S('advisory', 'Budgeting',
            'Budgets and forecasts that help you plan and track spending.',
            'We help you prepare realistic budgets and compare them with actual results.',
            'Businesses, departments and individuals who want better control of spending.',
            ['Budget preparation', 'Budget versus actual review', 'Simple, usable formats']),
        $S('advisory', 'Due Diligence',
            'Financial review of a business or transaction before you commit.',
            'We review financial records and information to help you understand the position before a transaction.',
            'Buyers, investors, lenders and partners evaluating a business.',
            ['Scope agreed in advance', 'Review of financial records', 'Findings summarised in a report']),
        $S('advisory', 'Asset Management',
            'Accounting and record support for managing assets.',
            'Support with maintaining asset registers and records. Any regulated asset management activity is outside this description.',
            'Businesses and individuals who need to track assets.',
            ['Subject to authorised professional scope'], 0, $review),
        $S('advisory', 'Investment-related Financial Consultancy',
            'Financial consultancy related to investments, only within applicable professional permissions.',
            'Consultancy that falls within the permissions applicable to Chartered Accountants.',
            'To be confirmed.',
            ['Subject to applicable permissions'], 0, $review . ' Do not imply investment adviser registration.'),

        // ---- Other ----
        $S('other', 'Trusts and Estates Accounting',
            'Accounting support for trusts and estates.',
            'We help trusts and estates maintain accounts and prepare statements that meet their reporting requirements.',
            'Trustees, executors and beneficiaries.',
            ['Maintenance of accounts', 'Preparation of statements', 'Compliance support']),
        $S('other', 'Debt-related Financial Consultancy',
            'Financial consultancy on debt-related matters.',
            'Financial analysis and planning support related to borrowing and repayment. No legal or insolvency representation is implied.',
            'Businesses reviewing borrowing and repayment.',
            ['Financial analysis only', 'Subject to authorised professional scope'], 0, $review . ' Do not imply insolvency practice or legal representation.'),
        $S('other', 'Business Restructuring and Financial Consultancy',
            'Financial consultancy for business restructuring.',
            'Financial analysis and planning support during business restructuring. Legal and regulatory aspects are outside this description.',
            'Business owners considering reorganisation.',
            ['Financial analysis and planning', 'Subject to authorised professional scope'], 0, $review),
    ];
}

function seed_expertise(): array
{
    $E = fn($t, $d, $i, $pub = 1, $note = '') => ['title' => $t, 'description' => $d, 'icon' => $i, 'published' => $pub, 'admin_note' => $note];
    $review = 'DRAFT: confirm the firm\'s authorised scope before publishing. Avoid implying regulated investment services.';
    return [
        $E('Strategic Planning', 'Connecting long-term goals with practical, financially sound steps.', 'compass'),
        $E('Accounting', 'Accurate, organised accounting that stands up to compliance and audit.', 'calculator'),
        $E('Strategic Financial Planning', 'Planning financial priorities with a long-term view.', 'target'),
        $E('Financial Analysis', 'Making sense of financial statements, ratios and trends.', 'chart'),
        $E('Asset Management', 'Record and accounting support for tracking assets.', 'wallet', 0, $review),
        $E('Budgeting', 'Realistic budgets and forecasts that support day-to-day control.', 'pie'),
        $E('Business Planning', 'Structured plans with clear goals, assumptions and projections.', 'clipboard'),
        $E('Sales', 'Commercial perspective on revenue, pricing and the numbers behind sales.', 'trending'),
        $E('Negotiation', 'Experience in commercial and financial discussions.', 'message'),
        $E('Sales Management', 'Understanding sales performance through reporting and analysis.', 'users'),
        $E('Financial Reporting', 'Clear, timely financial reports that decision-makers can rely on.', 'file'),
        $E('Due Diligence', 'Reviewing financial information before important transactions.', 'search'),
        $E('Investments', 'Understanding of investment concepts as part of financial analysis.', 'layers', 0, $review),
        $E('Finance', 'Broad grounding in business and personal finance.', 'landmark'),
        $E('Corporate Finance', 'Financial thinking for company-level decisions and funding questions.', 'building'),
    ];
}

function seed_timeline(): array
{
    return [
        ['year' => '2001', 'title' => 'Prince Jain & Company established', 'description' => 'The firm began practice in Thane, Maharashtra.'],
        ['year' => 'Today', 'title' => 'Over 25 years of professional experience', 'description' => 'CA Prince Jain continues to lead the firm\'s accounting, taxation and advisory work.'],
    ];
}

function seed_links(): array
{
    return [
        ['Privacy Policy', 'privacy-policy'],
        ['Terms and Conditions', 'terms-and-conditions'],
        ['Disclaimer', 'disclaimer'],
        ['Professional Disclosures', 'professional-disclosures'],
        ['Additional Information', 'additional-information'],
        ['LinkedIn', '{linkedin}'],
    ];
}

/** Marker that blocks publication until the CA has completed/approved the text. */
const REVIEW_MARKER = '[[CONFIRM]]';

function seed_legal(): array
{
    $m = REVIEW_MARKER;
    return [
        'privacy-policy' => "## Privacy Policy\n\n> Template for review. {$m} The CA must review, complete and approve this text before it is published. Remove every {$m} marker when done.\n\n## 1. Who we are\n\nPrince Jain & Company, Thane, Maharashtra. {$m} Add the legal name and registration details the firm wishes to disclose.\n\n## 2. Information we collect\n\nInformation you submit through the contact, consultation and feedback forms (such as name, phone number, email address, service of interest and message). {$m} Confirm the list.\n\n## 3. How we use information\n\nTo respond to enquiries, arrange consultations and improve our services. {$m} Confirm purposes.\n\n## 4. Storage and security\n\n{$m} Describe how data is stored and protected, and retention periods.\n\n## 5. Sharing of information\n\n{$m} State whether and with whom information is shared.\n\n## 6. Your choices\n\n{$m} Describe how people can ask for access, correction or deletion.\n\n## 7. Contact\n\nQuestions about this policy can be sent to the email address shown on our Contact page.",
        'terms-and-conditions' => "## Terms and Conditions\n\n> Template for review. {$m} The CA must review, complete and approve this text before it is published. Remove every {$m} marker when done.\n\n## 1. Use of this website\n\nThis website is provided for general information about Prince Jain & Company and its services. {$m} Confirm.\n\n## 2. No professional engagement\n\nUse of this website or submission of a form does not create a professional engagement. Engagements are confirmed separately in writing. {$m} Confirm.\n\n## 3. Appointments\n\nConsultation requests are not confirmed until the firm approves them. {$m} Confirm.\n\n## 4. Intellectual property\n\n{$m} Add terms on use of website content.\n\n## 5. Limitation of liability\n\n{$m} To be drafted with professional or legal advice.\n\n## 6. Governing law\n\n{$m} Confirm governing law and jurisdiction.",
        'disclaimer' => "## Website Disclaimer\n\n> Template for review. {$m} The CA must review and approve this text before it is published. Remove every {$m} marker when done.\n\n## General information only\n\nThe content on this website is for general informational purposes only and does not constitute professional, legal, tax or financial advice. Professional advice depends on individual circumstances; please consult us before acting on any information here.\n\n## No guarantee\n\nWhile care is taken to keep the information accurate, laws and regulations change and no guarantee of completeness or accuracy is given. {$m} Confirm.\n\n## External links\n\nLinks to other websites are provided for convenience; the firm is not responsible for their content. {$m} Confirm.\n\n## Professional rules\n\n{$m} Add any statement required under ICAI guidelines.",
        'professional-disclosures' => "## Professional Disclosures\n\n> Template for review. {$m} The CA must review, complete and approve this text before it is published. Remove every {$m} marker when done.\n\n## Firm details\n\nPrince Jain & Company, Chartered Accountants, Thane, Maharashtra. Established 2001.\n\n## Membership and registration\n\nICAI membership number: {$m} (enter only the verified number, or remove this line).\n\nFirm registration number: {$m} (enter only the verified number, or remove this line).\n\n## Scope of services\n\n{$m} List the services the firm is authorised to provide.\n\n## Other disclosures\n\n{$m} Add any further disclosures required by ICAI or other regulators.",
    ];
}

/** Create tables and insert starter content. Safe to call once on a fresh database. */
function seed_content(): void
{
    $t = now();
    if ((int)db_val('SELECT COUNT(*) FROM services') === 0) {
        $i = 0;
        foreach (seed_services() as $s) {
            $s['slug'] = unique_slug('services', $s['title']);
            $s['icon'] = service_categories()[$s['category']]['icon'] ?? 'file';
            $s['sort_order'] = ++$i * 10;
            $s['created_at'] = $t;
            $s['updated_at'] = $t;
            db_insert('services', $s);
        }
    }
    if ((int)db_val('SELECT COUNT(*) FROM expertise') === 0) {
        $i = 0;
        foreach (seed_expertise() as $e) {
            $e['sort_order'] = ++$i * 10;
            $e['created_at'] = $t;
            $e['updated_at'] = $t;
            db_insert('expertise', $e);
        }
    }
    if ((int)db_val('SELECT COUNT(*) FROM timeline') === 0) {
        $i = 0;
        foreach (seed_timeline() as $r) {
            $r['sort_order'] = ++$i * 10;
            $r['published'] = 1;
            $r['created_at'] = $t;
            $r['updated_at'] = $t;
            db_insert('timeline', $r);
        }
    }
    if ((int)db_val('SELECT COUNT(*) FROM links') === 0) {
        $i = 0;
        foreach (seed_links() as [$label, $u]) {
            db_insert('links', ['grp' => 'important', 'label' => $label, 'url' => $u, 'sort_order' => ++$i * 10,
                'published' => 1, 'created_at' => $t, 'updated_at' => $t]);
        }
    }
    foreach (seed_legal() as $slug => $body) {
        if (!db_one('SELECT id FROM legal_pages WHERE slug = ?', [$slug])) {
            db_insert('legal_pages', ['slug' => $slug, 'title' => legal_slugs()[$slug], 'body' => $body, 'published' => 0, 'updated_at' => $t]);
        }
    }
}
