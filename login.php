<?php 
session_start();
$conn = new mysqli("localhost", "root", "", "school_portal");
require_once 'theme_config.php'; // Drops the palette array right into this file

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- MOVE THE INCOGNITO GUEST MODE INTERCEPTOR RIGHT HERE ---
    if (isset($_POST['guest_login'])) {
        // Generate an unguessable visitor code (16 hex chars) so two guests can never share an ID
        $random_guest_num = bin2hex(random_bytes(8));
        
        // Log them in with a distinct temporary session key
        session_regenerate_id(true);
        $_SESSION['user_id'] = "guest_" . $random_guest_num;
        $_SESSION['user_name'] = "Guest " . substr($random_guest_num, 0, 5); 
        
        // Redirect them straight into the workspace dashboard portal!
        header("Location: dashboard.php");
        exit();
    }

    // Now it is completely safe to read these because we know it's a standard login submission!
    $student_email = trim($_POST['email'] ?? ''); 
    $student_password = $_POST['password'] ?? '';

$errors = [];
if (empty($student_email)) {
    $errors[] = "Email is required.";
}

if (empty($student_password)){
    $errors[] = "Password is required.";
}

if (!empty($student_email) && !filter_var($student_email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email.";
}

if (empty($errors)) {

$sql = "SELECT id, name, password FROM pupils WHERE email = ?;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_email);
$stmt->execute();
$user_id = null;
$db_full_name = "";
$db_password = "";
$stmt->bind_result($user_id, $db_full_name, $db_password);

if ($stmt->fetch()) { 
    if(password_verify($student_password, $db_password)){
    session_regenerate_id(true); // new session ID after login prevents session fixation
    $_SESSION['user_id'] = $user_id;

    // Split full name to save just the first name on login
    $name_parts = explode(' ', trim($db_full_name));
    $_SESSION['user_name'] = $name_parts[0];

    $stmt->close();
    // ... the rest of your redirection lines remain exactly the same

        $conn->close();
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid email or password.<br>";
        $stmt->close();
        $conn->close();
    }
} else {
    echo "Invalid email or password.<br>";
    $stmt->close();
    $conn->close();
}

} else {
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
    /* 1. Reset margins and pad the full screen */
    body {
      margin: 0;
      padding: 0;
      background-color: #f0f2f5; /* Light grey background */
      
      /* 2. Enable Flexbox grid */
      display: flex;
      
      /* 3. Center perfectly horizontal and vertical */
      justify-content: center; 
      align-items: center;     
      
      /* 4. Force the body to take up 100% of the screen height */
      min-height: 100vh;       
      font-family: Arial, sans-serif;
    }

    /* 5. Style the login box container */
    .login-card {
      background: #ffffff;
      padding: 50px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px; /* Prevents card from stretching too wide */
      box-sizing: border-box;
    }

    /* Quick helper styling for internal elements */
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      margin-bottom: 8px;
    }
    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }
    button {
      width: 100%;
      padding: 12px;
      background: #007bff;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    #loginToggleBtn {
  color: #ff5733; /* Change this hex code to your preferred color */
  font-weight: bold; /* Optional: makes it easier to see */
}
/* Creates the distinct box container area at the top */
.header-box {
  margin-top: -15px;  /* Pulls the box closer to the top border of the card */
  margin-bottom: 25px; /* Adds clean spacing below the box before your inputs start */
}

/* Specific styling for the welcome title inside the box */
.header-box h3 {
  margin: 0;
  font-size: 28px;
  font-weight: bold;
}

  </style>
</head>
<body style="background-color: <?= $theme['bg'] ?>; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; color: <?= $theme['page_text'] ?>;">

    <!-- The Theme 1 Card Container Card -->
    <div style="max-width: 400px; width: 90%; padding: 40px 30px; background-color: <?= $theme['card'] ?>; color: <?= $theme['text'] ?>; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid <?= $theme['border'] ?>; border-radius: 12px; box-shadow: 0 10px 30px <?= $theme['shadow'] ?>; text-align: center; box-sizing: border-box;">
        
        <h2 style="color: <?= $theme['text'] ?>; margin-top: 0; margin-bottom: 25px; font-size: 28px; font-weight: bold; letter-spacing: 0.5px;">Welcome Back!</h2>

        <form action="login.php" method="post" style="text-align: left;">
            
            <h4 style="color: <?= $theme['text'] ?>; margin: 0 0 8px 0; font-size: 14px; font-weight: normal;">Email Address:</h4>
            <input type="email" name="email" required style="width: 100%; padding: 12px 16px; margin-bottom: 20px; background-color: <?= $theme['input_bg'] ?>; border: 1px solid <?= $theme['input_border'] ?>; border-radius: 6px; color: <?= $theme['text'] ?>; font-size: 16px; outline: none; box-sizing: border-box;">

            <h4 style="color: <?= $theme['text'] ?>; margin: 0 0 8px 0; font-size: 14px; font-weight: normal;">Password:</h4>
            <div style="display: flex; gap: 10px; width: 100%; align-items: center; margin-bottom: 25px; box-sizing: border-box;">
                <input type="password" id="loginPassword" name="password" required style="flex: 1; padding: 12px 16px; background-color: <?= $theme['input_bg'] ?>; border: 1px solid <?= $theme['input_border'] ?>; border-radius: 6px; color: <?= $theme['text'] ?>; font-size: 16px; outline: none; box-sizing: border-box;">
                <button type="button" id="loginToggleBtn" style="padding: 12px 16px; font-size: 14px; border: 1px solid <?= $theme['input_border'] ?>; border-radius: 6px; background: <?= $theme['soft_bg'] ?>; color: <?= $theme['text'] ?>; cursor: pointer; white-space: nowrap;">Show</button>
            </div>

            <!-- The Primary Save Point Gold Action Button -->
            <button type="submit" style="width: 100%; background-color: <?= $theme['accent'] ?>; color: <?= $theme['button_text'] ?>; padding: 14px; font-size: 16px; font-weight: bold; border: none; border-radius: 6px; cursor: pointer; transition: background-color 0.2s; box-sizing: border-box;">
                Log In
            </button>

            <!-- Incognito Guest Action Button -->
            <button type="submit" name="guest_login" formnovalidate style="width: 100%; background-color: <?= $theme['soft_bg'] ?>; color: <?= $theme['text'] ?>; padding: 14px; font-size: 16px; font-weight: bold; border: 1px solid rgba(255,255,255,0.2); border-radius: 6px; cursor: pointer; margin-top: 12px; transition: background-color 0.2s; box-sizing: border-box;">
                Try as Anonymous Guest 
            </button>
        </form>

        <h4 style="color: <?= $theme['sub'] ?>; margin-top: 30px; margin-bottom: 10px; font-size: 14px; font-weight: normal;">You don't have an account?</h4>
        <a href="index.php" style="color: <?= $theme['accent'] ?>; text-decoration: none; font-weight: bold; font-size: 15px;">Register Now!</a>
    </div>

    <!-- Inject your existing login scripts right below -->
    <script>
    const loginPasswordInput = document.getElementById('loginPassword');
    const loginToggleBtn = document.getElementById('loginToggleBtn');

    loginToggleBtn.addEventListener('click', function() {
        if (loginPasswordInput.type === 'password') {
            loginPasswordInput.type = 'text';
            loginToggleBtn.innerText = 'Hide';
        } else {
            loginPasswordInput.type = 'password';
            loginToggleBtn.innerText = 'Show';
        }
    });
    </script>
<?php theme_switcher(); ?>
</body>

</html>