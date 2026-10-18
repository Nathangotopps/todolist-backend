<?php 
session_start();
require_once 'theme_config.php'; // Drops the palette array right into this file
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$current_user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Student";


// Where the React app lives. Change this ONE line when you deploy (no trailing slash).
$reactAppUrl = "http://localhost:3000";

// --- MINT A FIREBASE CUSTOM TOKEN SO THE REACT APP CAN VERIFY WHO THIS REALLY IS ---
// Replaces sending the raw ?userId= to React, which anyone could edit in the URL bar
// to read/write someone else's to-dos. Firebase now signs a token proving identity.
require_once __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;

// IMPORTANT: this JSON file is a real secret. Keep it OUTSIDE your public web root
// (see the .htaccess note below), never commit it to git, never serve it directly.
$firebaseFactory = (new Factory)->withServiceAccount(__DIR__ . '/secure/firebase-service-account.json');
$firebaseAuth = $firebaseFactory->createAuth();

// Firebase uids must be strings; mysqli gives $current_user_id back as an int for
// registered users (and it's already a "guest_XXXXX" string for guests).
$firebaseToken = $firebaseAuth->createCustomToken((string) $current_user_id)->toString();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Choose Category</title>
 <style>
     body {
        background-color: <?= $theme['bg'] ?>;
        color: <?= $theme['page_text'] ?>;
        font-family: Arial, Helvetica, sans-serif !important;
        margin: 0; padding: 40px;
        text-align: center;
    }
    h1 { color: <?= $theme['page_text'] ?>; margin-bottom: 10px; }
    p { color: <?= $theme['page_sub'] ?>; margin-bottom: 40px; }
    
    .dashboard-container { display: flex; gap: 25px; padding: 20px; justify-content: center; flex-wrap: wrap; }
    
    .category-card {
        padding: 35px 25px; 
        border: 1px solid <?= $theme['border'] ?>; 
        border-radius: 12px;
        text-decoration: none; 
        color: <?= $theme['text'] ?>; 
        font-weight: bold; 
        text-align: center;
        width: 180px; 
        background-color: <?= $theme['card'] ?>;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 8px 24px <?= $theme['shadow'] ?>;
        transition: transform 0.2s, background-color 0.2s, border-color 0.2s;
    }
    
    /* Accent-coloured outline triggers smoothly upon card hovers! */
    .category-card:hover { 
        transform: scale(1.05); 
        background-color: <?= $theme['card_hover'] ?>; 
        border-color: <?= $theme['accent'] ?>; 
        color: <?= $theme['accent'] ?>;
    }

 </style>   
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($current_user_name); ?>!</h1>

    <p>Which category are we opening today?</p>

    <div class="dashboard-container">
        <!-- Attached a custom class and JavaScript listener to intercept the clicks safely -->
        <a href="<?php echo htmlspecialchars($reactAppUrl); ?>/?token=<?php echo urlencode($firebaseToken); ?>&category=financial&theme=<?php echo $active_theme; ?>" class="category-card nav-link">💰 Financial</a>
        <a href="<?php echo htmlspecialchars($reactAppUrl); ?>/?token=<?php echo urlencode($firebaseToken); ?>&category=fitness&theme=<?php echo $active_theme; ?>" class="category-card nav-link">🏋️<br>Fitness</a>
        <a href="<?php echo htmlspecialchars($reactAppUrl); ?>/?token=<?php echo urlencode($firebaseToken); ?>&category=academic&theme=<?php echo $active_theme; ?>" class="category-card nav-link">📚 Academic</a>
        <a href="<?php echo htmlspecialchars($reactAppUrl); ?>/?token=<?php echo urlencode($firebaseToken); ?>&category=personal/miscellaneous&theme=<?php echo $active_theme; ?>" class="category-card nav-link">🏠 Personal</a>
    </div>

    <a href="logout.php" class="logout-link" style="display:inline-block; margin-top: 20px; color:<?= $theme['logout'] ?>; justify-content: center; align-items: center;">Log out safely.</a>

    <!-- Music credits (required by Creative Commons Attribution licensing) -->
    <details style="max-width: 480px; margin: 30px auto 0 auto; color: <?= $theme['page_sub'] ?>; font-size: 13px; text-align: left;">
        <summary style="cursor: pointer; color: <?= $theme['page_sub'] ?>; text-align: center;">🎵 Music credits</summary>
        <ul style="line-height: 1.7; padding-left: 20px;">
            <li>"Canon in D Major" by Kevin MacLeod (incompetech.com), CC BY 4.0</li>
            <li>"Vibing Over Venus" by Kevin MacLeod (incompetech.com), CC BY 4.0</li>
            <li>"Space Jazz" by Kevin MacLeod (incompetech.com), CC BY 3.0</li>
            <li>"Waltz Primordial" by Kevin MacLeod (incompetech.com), CC BY 4.0</li>
            <li>"Wallpaper" by Kevin MacLeod (incompetech.com), CC BY 4.0</li>
            <li>"Deliberate Thought" by Kevin MacLeod (incompetech.com), CC BY 4.0</li>
            <li>Photo: "Nikola Tesla, with his equipment" (edited), Dickenson V. Alley, 1901, via Wikimedia Commons / Wellcome Collection, CC BY 4.0</li>
        </ul>
        <p style="text-align:center;"><a href="https://creativecommons.org/licenses/by/4.0/" style="color:<?= $theme['link'] ?>;">creativecommons.org/licenses/by/4.0</a> &middot; <a href="https://creativecommons.org/licenses/by/3.0/" style="color:<?= $theme['link'] ?>;">by/3.0</a></p>
    </details>

    
    <!-- Hidden Logout Fade Screen Overlay -->
<div id="logoutOverlay" style="
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background-color: black; opacity: 0; pointer-events: none;
    z-index: 10000; transition: opacity 0.6s ease;
"></div>

   <!-- The Hidden Audio File -->
<audio id="dashAudio" src="dashboard.mp3" loop></audio>

<!-- 1. The Welcome Overlay Fixed Backdrop (Includes fade-out exit rule) -->
<div id="welcomeOverlay" style="
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background-color: <?= $theme['overlay'] ?>; display: flex; 
    flex-direction: column; justify-content: center; align-items: center;
    z-index: 9999; color: white; font-family: sans-serif;
    transition: opacity 1s ease; opacity: 1; 
">
    <!-- Inner wrapper content box that handles the smooth sliding drop on load! -->
    <div class="welcome-content-box" style="text-align: center; display: flex; flex-direction: column; align-items: center;">
        <h2 style="margin-bottom: 20px;">Welcome to Your Dashboard Portal</h2>
        <p style="margin-bottom: 30px; color: #ccc;">Click below to enter your secure workspace.</p>
        <button id="enterBtn" style="
            background-color: <?= $theme['enter_bg'] ?>; color: <?= $theme['enter_text'] ?>; border: none;
            padding: 15px 40px; font-size: 18px; font-weight: bold;
            border-radius: 6px; cursor: pointer; transition: transform 0.2s;
        ">Enter Dashboard</button>
    </div>
</div>


    <!-- 2. The Fixed Corner Mute/Unmute Toggle Button -->
    <button id="muteToggleBtn" style="
        position: fixed; bottom: 20px; right: 20px; z-index: 1000;
        background-color: #333; color: white; border: none;
        border-radius: 50%; width: 50px; height: 50px; font-size: 20px;
        cursor: pointer; display: flex; justify-content: center; align-items: center;
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    ">🔊</button>

<script>
    // 1. PHP prints true or false right here into a JavaScript variable
    const isGuestUser = <?php echo (strpos((string) $current_user_id, 'guest_') === 0) ? 'true' : 'false'; ?>;

    const audio = document.getElementById('dashAudio');
    const overlay = document.getElementById('welcomeOverlay');
    const enterBtn = document.getElementById('enterBtn');
    const muteBtn = document.getElementById('muteToggleBtn');

    audio.volume = 0.0; 
    let isMuted = false;

   // Action 1: Handle clicking the "Enter Dashboard" Button with smooth Fade-In and Visual transitions
enterBtn.addEventListener('click', function() {
    // 1. Fade out the black overlay opacity to 0
    overlay.style.opacity = '0';
    
    // 2. Wait exactly 1 second for the fade animation to finish, then turn off display
    setTimeout(() => {
        overlay.style.display = 'none';
    }, 1000);
    
    // 3. Set your audio volume to completely silent before playing
    audio.volume = 0.0;
    
    // 4. Fire off the media track
    audio.play().then(() => {
        let targetVolume = 0.5; // Final maximum volume level (50%)
        
        const fadeInLoop = setInterval(() => {
            if (audio.volume < targetVolume) {
                audio.volume = Math.min(targetVolume, audio.volume + 0.02); // Add 2% volume per tick
            } else {
                clearInterval(fadeInLoop); // Kill the background loop when volume reaches 50%
            }
        }, 40); // 40ms * 25 steps = ~1000ms total smooth fade-in duration
    }).catch(error => {
        console.log("Audio failed to activate:", error);
    });
});

    // --- MUTE SYSTEM TRIGGER ---
    muteBtn.addEventListener('click', function() {
        if (isMuted) {
            audio.muted = false;
            muteBtn.innerText = "🔊"; 
            isMuted = false;
        } else {
            audio.muted = true;
            muteBtn.innerText = "🔇"; 
            isMuted = true;
        }
    });

    // --- NEW: FADE OUT LINK INTERCEPTOR ENGINE ---
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // Stop browser from changing pages instantly
            const targetUrl = this.getAttribute('href');
            
            let currentVolume = audio.volume;
            
            // Loop steps volume down dynamically over half a second
            const fadeOutLoop = setInterval(() => {
                if (currentVolume > 0.02) {
                    currentVolume -= 0.02;
                    audio.volume = Math.max(0, currentVolume);
                } else {
                    clearInterval(fadeOutLoop);
                    audio.pause();
                    // Once completely silent, fire the destination route jump
                    window.location.href = targetUrl;
                }
            }, 20); // 20ms * 25 steps = 500ms perfect fade-out duration
        });
    });

    // --- INTEGRATED: LOUGOUT CONFIRMATION & FADE OUT CONTROLLER ENGINE ---
    const logoutLink = document.querySelector('.logout-link') || document.querySelector('a[href="logout.php"]');
    const logoutOverlay = document.getElementById('logoutOverlay');

    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            
            // 2. THE VISITOR SHIELD INTERCEPTOR PROMPT
            if (isGuestUser) {
                const userChoice = confirm("⚠️ WARNING: You are currently on a Temporary Guest Account!\n\nGuest accounts are temporary. Once you log out you will not be able to get back to these tasks. To keep them, cancel and create a permanent account.\n\nLog out anyway?");
                
                // If they click "Cancel", stop everything instantly and keep them safe on the dashboard
                if (!userChoice) {
                    e.preventDefault();
                    return; 
                }
            }

            // 3. CONTINUES FADE TRANSITIONS SEAMLESSLY IF APPROVED OR REGISTERED MEMBER
            e.preventDefault(); // Stop browser from jumping pages instantly
            const logoutUrl = this.getAttribute('href');
            
            // Trigger the dark black layout fade-out curtain screen overlay if it exists
            if (logoutOverlay) {
                logoutOverlay.style.pointerEvents = 'all'; // Intercept stray clicks
                logoutOverlay.style.opacity = '1';
            }
            
            let currentVolume = audio.volume;
            
            // Loop steps the "dashboard" volume down dynamically over half a second
            const fadeOutLoop = setInterval(() => {
                if (currentVolume > 0.02) {
                    currentVolume -= 0.02;
                    audio.volume = Math.max(0, currentVolume);
                } else {
                    clearInterval(fadeOutLoop);
                    audio.pause();
                    // Once completely silent and dark, execute the final logout route
                    window.location.href = logoutUrl;
                }
            }, 20); // 20ms * 25 steps = 500ms fade-out duration
        });
    }
</script>

<?php theme_switcher(); ?>
</body>
</html>
