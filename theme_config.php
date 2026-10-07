<?php
// --- THE ONLY WORD YOU CHANGE TO SWAP THE ENTIRE WEBSITE ---
// Options: 'retro', 'cyber', or 'slate'
$active_theme = 'slate'; 

$themes = [
    'retro' => [
        'bg' => '#121212',
        'card' => 'rgba(24, 24, 24, 0.9)',
        'border' => 'rgba(255, 255, 255, 0.12)',
        'text' => '#ffffff',
        'sub' => '#aaaaaa',
        'accent' => '#ffcc00', // Gold
        'button_text' => '#121212'
    ],
    'cyber' => [
        'bg' => '#0a192f',
        'card' => 'rgba(16, 28, 48, 0.85)',
        'border' => '#00f2fe', // Cyan
        'text' => '#e6f1ff',
        'sub' => '#8892b0',
        'accent' => '#00f2fe',
        'button_text' => '#0a192f'
    ],
    'slate' => [
        'bg' => '#2c3e50',
        'card' => 'rgba(255, 255, 255, 0.95)',
        'border' => 'rgba(0, 0, 0, 0.1)',
        'text' => '#2c3e50',
        'sub' => '#7f8c8d',
        'accent' => '#2980b9', // Royal Blue
        'button_text' => '#ffffff'
    ]
];

// Extract the current palette variables
$theme = $themes[$active_theme];
?>
