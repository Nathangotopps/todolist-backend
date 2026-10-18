<?php
// --- THE DEFAULT THEME FOR VISITORS WHO HAVEN'T PICKED ONE ---
// Options: 'retro', 'cyber', or 'slate'.
// Visitors can override it with the coloured dots on each page; their choice is
// remembered in a cookie for a year. Change this word to change the default for everyone else.
$default_theme = 'slate';

// Every colour the PHP pages (register, login, dashboard, logout) use comes from here.
// The theme name is also sent to the React app, which uses it for its top bar.
$themes = [
    'retro' => [
        'bg'          => '#121212',                 // page background
        'page_text'   => '#ffffff',                 // text sitting directly on the page background
        'page_sub'    => '#aaaaaa',                 // muted text on the page background
        'link'        => '#ffcc00',                 // links on the page background
        'card'        => 'rgba(24, 24, 24, 0.9)',   // card / box background
        'card_hover'  => 'rgba(35, 35, 35, 0.9)',
        'border'      => 'rgba(255, 255, 255, 0.12)',
        'text'        => '#ffffff',                 // text inside cards
        'sub'         => '#aaaaaa',                 // muted text inside cards
        'accent'      => '#ffcc00',                 // Gold: buttons and links inside cards
        'button_text' => '#121212',
        'input_bg'    => 'rgba(0, 0, 0, 0.4)',
        'input_border'=> 'rgba(255, 255, 255, 0.2)',
        'soft_bg'     => 'rgba(255, 255, 255, 0.1)', // secondary buttons
        'overlay'     => 'rgba(0, 0, 0, 0.9)',      // welcome screen backdrop
        'enter_bg'    => '#000077',                 // "Enter" / "Register Now" buttons
        'enter_text'  => '#ffffff',
        'shadow'      => 'rgba(0, 0, 0, 0.5)',
        'logout'      => '#cc0000'
    ],
    'cyber' => [
        'bg'          => '#0a192f',
        'page_text'   => '#e6f1ff',
        'page_sub'    => '#8892b0',
        'link'        => '#00f2fe',
        'card'        => 'rgba(16, 28, 48, 0.85)',
        'card_hover'  => 'rgba(22, 38, 64, 0.95)',
        'border'      => '#00f2fe',                 // Cyan
        'text'        => '#e6f1ff',
        'sub'         => '#8892b0',
        'accent'      => '#00f2fe',
        'button_text' => '#0a192f',
        'input_bg'    => 'rgba(0, 0, 0, 0.35)',
        'input_border'=> 'rgba(0, 242, 254, 0.35)',
        'soft_bg'     => 'rgba(0, 242, 254, 0.1)',
        'overlay'     => 'rgba(10, 25, 47, 0.94)',
        'enter_bg'    => '#00f2fe',
        'enter_text'  => '#0a192f',
        'shadow'      => 'rgba(0, 0, 0, 0.5)',
        'logout'      => '#ff6b6b'
    ],
    'slate' => [
        'bg'          => '#2c3e50',
        'page_text'   => '#ffffff',
        'page_sub'    => '#bdc3c7',
        'link'        => '#5dade2',
        'card'        => 'rgba(255, 255, 255, 0.95)',
        'card_hover'  => '#ffffff',
        'border'      => 'rgba(0, 0, 0, 0.1)',
        'text'        => '#2c3e50',
        'sub'         => '#7f8c8d',
        'accent'      => '#2980b9',                 // Royal Blue
        'button_text' => '#ffffff',
        'input_bg'    => '#ffffff',
        'input_border'=> 'rgba(0, 0, 0, 0.25)',
        'soft_bg'     => 'rgba(0, 0, 0, 0.06)',
        'overlay'     => 'rgba(44, 62, 80, 0.96)',
        'enter_bg'    => '#2980b9',
        'enter_text'  => '#ffffff',
        'shadow'      => 'rgba(0, 0, 0, 0.35)',
        'logout'      => '#e74c3c'
    ]
];

// --- WORK OUT WHICH THEME TO USE ---
// 1. start from the default (falls back to 'retro' if the name above is misspelt)
$active_theme = isset($themes[$default_theme]) ? $default_theme : 'retro';

// 2. a visitor's saved choice wins (only accepted if it is one of the known theme names)
if (isset($_COOKIE['theme']) && is_string($_COOKIE['theme']) && isset($themes[$_COOKIE['theme']])) {
    $active_theme = $_COOKIE['theme'];
}

// 3. clicking a dot sends ?set_theme=name: save it in a cookie, then reload the same page
if (isset($_GET['set_theme']) && is_string($_GET['set_theme']) && isset($themes[$_GET['set_theme']])) {
    $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    setcookie('theme', $_GET['set_theme'], [
        'expires'  => time() + 60 * 60 * 24 * 365,
        'path'     => '/',
        'secure'   => $is_https,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    // redirect to this same file name only (never to a URL taken from the request)
    header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
    exit();
}

// Extract the current palette variables
$theme = $themes[$active_theme];

// Prints the little row of theme dots; call it just before </body> on each page.
function theme_switcher() {
    global $themes, $active_theme;
    echo '<div style="position:fixed; top:12px; right:12px; z-index:10001; display:flex; align-items:center; gap:8px; padding:6px 10px; border-radius:20px; background:rgba(0,0,0,0.45); font-family:Arial,sans-serif; font-size:12px; color:#ffffff;">';
    echo '<span aria-hidden="true">🎨</span>';
    foreach ($themes as $name => $palette) {
        $label = ucfirst($name) . ' theme';
        $border = ($name === $active_theme) ? '#ffffff' : 'transparent';
        echo '<a href="?set_theme=' . urlencode($name) . '" title="' . $label . '" aria-label="' . $label . '"'
           . ' style="display:inline-block; width:18px; height:18px; border-radius:50%; box-sizing:border-box;'
           . ' background:' . $palette['accent'] . '; border:2px solid ' . $border . ';"></a>';
    }
    echo '</div>';
}
?>