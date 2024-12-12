<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;600&family=Montserrat:wght@400;600&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <title>Anderson Robotics</title>
    <style>
       /* Global Colors */
:root {
    --light-accent: #295183;  /* Blue */
    --dark-accent: #1e3c62;   /* Dark blue */
    --hyperlink: #0366d6;     /* Link color */
    --border-colour: rgba(0, 0, 0, 0.12); /* Border */
}

/* Font and Global Settings */
body, h1, h2 {
    background-color: white;
    color: black;
    margin: 0;
    padding: 0;
    font-family: 'Roboto Mono', monospace;
}

h1, h2 {
    font-family: 'Raleway', sans-serif;
}

/* Header */
header, .header-bar {
    background-color: var(--light-accent);
    padding: 10px 20px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

header * {
    color: inherit;
    background-color: transparent;  /* Ensure no white background */
}

.header-bar .auth-links {
    display: flex;
    gap: 10px;
}

.header-bar a {
    color: inherit;
    text-decoration: none;
}

.header-bar a:hover {
    text-decoration: underline;
}

/* Logo */
.logo img {
    height: 50px;
}

/* Navigation Menu */
nav .nav-links {
    display: flex;
    list-style: none;
}

.nav-links li {
    margin-right: 20px;
}

.nav-links a {
    color: inherit;
    text-decoration: none;
    font-size: 16px;
}

.nav-links li:hover .dropdown-content {
    display: block;
}

/* Dropdown */
.dropdown-content {
    display: none;
    position: absolute;
    background-color: var(--dark-accent);
    min-width: 160px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
}

.dropdown-content a {
    color: white;
    padding: 10px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: var(--light-accent);
}

/* Main Content */
.container {
    padding: 20px;
}

.product-list {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.product-item {
    background-color: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0px 4px 6px var(--border-colour);
}

.product-item a {
    color: var(--hyperlink);
    text-decoration: none;
}

.product-item a:hover {
    text-decoration: underline;
}

/* Footer */
footer {
    background-color: var(--light-accent);
    color: white;
    text-align: center;
    padding: 10px 0;
    position: fixed;
    width: 100%;
    bottom: 0;
}

/* Styling Links */
a {
    color: var(--hyperlink);
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
    </style>
</head>
<body>

<header>
<a href="index.php">
            <img src="images/ar_logo_blue.png" alt="Anderson Robotics Logo" width="50" height="50">
        </a>
    <div class="header-text">
        <h1>Anderson Robotics</h1>
        <p>More than human</p>
    </div>
    
    <nav>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li class="dropdown">
                <a href="products.php">Products</a>
                <div class="dropdown-content">
                    <a href="products/amur_series_recon_drone.php">Amur Series Recon Drone</a>
                    <a href="products/aplomado_series_facility_defense_unit.php">Aplomado Series Facility Defense Unit</a>
                    <a href="products/gyrfalcon_series_prosthetics.php">Gyrfalcon Series Prosthetics</a>
                    <a href="products/hobby_personal_android.php">Hobby Personal Android</a>
                    <a href="products/kestrel_series_domestic_utility_unit.php">Kestrel Series Domestic Utility Unit</a>
                    <a href="products/merlin_series_aerial_drone.php">Merlin Series Aerial Drone</a>
                    <a href="products/nankeen_computer_repair_system.php">Nankeen Computer Repair System</a>
                    <a href="products/peregrine_series_humanoid_utility_droid.php">Peregrine Series Humanoid Utility Droid</a>
                    <a href="products/saker_series_android.php">Saker Series Android</a>
                </div>
            </li>
            <li><a href="create_page.php">New Entry</a></li>
        </ul>
    </nav>
</header>
<div class="navigation">
    <?php if (isset($_SESSION['username'])): ?>
        <p>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</p>
        <!-- Show "Manage Comments" and "New Entry" links if the user is logged in -->
        <?php if ($_SESSION['role'] === 'Administrator'): ?>
            <a href="manage_comments.php">Manage Comments</a>
        <?php endif; ?>
        <!-- Logout as a hyperlink -->
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <!-- Show Login and Register links if the user is not logged in -->
        <a href="verify.php">Login</a>
        <a href="register.php">Register</a>
    <?php endif; ?>
</div>

<div class="container">
    <h1>Welcome to Anderson Robotics</h1>
    <h2>Explore Our Products</h2>
    <div class="product-list">
        <div class="product-item">
            <h3><a href="products/amur_series_recon_drone.php">Amur Series Recon Drone</a></h3>
            <p>Small, orb-shaped robots with retractable legs capable of climbing sheer surfaces. Equipped with video/audio recording and active camouflage systems.</p>
        </div>
        <div class="product-item">
            <h3><a href="products/aplomado_series_facility_defense_unit.php">Aplomado Series Facility Defense Unit</a></h3>
            <p>A two-meter auto-turret with thermal imaging and self-repairing armor, designed to defend facilities against intruders.</p>
        </div>
        <div class="product-item">
            <h3><a href="products/gyrfalcon_series_prosthetics.php">Gyrfalcon Series Prosthetics</a></h3>
            <p>Cybernetic limbs with parahuman abilities, exclusively offered to Marshall, Carter, and Dark Ltd. customers.</p>
        </div>
        <div class="product-item">
            <h3><a href="products/hobby_personal_android.php">Hobby Personal Android</a></h3>
            <p>A toy robot prototype designed for user assembly and programming, with personality traits selectable during construction.</p>
        </div>
        <div class="product-item">
            <h3><a href="products/kestrel_series_domestic_utility_unit.php">Kestrel Series Domestic Utility Unit</a></h3>
            <p>A versatile domestic robot designed for various tasks such as cleaning, cooking, and child care.</p>
        </div>
        <div class="product-item">
            <h3><a href="products/merlin_series_aerial_drone.php">Merlin Series Aerial Drone</a></h3>
            <p>A small, jet-like drone capable of carrying various armaments, with invisibility to most forms of video recording.</p>
        </div>
        <div class="product-item">
            <h3><a href="products/nankeen_computer_repair_system.php">Nankeen Computer Repair System</a></h3>
            <p>A flash drive AI prototype designed to repair basic computer issues, not yet available for sale.</p>
        </div>
        <div class="products/product-item">
            <h3><a href="peregrine_series_humanoid_utility_droid.php">Peregrine Series Humanoid Utility Droid</a></h3>
            <p>The top-selling humanoid utility droid, customizable for domestic tasks and combat, with advanced AI.</p>
        </div>
        <div class="products/product-item">
            <h3><a href="saker_series_android.php">Saker Series Android</a></h3>
            <p>Human-like androids designed for espionage, used to impersonate individuals, including SCP personnel.</p>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2024 Anderson Robotics. All rights reserved.</p>
</footer>

</body>
</html>