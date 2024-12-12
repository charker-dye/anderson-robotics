<?php
require 'connect.php'; // Database connection

// Initialize error message variable
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);  // Add trim to remove unwanted spaces
    $password_confirm = trim($_POST['password_confirm']);  // Add trim to remove unwanted spaces

    // Debugging - echo passwords to check for invisible characters
    // echo "Password: '$password'<br>";  // Debugging line, remove after testing
    // echo "Confirm Password: '$password_confirm'<br>";  // Debugging line, remove after testing

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        $errorMessage = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email address!";
    } elseif ($password !== $password_confirm) {
        $errorMessage = "Passwords do not match!";
    } else {
        // Check if the username or email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $errorMessage = "Username or email already taken!";
        } else {
            // Hash the password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into the database
            $stmt = $pdo->prepare("INSERT INTO users (username, email, PasswordHash, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$username, $email, $password_hash]);

            // Redirect after successful registration
            header("Location: login.php");
            exit;
        }
    }
}
?>

<!-- Registration Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Registration</title>
    <header>
    <a href="index.php">
            <img src="images/ar_logo_blue.png" alt="Anderson Robotics Logo" width="50" height="50">
        </a>
        <div class="header-text">
            <h1>Anderson Robotics</h1>
            <p>More than human</p>
        </div>
    </header>
</head>
<body>

<div class="container">
    <h2>Register</h2>

    <?php if ($errorMessage): ?>
        <div class="error"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <form method="post">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter username" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter email" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter password" required>
        </div>
        <div>
            <label for="password_confirm">Confirm Password:</label>
            <input type="password" id="password_confirm" name="password_confirm" placeholder="Confirm password" required>
        </div>
        <button type="submit">Register</button>
    </form>
</div>

</body>
</html>