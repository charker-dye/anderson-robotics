<?php
require '../connect.php'; // Ensure you have a proper database connection in 'connect.php'

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debugging output to check POST data
    echo '<pre>';
    print_r($_POST); 
    echo '</pre>';

    // Ensure the comment field is not empty
    if (empty($_POST['comment'])) {
        echo "Comment content cannot be empty!";
        exit;
    }

    // Collect form data
    $page_id = $_POST['page_id']; // Make sure the page ID for the product is set properly
    $name = !empty($_POST['name']) ? $_POST['name'] : null;
    $comment = $_POST['comment']; // Comment field from form
    $image_path = null;

    // Handle the image upload (if any)
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . '/uploads/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $fileType = mime_content_type($fileTmpPath);
        $newFileName = uniqid('img_', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        $uploadFilePath = $uploadDir . $newFileName;

        if (in_array($fileType, $allowedTypes)) {
            // Ensure the upload directory exists
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Move the uploaded file to the upload directory
            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                $image_path = $uploadFilePath;
            } else {
                echo "Error moving uploaded file.";
                exit;
            }
        } else {
            echo "Unsupported file type!";
            exit;
        }
    }

    // Insert the comment into the database
    $stmt = $pdo->prepare("INSERT INTO comments (PageID, Author, Content, ImagePath) VALUES (?, ?, ?, ?)");
    $stmt->execute([$page_id, $name, $comment, $image_path]);

    // Redirect to the product page
    header("Location: amur_series_recon_drone.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amur Series Recon Drone</title>
    <link rel="stylesheet" href="../styles.css">
    <header>
        <div class="logo">
        <a href="../index.php">
            <img src="../images/ar_logo_blue.png" alt="Anderson Robotics Logo" width="50" height="50">
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
            <li><a href="../index.php">Home</a></li>
            <li><a href="../about.php">About</a></li>
            <li class="dropdown">
            <a href="../products.php">Products</a>
                <div class="dropdown-content">
                    <a href="amur_series_recon_drone.php">Amur Series Recon Drone</a>
                    <a href="aplomado_series_facility_defense_unit.php">Aplomado Series Facility Defense Unit</a>
                    <a href="gyrfalcon_series_prosthetics.php">Gyrfalcon Series Prosthetics</a>
                    <a href="hobby_personal_android.php">Hobby Personal Android</a>
                    <a href="kestrel_series_domestic_utility_unit.php">Kestrel Series Domestic Utility Unit</a>
                    <a href="nankeen_computer_repair_system.php">Nankeen Computer Repair System</a>
                    <a href="peregrine_series_humanoid_utility_droid.php">Peregrine Series Humanoid Utility Droid</a>
                    <a href="saker_series_android.php">Saker Series Android</a>
                    <a href="/products.php">Click Here to See More</a> 
                </div>
            </li>
            <li><a href="../create_page.php">New Entry</a></li>
        </ul>
    </nav>
    </header>
</head>
<body>
    <div class="container">
        <main>
        <section class="product-description">
            <h2>Amur Series Recon Drone</h2>
            <p>
                <strong>Overview:</strong><br>
                The Amur Series Recon Drone is a compact, versatile surveillance device built for precision reconnaissance in challenging environments. Designed for discretion and adaptability, these drones excel in both urban and remote operations, making them an essential tool for modern intelligence gathering.
            </p>

            <h3>Key Features:</h3>
            <ul>
                <li><strong>Compact and Portable:</strong> Measuring just 5 cm in diameter, the Amur Series drone is small enough to fit in the palm of your hand, ensuring easy transport and deployment.</li>
                <li><strong>Retractable Legs for Mobility:</strong> With innovative retractable legs, the drone can traverse sheer surfaces, walls, and ceilings, providing unmatched flexibility in movement.</li>
                <li><strong>Advanced Video and Audio Recording:</strong> Equipped with high-definition video and audio recording capabilities, the Amur drone captures critical details in any situation, ensuring no data is missed.</li>
                <li><strong>Active Camouflage System:</strong> Utilizing cutting-edge adaptive materials, the drone seamlessly blends into its surroundings, remaining undetected during operations.</li>
                <li><strong>Silent Propulsion Technology:</strong> Whisper-quiet operation minimizes noise, allowing the Amur Series to conduct surveillance without alerting targets.</li>
                <li><strong>Durable Build:</strong> Built with lightweight yet sturdy materials, the drone withstands harsh conditions, ensuring reliable performance in extreme environments.</li>
            </ul>

            <h3>Applications:</h3>
            <p>The Amur Series Recon Drone is perfect for:</p>
            <ul>
                <li>Tactical surveillance missions</li>
                <li>Search-and-rescue operations</li>
                <li>Environmental monitoring</li>
                <li>Facility inspections</li>
                <li>Security assessments</li>
            </ul>

            <h3>Experience Precision Reconnaissance:</h3>
            <p>
                Anderson Robotics’ Amur Series Recon Drone redefines intelligence gathering, combining cutting-edge technology with unparalleled discretion. Elevate your operations with the next generation of recon technology.
            </p>
        </section>
    </main>
        <img src="../images/ASRD.png" alt="Amur Series Recon Drone" width="500" height="250">
        <h3>Leave a Comment:</h3>
        <form method="post" enctype="multipart/form-data">
            <!-- The page ID for the Amur Series Recon Drone -->
            <input type="hidden" name="page_id" value="1"> 

            <!-- Name input field -->
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name">
            </div>

            <!-- Comment input field -->
            <div>
                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment" rows="5" required></textarea>
            </div>

            <!-- Image upload input -->
            <div>
                <label for="image">Attach an image (optional):</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <button type="submit">Submit Comment</button>
        </form>

        <h3>Comments:</h3>
<!-- Fetch and display existing comments -->
<?php
$stmt = $pdo->prepare("SELECT Author, Content, ImagePath FROM comments WHERE PageID = 1 AND is_hidden = 0"); // Only show comments where is_hidden is 0 (not hidden)
$stmt->execute();
$comments = $stmt->fetchAll();

foreach ($comments as $comment) {
    echo '<div class="comment">';
    echo '<p><strong>' . htmlspecialchars($comment['Author']) . ':</strong></p>';
    echo '<p>' . nl2br(htmlspecialchars($comment['Content'])) . '</p>';
    if ($comment['ImagePath']) {
        // Assuming the 'uploads' folder is at the root level and the current page is in the 'products' folder
        echo '<img src="../uploads/' . htmlspecialchars($comment['ImagePath']) . '" alt="User uploaded image" style="max-width: 300px;">';
    }
    echo '</div>';
}
?>
    </div>
    <footer>
    <p>&copy; 2024 Anderson Robotics. All rights reserved.</p>
</footer>
</body>
</html>