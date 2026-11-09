<?php
declare(strict_types=1);

/**
 * Tiny, safe Markdown renderer. All input is HTML-escaped first, so raw HTML
 * can never be injected. Supports: ## headings, lists, bold, italic, links,
 * blockquotes, horizontal rules and paragraphs.
 */
function md_inline(string $s): string
{
    $s = e($s);
    // Links: [text](url) — only http(s), mailto, tel and site-relative URLs.
    $s = preg_replace_callback('/\[([^\]]+)\]\(([^)\s]+)\)/', function ($m) {
        $href = html_entity_decode($m[2], ENT_QUOTES);
        if (!preg_match('#^(https?://|mailto:|tel:|/|\#)#i', $href)) {
            return $m[1];
        }
        $ext = preg_match('#^https?://#i', $href) ? ' target="_blank" rel="noopener noreferrer"' : '';
        return '<a href="' . e($href) . '"' . $ext . '>' . $m[1] . '</a>';
    }, $s);
    $s = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $s);
    $s = preg_replace('/(?<![\w*])\*(?!\s)(.+?)(?<!\s)\*(?![\w*])/s', '<em>$1</em>', $s);
    return $s;
}

function md(?string $text): string
{
    $text = trim(str_replace(["\r\n", "\r"], "\n", (string)$text));
    if ($text === '') {
        return '';
    }
    $lines = explode("\n", $text);
    $out = '';
    $para = [];
    $list = null; // 'ul' | 'ol'
    $quote = [];

    $flushPara = function () use (&$para, &$out) {
        if ($para) {
            $out .= '<p>' . implode('<br>', array_map('md_inline', $para)) . '</p>';
            $para = [];
        }
    };
    $flushList = function () use (&$list, &$out) {
        if ($list) {
            $out .= '</' . $list . '>';
            $list = null;
        }
    };
    $flushQuote = function () use (&$quote, &$out) {
        if ($quote) {
            $out .= '<blockquote><p>' . implode('<br>', array_map('md_inline', $quote)) . '</p></blockquote>';
            $quote = [];
        }
    };

    foreach ($lines as $line) {
        $t = rtrim($line);
        if (trim($t) === '') {
            $flushPara();
            $flushList();
            $flushQuote();
            continue;
        }
        if (preg_match('/^(#{2,4})\s+(.+)$/', $t, $m)) {
            $flushPara();
            $flushList();
            $flushQuote();
            $lvl = strlen($m[1]);
            $out .= "<h{$lvl}>" . md_inline($m[2]) . "</h{$lvl}>";
        } elseif (preg_match('/^(-{3,}|\*{3,})$/', $t)) {
            $flushPara();
            $flushList();
            $flushQuote();
            $out .= '<hr>';
        } elseif (preg_match('/^[-*•]\s+(.+)$/', $t, $m)) {
            $flushPara();
            $flushQuote();
            if ($list !== 'ul') {
                $flushList();
                $out .= '<ul>';
                $list = 'ul';
            }
            $out .= '<li>' . md_inline($m[1]) . '</li>';
        } elseif (preg_match('/^\d+[.)]\s+(.+)$/', $t, $m)) {
            $flushPara();
            $flushQuote();
            if ($list !== 'ol') {
                $flushList();
                $out .= '<ol>';
                $list = 'ol';
            }
            $out .= '<li>' . md_inline($m[1]) . '</li>';
        } elseif (preg_match('/^>\s?(.*)$/', $t, $m)) {
            $flushPara();
            $flushList();
            $quote[] = $m[1];
        } else {
            $flushList();
            $flushQuote();
            $para[] = $t;
        }
    }
    $flushPara();
    $flushList();
    $flushQuote();
    return $out;
}

function md_plain(?string $text): string
{
    $t = strip_tags(md($text));
    return trim(preg_replace('/\s+/', ' ', html_entity_decode($t, ENT_QUOTES)));
}

function reading_time(?string $text): int
{
    $words = str_word_count(md_plain($text));
    return max(1, (int)ceil($words / 200));
}
