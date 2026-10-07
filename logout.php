<?php
session_start();

// 1. Empty all session variables out of memory
session_unset();

// 2. Completely destroy the session tracking cookie
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Successful</title>
</head>
<body>
    <h2 style = "font-family: Arial, sans-serif";>You have successfully logged out!</h2>
    <p style = "font-family: Arial, sans-serif";>Log back in below:</p>
    
    <!-- Cleaned up link styled to look exactly like your blue buttons -->
    <a href="login.php" style="display: inline-block; background-color: #007; 
       color: white; padding: 12px 24px; font-size: 16px; 
       text-decoration: none; border-radius: 4px; cursor: pointer; font-family: Arial, sans-serif;">
        Log In Again
    </a>
</body>
</html>
