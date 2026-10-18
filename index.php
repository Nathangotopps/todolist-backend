<?php
session_start();
require_once 'theme_config.php'; // Drops the palette array right into this file
if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AiOTDL Registration Form</title>
    <style>
    /* Enhanced top-down transition animation rule wrapper */
    @keyframes slideDownIn {
        0% {
            opacity: 0;
            transform: translateY(-120px) scale(0.95);
        }
        60% {
            opacity: 0.7;
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Custom class wrapper using a high-fidelity ease-out curve for smooth braking */
    .welcome-content-box {
        animation: slideDownIn 1.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* 1. Reset margins and pad the full screen */
    body {
      margin: 0;
      padding: 0;
      background-color: #f0f2f5; /* Light grey background */
      display: flex;
      justify-content: center; 
      align-items: center;     
      min-height: 100vh;       
      font-family: Arial, sans-serif;
    }

    /* 2. Style the box container */
    .login-card {
      background: #ffffff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 420px; 
      box-sizing: border-box;
    }

    /* Cleaned up label headers */
    .form-group-title {
      margin: 15px 0 5px 0;
      font-size: 16px;
      color: #333;
    }

    /* Target inputs cleanly without squishing them */
    .form-input-field {
      width: 100%; 
      padding: 12px; 
      font-size: 16px; 
      box-sizing: border-box;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    /* Custom Color styling for your toggle button */
    #regToggleBtn {
      color: #ff5733; 
      font-weight: bold; 
    }
  </style>
</head>
<body style="background-color: <?= $theme['bg'] ?>; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; color: <?= $theme['page_text'] ?>;">

    <!-- Registration Card Matrix Frame Box Container -->
    <div style="max-width: 480px; width: 95%; padding: 40px 30px; background-color: <?= $theme['card'] ?>; color: <?= $theme['text'] ?>; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid <?= $theme['border'] ?>; border-radius: 12px; box-shadow: 0 10px 30px <?= $theme['shadow'] ?>; box-sizing: border-box; margin: 40px 0;">
        
        <h2 style="color: <?= $theme['text'] ?>; margin-top: 0; margin-bottom: 25px; font-size: 26px; font-weight: bold; text-align: center;">AiOTDL Registration Form</h2>

        <form action="process.php" method="post" style="text-align: left;">
            <h4 style="color: <?= $theme['text'] ?>; margin: 0 0 6px 0; font-size: 14px; font-weight: normal;">Full Name:</h4>
            <input type="text" name="name" style="width: 100%; padding: 12px; background-color: <?= $theme['input_bg'] ?>; border: 1px solid <?= $theme['input_border'] ?>; border-radius: 6px; color: <?= $theme['text'] ?>; font-size: 16px; outline: none; box-sizing: border-box; margin-bottom: 15px;">

            <h4 style="color: <?= $theme['text'] ?>; margin: 0 0 6px 0; font-size: 14px; font-weight: normal;">Email Address:</h4>
            <input type="email" name="email" style="width: 100%; padding: 12px; background-color: <?= $theme['input_bg'] ?>; border: 1px solid <?= $theme['input_border'] ?>; border-radius: 6px; color: <?= $theme['text'] ?>; font-size: 16px; outline: none; box-sizing: border-box; margin-bottom: 15px;">

            <h4 style="color: <?= $theme['text'] ?>; margin: 0 0 6px 0; font-size: 14px; font-weight: normal;">Phone Number:</h4>
            <input type="tel" name="phone_number" style="width: 100%; padding: 12px; background-color: <?= $theme['input_bg'] ?>; border: 1px solid <?= $theme['input_border'] ?>; border-radius: 6px; color: <?= $theme['text'] ?>; font-size: 16px; outline: none; box-sizing: border-box; margin-bottom: 15px;">

            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <h4 style="color: <?= $theme['text'] ?>; margin: 0 0 6px 0; font-size: 14px; font-weight: normal;">Age:</h4>
                    <input type="number" name="age" style="width: 100%; padding: 12px; background-color: <?= $theme['input_bg'] ?>; border: 1px solid <?= $theme['input_border'] ?>; border-radius: 6px; color: <?= $theme['text'] ?>; font-size: 16px; outline: none; box-sizing: border-box;">
                </div>
                <div style="flex: 1.5; text-align: left;">
                    <h4 style="color: <?= $theme['text'] ?>; margin: 0 0 6px 0; font-size: 14px; font-weight: normal;">Gender:</h4>
                    <div style="display: flex; gap: 10px; align-items: center; margin-top: 12px;">
                        <input type="radio" id="male" name="gender" value="male"><label for="male" style="font-size:14px; cursor:pointer;">Male</label>
                        <input type="radio" id="female" name="gender" value="female"><label for="female" style="font-size:14px; cursor:pointer;">Female</label>
                        <input type="radio" id="other" name="gender" value="other"><label for="other" style="font-size:14px; cursor:pointer;">Other</label>
                    </div>
                </div>
            </div>

            <h4 style="color: <?= $theme['text'] ?>; margin: 0 0 6px 0; font-size: 14px; font-weight: normal;">Password:</h4>
            <div style="display: flex; gap: 10px; width: 100%; align-items: center; margin-bottom: 25px; box-sizing: border-box;">
                <input type="password" id="regPassword" name="password" style="flex: 1; padding: 12px; background-color: <?= $theme['input_bg'] ?>; border: 1px solid <?= $theme['input_border'] ?>; border-radius: 6px; color: <?= $theme['text'] ?>; font-size: 16px; outline: none; box-sizing: border-box;">
                <button type="button" id="regToggleBtn" style="padding: 12px; font-size: 14px; border: 1px solid <?= $theme['input_border'] ?>; border-radius: 6px; background: <?= $theme['soft_bg'] ?>; color: <?= $theme['text'] ?>; cursor: pointer; white-space: nowrap;">Show</button>
            </div>

            <button type="submit" style="width: 100%; background-color: <?= $theme['accent'] ?>; color: <?= $theme['button_text'] ?>; padding: 14px; font-size: 16px; font-weight: bold; border: none; border-radius: 6px; cursor: pointer; box-sizing: border-box;">
                Submit Registration
            </button>
        </form>

        <div style="text-align: center; margin-top: 25px;">
            <h4 style="color: <?= $theme['sub'] ?>; margin: 0 0 8px 0; font-size: 14px; font-weight: normal;">Already have an account?</h4>
            <a href="login.php" style="color: <?= $theme['accent'] ?>; text-decoration: none; font-weight: bold; font-size: 15px;">Log In here</a>
        </div>
    </div>


<?php 
if (isset($_SESSION['success'])) {
    echo '<script>alert("'. $_SESSION['success'] . '");</script>';
    unset($_SESSION['success']);
}
?>

<audio id="regAudio" src="index.mp3" loop></audio>

<!-- Page Entry Welcome Screen Overlay -->
<div id="regOverlay" style="
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background-color: <?= $theme['overlay'] ?>; display: flex; 
    flex-direction: column; justify-content: center; align-items: center;
    z-index: 9999; color: white; font-family: sans-serif;
    transition: opacity 1s ease; opacity: 1; 
">
    <div class="welcome-content-box" style="text-align: center; display: flex; flex-direction: column; align-items: center;">
        <h2 style="margin-bottom: 20px;">Welcome to Your All-In-One To-Do List!</h2>
        <p style="margin-bottom: 30px; color: #ccc;">Click below to begin your registration.</p>
        <button id="regEnterBtn" style="
            background-color: <?= $theme['enter_bg'] ?>; color: <?= $theme['enter_text'] ?>; border: none;
            padding: 15px 40px; font-size: 18px; font-weight: bold;
            border-radius: 6px; cursor: pointer; transition: transform 0.2s;
        ">Register Now</button>
    </div>
</div>

<!-- Visual Transition curtain used during page switches (Matching your logoutOverlay ID structural mapping) -->
<div id="logoutOverlay" style="
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background-color: black; z-index: 9998; opacity: 0; pointer-events: none;
    transition: opacity 0.8s ease;
"></div>

<button id="muteToggleBtn" style="
    position: fixed; bottom: 20px; right: 20px; z-index: 1000;
    background-color: #333; color: white; border: none;
    border-radius: 50%; width: 50px; height: 50px; font-size: 20px;
    cursor: pointer; display: flex; justify-content: center; align-items: center;
    box-shadow: 0 4px 6px rgba(0,0,0,0.3);
">🔊</button>

<script>
const regAudio = document.getElementById('regAudio'); 
const regEnterBtn = document.getElementById('regEnterBtn'); 
const regOverlay = document.getElementById('regOverlay'); 
const muteBtn = document.getElementById('muteToggleBtn');

// --- NEW: LOUGOUT FADE OUT CONTROLLER ENGINE ELEMENTS ---
const loginLink = document.querySelector('.login-link');
const logoutOverlay = document.getElementById('logoutOverlay');

let isMuted = false;
let fadeInInterval; 

// 1. FADE IN MUSIC ENGINE (ON SCREEN ENTRY)
regEnterBtn.addEventListener('click', function() {
    regOverlay.style.opacity = '0';
    
    setTimeout(() => {
        regOverlay.style.display = 'none';
    }, 1000);
    
    regAudio.volume = 0.0; 
    
    regAudio.play().then(() => {
        let maxVolume = 0.5; 
        
        fadeInInterval = setInterval(() => {
            if (regAudio.volume < maxVolume) {
                regAudio.volume = Math.min(maxVolume, regAudio.volume + 0.02);
            } else {
                clearInterval(fadeInInterval);
            }
        }, 40);
    }).catch(err => console.log("Playback blocked:", err));
});

// 2. MUTE BUTTON CONTROLLER ENGINE
muteBtn.addEventListener('click', function() {
    if (isMuted) {
        regAudio.muted = false;
        muteBtn.innerText = "🔊"; 
        isMuted = false;
    } else {
        regAudio.muted = true;
        muteBtn.innerText = "🔇"; 
        isMuted = true;
    }
});

// 3. --- NEW: LOUGOUT FADE OUT CONTROLLER ENGINE ---
if (loginLink) {
    loginLink.addEventListener('click', function(e) {
        e.preventDefault(); // Stop browser from jumping pages instantly
        const targetUrl = this.getAttribute('href');
        
        clearInterval(fadeInInterval); // Terminate active volume raise loops
        
        // 1. Trigger the dark black layout fade-out curtain screen
        logoutOverlay.style.pointerEvents = "all";
        logoutOverlay.style.opacity = "1";

        // If audio track is paused or muted, route straight to redirection without dead waiting loops
        if (regAudio.muted || regAudio.paused || regAudio.volume === 0) {
            setTimeout(() => { window.location.href = targetUrl; }, 800);
            return;
        }

        // 2. Begin stepping down volume tracks gradually
        const fadeOutInterval = setInterval(() => {
            if (regAudio.volume > 0.02) {
                regAudio.volume = Math.max(0, regAudio.volume - 0.03);
            } else {
                clearInterval(fadeOutInterval);
                regAudio.pause();
                
                // 3. Navigate away to login.php
                window.location.href = targetUrl;
            }
        }, 35);
    });
}

// 4. PASSWORD VISIBILITY TOGGLE ENGINE
const regPasswordInput = document.getElementById('regPassword');
const regToggleBtn = document.getElementById('regToggleBtn');

regToggleBtn.addEventListener('click', function() {
    if (regPasswordInput.type === 'password') {
        regPasswordInput.type = 'text';
        regToggleBtn.innerText = 'Hide';
    } else {
        regPasswordInput.type = 'password';
        regToggleBtn.innerText = 'Show';
    }
});
</script>
<?php theme_switcher(); ?>
</body>
</html>
