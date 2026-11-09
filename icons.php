<?php
declare(strict_types=1);

/** Inline SVG icon set (24x24, stroke-based). */
function icon_paths(): array
{
    return [
        'scale' => '<path d="M12 3v18M5 7h14M5 7l-3 7a4 4 0 0 0 6 0L5 7zM19 7l-3 7a4 4 0 0 0 6 0l-3-7zM8 21h8"/>',
        'receipt' => '<path d="M6 3h12v18l-3-2-3 2-3-2-3 2V3zM9 8h6M9 12h6M9 16h3"/>',
        'percent' => '<path d="M19 5L5 19"/><circle cx="7.5" cy="7.5" r="2"/><circle cx="16.5" cy="16.5" r="2"/>',
        'shield' => '<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3z"/><path d="M8.5 12l2.5 2.5L16 9.5"/>',
        'calculator' => '<rect x="5" y="3" width="14" height="18" rx="2"/><path d="M8 7h8M8 11h2M12 11h2M8 15h2M12 15h2M8 18h8"/>',
        'briefcase' => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2M3 13h18"/>',
        'chart' => '<path d="M4 20V4M4 20h16M8 16v-5M12 16V8M16 16v-3"/>',
        'trending' => '<path d="M3 17l6-6 4 4 8-8M15 7h6v6"/>',
        'landmark' => '<path d="M3 10l9-6 9 6M5 10v8M9 10v8M15 10v8M19 10v8M3 21h18"/>',
        'users' => '<circle cx="9" cy="8" r="3.2"/><path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6M16 5.2a3.2 3.2 0 0 1 0 5.6M18 14.3c1.8.8 3 2.6 3 4.7"/>',
        'building' => '<path d="M4 21V5l8-2v18M12 9l8 2v10M3 21h18M8 9h1M8 13h1M8 17h1M16 15h1M16 18h1"/>',
        'file' => '<path d="M7 3h7l5 5v13H7V3zM14 3v5h5M10 13h6M10 17h6"/>',
        'clipboard' => '<rect x="6" y="4" width="12" height="17" rx="2"/><path d="M9 4h6v3H9zM9 12h6M9 16h4"/>',
        'target' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/>',
        'layers' => '<path d="M12 3l9 5-9 5-9-5 9-5zM3 13l9 5 9-5"/>',
        'message' => '<path d="M4 5h16v11H9l-5 4V5z"/>',
        'compass' => '<circle cx="12" cy="12" r="9"/><path d="M15.5 8.5l-2 5-5 2 2-5 5-2z"/>',
        'wallet' => '<rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18M16 14h2"/>',
        'search' => '<circle cx="11" cy="11" r="6"/><path d="M20 20l-4.5-4.5"/>',
        'pie' => '<path d="M12 3v9h9a9 9 0 1 1-9-9z"/><path d="M15 3.5A9 9 0 0 1 20.5 9H15V3.5z"/>',
        'book' => '<path d="M5 4h10a3 3 0 0 1 3 3v13H8a3 3 0 0 1-3-3V4z"/><path d="M5 17a3 3 0 0 1 3-3h10"/>',
        'sprout' => '<path d="M12 21v-8M12 13c0-4 3-6 7-6 0 4-3 6-7 6zM12 15c0-3-2-5-6-5 0 3 2 5 6 5z"/>',
        'phone' => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
        'pin' => '<path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
        'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'check' => '<path d="M5 12l5 5 9-10"/>',
        'menu' => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'close' => '<path d="M6 6l12 12M18 6L6 18"/>',
        'up' => '<path d="M6 14l6-6 6 6"/>',
        'chevron' => '<path d="M6 9l6 6 6-6"/>',
        'external' => '<path d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/>',
        'link' => '<path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/>',
        'whatsapp' => '<path d="M12 3a9 9 0 0 0-7.8 13.5L3 21l4.6-1.2A9 9 0 1 0 12 3z"/><path d="M9 8.5c0 3 3 6 6 6l1.2-1.4-2-1-1 .8a4 4 0 0 1-2-2l.8-1-1-2L9 8.5z"/>',
        'linkedin' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M8 10v7M8 7v.01M12 17v-7M12 13c0-2 1.5-3 3-3s2.5 1 2.5 3v4"/>',
        'facebook' => '<path d="M14 21v-8h3l.5-3.5H14V7.5c0-1 .5-1.7 1.8-1.7H18V3a20 20 0 0 0-2.7-.2c-2.8 0-4.8 1.7-4.8 4.4V9.5h-3V13h3v8"/>',
        'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5v.01"/>',
        'x' => '<path d="M4 4l16 16M20 4L4 20"/>',
        'youtube' => '<rect x="3" y="6" width="18" height="12" rx="3"/><path d="M10 9.5v5l4.5-2.5-4.5-2.5z"/>',
        'star' => '<path d="M12 3.5l2.7 5.5 6 .9-4.4 4.2 1 6-5.3-2.8-5.3 2.8 1-6L3.3 9.9l6-.9L12 3.5z"/>',
        'share' => '<circle cx="18" cy="5" r="2.5"/><circle cx="6" cy="12" r="2.5"/><circle cx="18" cy="19" r="2.5"/><path d="M8.2 10.8l7.6-4.4M8.2 13.2l7.6 4.4"/>',
        'lock' => '<rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/>',
        'folder' => '<path d="M3 6a1 1 0 0 1 1-1h5l2 2h8a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6z"/>',
        'inbox' => '<path d="M3 13l3-8h12l3 8v6H3v-6zM3 13h5l1 3h6l1-3h5"/>',
        'gear' => '<circle cx="12" cy="12" r="3"/><path d="M12 3v3M12 18v3M3 12h3M18 12h3M5.6 5.6l2.1 2.1M16.3 16.3l2.1 2.1M18.4 5.6l-2.1 2.1M7.7 16.3l-2.1 2.1"/>',
        'home' => '<path d="M3 11l9-8 9 8M5 10v10h14V10"/>',
        'quote' => '<path d="M7 7h4v5c0 3-1.5 4.5-4 5M15 7h4v5c0 3-1.5 4.5-4 5"/>',
        'help' => '<circle cx="12" cy="12" r="9"/><path d="M9.5 9.5a2.5 2.5 0 1 1 3.5 2.3c-.7.4-1 .9-1 1.7M12 17v.01"/>',
        'megaphone' => '<path d="M4 10v4h3l8 4V6L7 10H4zM18 9a4 4 0 0 1 0 6"/>',
        'newspaper' => '<path d="M5 4h12v16H7a2 2 0 0 1-2-2V4zM17 8h3v10a2 2 0 0 1-2 2M8 8h6M8 12h6M8 16h4"/>',
        'info' => '<circle cx="12" cy="12" r="9"/><path d="M12 11v6M12 7.5v.01"/>',
        'award' => '<circle cx="12" cy="9" r="5"/><path d="M8.5 13.5L7 21l5-3 5 3-1.5-7.5"/>',
    ];
}

function icon_names(): array
{
    return array_keys(icon_paths());
}

function icon(string $name, string $class = 'icon', int $size = 24): string
{
    $paths = icon_paths();
    $inner = $paths[$name] ?? $paths['file'];
    $fill = $name === 'star' ? 'currentColor' : 'none';
    return '<svg class="' . e($class) . '" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="' . $fill
        . '" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
        . $inner . '</svg>';
}
