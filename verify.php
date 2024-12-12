<?php
require 'connect.php';
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // Check the username and password
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['PasswordHash'])) {
            // Login success
            $_SESSION['username'] = $user['Username'];
            $_SESSION['role'] = $user['Role'];

            // Redirect to index.php
            header('Location: index.php');
            exit;
        } else {
            // Invalid login
            $error = 'Invalid credentials or insufficient permissions.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <header>
        <a href="index.php">
            <img src="images/ar_logo_blue.png" alt="Anderson Robotics Logo" width="50" height="50">
        </a>
        <div class="header-text">
            <h1>Anderson Robotics</h1>
            <p>More than human</p>
        </div>
    </header>
    <title>Admin Login</title>
</head>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" action=""> <!-- Submit to the current file -->
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>