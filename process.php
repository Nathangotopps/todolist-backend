<?php
session_start();

// Make MySQL errors throw exceptions so we can catch them (e.g. duplicate email)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host_name = "localhost";
$default_username = "root";
$password = "";
$database_name = "school_portal";

// Only the registration form should reach this page
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$student_name     = trim($_POST['name'] ?? '');
$student_email    = trim($_POST['email'] ?? '');
$student_password = $_POST['password'] ?? '';

$errors = [];

if ($student_name === '') {
    $errors[] = "Full name is required.";
}

if ($student_email === '') {
    $errors[] = "Email is required.";
} elseif (!filter_var($student_email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email.";
}

if (strlen($student_password) < 8) {
    $errors[] = "Password must be at least 8 characters.";
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo htmlspecialchars($error) . "<br>";
    }
    echo '<br><a href="index.php">Go back</a>';
    exit();
}

$hashed_password = password_hash($student_password, PASSWORD_DEFAULT);

try {
    $conn = new mysqli($host_name, $default_username, $password, $database_name);

    $sql = "INSERT INTO pupils(name, email, password) VALUES(?, ?, ?);";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $student_name, $student_email, $hashed_password);
    $stmt->execute();

    // Grab the exact new row ID MySQL just generated for this pupil account
    $new_user_id = $conn->insert_id;

    $stmt->close();
    $conn->close();
} catch (mysqli_sql_exception $e) {
    // 1062 = duplicate entry (the email column is UNIQUE)
    if ($e->getCode() === 1062) {
        echo "That email is already registered. Try logging in instead.<br>";
        echo '<br><a href="login.php">Log in</a> &middot; <a href="index.php">Go back</a>';
    } else {
        error_log("Registration failed: " . $e->getMessage());
        echo "Something went wrong creating your account. Please try again later.<br>";
        echo '<br><a href="index.php">Go back</a>';
    }
    exit();
}

// Log them in behind the scenes (new session ID prevents session fixation)
session_regenerate_id(true);
$_SESSION['user_id'] = $new_user_id;

// Save just the first name for the dashboard greeting
$name_parts = explode(' ', $student_name);
$_SESSION['user_name'] = $name_parts[0];

$_SESSION['success'] = "Registration Successful!";

header("Location: dashboard.php");
exit();
