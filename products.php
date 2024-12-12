<?php
// Include database connection
require 'connect.php';

// Function to create a URL-friendly slug
function generateSlug($title) {
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9-]+/', '_', $slug); // Replace non-alphanumeric characters with dashes
    $slug = trim($slug, '_'); // Remove leading and trailing dashes
    return $slug;
}

// Fetch all product pages
try {
    $sql = "SELECT * FROM pages WHERE Type = 'product'";
    $stmt = $pdo->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error retrieving products: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Listings</title>
    <link rel="stylesheet" href="styles.css">
    <header>
        <div class="logo">
        <a href="index.php">
            <img src="images/ar_logo_blue.png" alt="Anderson Robotics Logo" width="50" height="50">
        </a>
        </div>
        <div class="header-text">
            <h1>Anderson Robotics</h1>
            <p>More than human</p>
        </div>
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
                    <a href="products.php">Click Here to See More</a> 
                </div>
            </li>
            <li><a href="create_page.php">New Entry</a></li>
        </ul>
    </nav>
    </header>
</head>
<body>
    <style>
        .product-box {
            border: 1px solid #ccc;
            margin: 10px;
            padding: 15px;
            display: inline-block;
            width: 200px;
            text-align: center;
        }
        .product-box img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
        .product-box a {
            text-decoration: none;
            color: #0366d6;
            font-weight: bold;
        }
        .product-box a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Product Listings</h1>

    <!-- Display the products -->
    <div class="products">
        <?php if ($products): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-box">
                    <?php
                    // Dynamically generate the link using the slug
                    $slug = generateSlug($product['Title']);
                    $productLink = "/wd2/assignments/Project/products/" . $slug . ".php";
                    ?>
                    <a href="<?= htmlspecialchars($productLink); ?>">
                        <h3><?= htmlspecialchars($product['Title']); ?></h3>
                    </a>
                    <?php if (!empty($product['ImageURL'])): ?>
                        <img src="<?= htmlspecialchars($product['ImageURL']); ?>" alt="<?= htmlspecialchars($product['Title']); ?>">
                    <?php else: ?>
                        <p>No image available</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products available.</p>
        <?php endif; ?>
    </div>
    <footer>
    <p>&copy; 2024 Anderson Robotics. All rights reserved.</p>
</footer>
</body>
</html>
