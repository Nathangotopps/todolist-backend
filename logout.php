<?php
session_start();

// 1. Empty all session variables out of memory
session_unset();

// 2. Completely destroy the session tracking cookie
session_destroy();

require_once 'theme_config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Successful</title>
</head>
<body style="margin: 0; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; background-color: <?= $theme['bg'] ?>; color: <?= $theme['page_text'] ?>;">
    <h2 style = "font-family: Arial, sans-serif";>You have successfully logged out!</h2>
    <p style = "font-family: Arial, sans-serif";>Log back in below:</p>
    
    <!-- Cleaned up link styled to look exactly like your blue buttons -->
    <a href="login.php" style="display: inline-block; background-color: <?= $theme['enter_bg'] ?>; 
       color: <?= $theme['enter_text'] ?>; padding: 12px 24px; font-size: 16px; 
       text-decoration: none; border-radius: 4px; cursor: pointer; font-family: Arial, sans-serif;">
        Log In Again
    </a>
</body>
</html>
