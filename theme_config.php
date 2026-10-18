<?php
// --- THE ONLY WORD YOU CHANGE TO SWAP THE ENTIRE WEBSITE ---
// Options: 'retro', 'cyber', or 'slate'
$active_theme = 'slate';

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

// Extract the current palette variables (falls back to 'retro' if the name is misspelt)
if (!isset($themes[$active_theme])) {
    $active_theme = 'retro';
}
$theme = $themes[$active_theme];
?>
