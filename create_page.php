<?php

require 'connect.php'; // Database connection
 // To check session data

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
}
$errorMessage = '';
$successMessage = '';
if (!empty($username) && !empty($password)) {
    // Assuming you're storing passwords securely using hashing
    $stmt = $pdo->prepare("SELECT userID, password FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo '<pre>' . print_r($_SESSION, true) . '</pre>';
    if ($user && password_verify($password, $user['password'])) {
        // Login successful, set the session
        $_SESSION['userID'] = $user['userID']; // Store userID in session

        // Debugging to check session after login
        echo '<pre>' . print_r($_SESSION, true) . '</pre>';

        header("Location: create_page.php"); // Redirect to create page
        exit();
    } else {
        echo "Invalid credentials.";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user inputs
    $title = trim($_POST['Title']);
    $content = trim($_POST['Content']);
    $author_id = $_SESSION['user_id'] ?? null;
    $image_url = $_POST['ImageURL']?? null; // Get the selected image ID from the dropdown
    $content = htmlspecialchars($content);
    // Validate input
    if (empty($title) || empty($content)) {
        $errorMessage = "Title and content are required!";
    } 
    if (strlen($title) > 255) {
        $errorMessage = 'Title cannot be longer than 255 characters.';
    }
    if (!empty($errorMessage)) {
        echo "<p style='color: red;'>$errorMessage</p>";
    }
    else {
        // Insert page into the database
        $stmt = $pdo->prepare("INSERT INTO pages (Title, Content, AuthorID, ImageURL) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $content, $author_id, $image_url]);

        // Get the last inserted page ID
        $lastInsertId = $pdo->lastInsertId();

        // Sanitize the title for use as a filename
        $sanitizedTitle = preg_replace('/[^a-zA-Z0-9-_]+/', '_', $title); // Replace non-alphanumeric characters with hyphens
        $fileName = "products/{$sanitizedTitle}.php"; // Use sanitized title as the filename

        // Create the content for the PHP file
        $fileContent = "<?php\n";
        $fileContent .= "require '../connect.php';\n";
        $fileContent .= "\$page_id = $lastInsertId;\n";
        $fileContent .= "\$stmt = \$pdo->prepare('SELECT * FROM pages WHERE PageID = ?');\n";
        $fileContent .= "\$stmt->execute([\$page_id]);\n";
        $fileContent .= "\$page = \$stmt->fetch(PDO::FETCH_ASSOC);\n";
        $fileContent .= "if (!\$page) { echo 'Page not found.'; exit; }\n";
        $fileContent .= "?>\n\n";
        $fileContent .= "<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n";
        $fileContent.= '<link rel="stylesheet" href="../styles.css">';
        $fileContent .= "<header>";
        $fileContent .= '<div class="logo">';
        $fileContent .= '<a href="index.php">';
        $fileContent .= '<img src="../images/ar_logo_blue.png" alt="Anderson Robotics Logo" width="50" height="50">';
        $fileContent .= '</a>';
        $fileContent .= '</div>';
        $fileContent .= '<div class="header-text">';
        $fileContent .= '<h1>Anderson Robotics</h1>';
        $fileContent .= '<p>More than human</p>';
        $fileContent .= '</div>';
        $fileContent .= ':root {';
        $fileContent .= '    --light-accent: #295183;  /* Blue */';
        $fileContent .= '    --dark-accent: #1e3c62;   /* Dark blue */';
        $fileContent .= '    --hyperlink: #0366d6;     /* Link color */';
        $fileContent .= '    --border-colour: rgba(0, 0, 0, 0.12); /* Border */';
        $fileContent .= '}';
        
        $fileContent .= 'body, h1, h2 {';
        $fileContent .= '    background-color: white;';
        $fileContent .= '    color: black;';
        $fileContent .= '    margin: 0;';
        $fileContent .= '    padding: 0;';
        $fileContent .= '    font-family: \'Roboto Mono\', monospace;';
        $fileContent .= '}';
        
        $fileContent .= 'h1, h2 {';
        $fileContent .= '    font-family: \'Raleway\', sans-serif;';
        $fileContent .= '}';
        
        $fileContent .= 'header, .header-bar {';
        $fileContent .= '    background-color: var(--light-accent);';
        $fileContent .= '    padding: 10px 20px;';
        $fileContent .= '    color: white;';
        $fileContent .= '    display: flex;';
        $fileContent .= '    justify-content: space-between;';
        $fileContent .= '    align-items: center;';
        $fileContent .= '}';
        
        $fileContent .= 'header * {';
        $fileContent .= '    color: inherit;';
        $fileContent .= '    background-color: transparent;  /* Ensure no white background */';
        $fileContent .= '}';
        
        $fileContent .= '.header-bar .auth-links {';
        $fileContent .= '    display: flex;';
        $fileContent .= '    gap: 10px;';
        $fileContent .= '}';
        
        $fileContent .= '.header-bar a {';
        $fileContent .= '    color: inherit;';
        $fileContent .= '    text-decoration: none;';
        $fileContent .= '}';
        
        $fileContent .= '.header-bar a:hover {';
        $fileContent .= '    text-decoration: underline;';
        $fileContent .= '}';
        
        $fileContent .= '.logo img {';
        $fileContent .= '    height: 50px;';
        $fileContent .= '}';
        
        $fileContent .= 'nav .nav-links {';
        $fileContent .= '    display: flex;';
        $fileContent .= '    list-style: none;';
        $fileContent .= '}';
        
        $fileContent .= '.nav-links li {';
        $fileContent .= '    margin-right: 20px;';
        $fileContent .= '}';
        
        $fileContent .= '.nav-links a {';
        $fileContent .= '    color: inherit;';
        $fileContent .= '    text-decoration: none;';
        $fileContent .= '    font-size: 16px;';
        $fileContent .= '}';
        
        $fileContent .= '.nav-links li:hover .dropdown-content {';
        $fileContent .= '    display: block;';
        $fileContent .= '}';
        
        $fileContent .= '.dropdown-content {';
        $fileContent .= '    display: none;';
        $fileContent .= '    position: absolute;';
        $fileContent .= '    background-color: var(--dark-accent);';
        $fileContent .= '    min-width: 160px;';
        $fileContent .= '    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);';
        $fileContent .= '}';
        
        $fileContent .= '.dropdown-content a {';
        $fileContent .= '    color: white;';
        $fileContent .= '    padding: 10px;';
        $fileContent .= '    text-decoration: none;';
        $fileContent .= '    display: block;';
        $fileContent .= '}';
        
        $fileContent .= '.dropdown-content a:hover {';
        $fileContent .= '    background-color: var(--light-accent);';
        $fileContent .= '}';
        
        $fileContent .= '.container {';
        $fileContent .= '    padding: 20px;';
        $fileContent .= '}';
        
        $fileContent .= '.product-list {';
        $fileContent .= '    display: grid;';
        $fileContent .= '    grid-template-columns: repeat(3, 1fr);';
        $fileContent .= '    gap: 20px;';
        $fileContent .= '}';
        
        $fileContent .= '.product-item {';
        $fileContent .= '    background-color: white;';
        $fileContent .= '    padding: 15px;';
        $fileContent .= '    border-radius: 8px;';
        $fileContent .= '    box-shadow: 0px 4px 6px var(--border-colour);';
        $fileContent .= '}';
        
        $fileContent .= '.product-item a {';
        $fileContent .= '    color: var(--hyperlink);';
        $fileContent .= '    text-decoration: none;';
        $fileContent .= '}';
        
        $fileContent .= '.product-item a:hover {';
        $fileContent .= '    text-decoration: underline;';
        $fileContent .= '}';
        
        $fileContent .= 'footer {';
        $fileContent .= '    background-color: var(--light-accent);';
        $fileContent .= '    color: white;';
        $fileContent .= '    text-align: center;';
        $fileContent .= '    padding: 10px 0;';
        $fileContent .= '    position: fixed;';
        $fileContent .= '    width: 100%;';
        $fileContent .= '    bottom: 0;';
        $fileContent .= '}';
        
        $fileContent .= 'a {';
        $fileContent .= '    color: var(--hyperlink);';
        $fileContent .= '    text-decoration: none;';
        $fileContent .= '}';
        
        $fileContent .= 'a:hover {';
        $fileContent .= '    text-decoration: underline;';
        $fileContent .= '}';
        $fileContent .= '<nav>';
        $fileContent .= '    <ul class="nav-links">';
        $fileContent .= '        <li><a href="index.php">Home</a></li>';
        $fileContent .= '        <li><a href="about.php">About</a></li>';
        $fileContent .= '        <li class="dropdown">';
        $fileContent .= '            <a href="products.php">Products</a>';
        $fileContent .= '            <div class="dropdown-content">';
        $fileContent .= '                <a href="products/amur_series_recon_drone.php">Amur Series Recon Drone</a>';
        $fileContent .= '                <a href="products/aplomado_series_facility_defense_unit.php">Aplomado Series Facility Defense Unit</a>';
        $fileContent .= '                <a href="products/gyrfalcon_series_prosthetics.php">Gyrfalcon Series Prosthetics</a>';
        $fileContent .= '                <a href="products/hobby_personal_android.php">Hobby Personal Android</a>';
        $fileContent .= '                <a href="products/kestrel_series_domestic_utility_unit.php">Kestrel Series Domestic Utility Unit</a>';
        $fileContent .= '                <a href="products/merlin_series_aerial_drone.php">Merlin Series Aerial Drone</a>';
        $fileContent .= '                <a href="products/nankeen_computer_repair_system.php">Nankeen Computer Repair System</a>';
        $fileContent .= '                <a href="products/peregrine_series_humanoid_utility_droid.php">Peregrine Series Humanoid Utility Droid</a>';
        $fileContent .= '                <a href="products/saker_series_android.php">Saker Series Android</a>';
        $fileContent .= '            </div>';
        $fileContent .= '        </li>';
        $fileContent .= '        <li><a href="create_page.php">New Entry</a></li>';
        $fileContent .= '    </ul>';
        $fileContent .= '</nav>';
        $fileContent .= '</header>';
        $fileContent .= "<meta charset=\"UTF-8\">\n<title>" . htmlspecialchars($title) . "</title>\n</head>\n<body>\n";
        $fileContent .= "<h1>" . htmlspecialchars($title) . "</h1>\n";
        $fileContent .= "<p>" . nl2br(htmlspecialchars($content)) . "</p>\n";
        $fileContent .= "</body>\n</html>";
        $fileContent .= '<h3>Leave a Comment:</h3>';
        $fileContent .= '<form method="post" enctype="multipart/form-data">';
        $fileContent .= '    <!-- The page ID for the Amur Series Recon Drone -->';
        $fileContent .= '    <input type="hidden" name="page_id" value="1">'; // Use dynamic page ID if needed
        $fileContent .= '';
        $fileContent .= '    <!-- Name input field -->';
        $fileContent .= '    <div>';
        $fileContent .= '        <label for="name">Name:</label>';
        $fileContent .= '        <input type="text" id="name" name="name">';
        $fileContent .= '    </div>';
        $fileContent .= '';
        $fileContent .= '    <!-- Comment input field -->';
        $fileContent .= '    <div>';
        $fileContent .= '        <label for="comment">Comment:</label>';
        $fileContent .= '        <textarea id="comment" name="comment" rows="5" required></textarea>';
        $fileContent .= '    </div>';
        $fileContent .= '';
        $fileContent .= '    <!-- Image upload input -->';
        $fileContent .= '    <div>';
        $fileContent .= '        <label for="image">Attach an image (optional):</label>';
        $fileContent .= '        <input type="file" id="image" name="image" accept="image/*">';
        $fileContent .= '    </div>';
        $fileContent .= '';
        $fileContent .= '    <button type="submit">Submit Comment</button>';
        $fileContent .= '</form>';
        $fileContent .= '';
        $fileContent .= '<h3>Comments:</h3>';
        $fileContent .= '    <!-- Fetch and display existing comments -->';
        $fileContent .= '    <?php';
        $fileContent .= '    $stmt = $pdo->prepare("SELECT Author, Content, ImagePath FROM comments WHERE PageID = 1"); // Use the actual page ID';
        $fileContent .= '    $stmt->execute();';
        $fileContent .= '    $comments = $stmt->fetchAll();';
        $fileContent .= '';
        $fileContent .= '    foreach ($comments as $comment) {';
        $fileContent .= '        echo \'<div class="comment">\';';
        $fileContent .= '        echo \'<p><strong>\' . htmlspecialchars($comment[\'Author\']) . \':</strong></p>\';';
        $fileContent .= '        echo \'<p>\' . nl2br(htmlspecialchars($comment[\'Content\'])) . \'</p>\';';
        $fileContent .= '        if ($comment[\'ImagePath\']) {';
        $fileContent .= '            echo \'<img src="\' . htmlspecialchars($comment[\'ImagePath\']) . \'" alt="User uploaded image" style="max-width: 300px;">\';';
        $fileContent .= '        }';
        $fileContent .= '        echo \'</div>\';';
        $fileContent .= '    }';
        $fileContent .= '    ?>';
        $fileContent.= "\n"; // Add a newline for better readability
        $fileContent.= "<footer>";
        $fileContent.= "<p>&copy; 2024 Anderson Robotics. All rights reserved.</p>";
        $fileContent.= "</footer>";

        // Write the content to the PHP file
        if (file_put_contents($fileName, $fileContent)) {
            $successMessage = "Page created and file generated successfully!";
        } else {
            $errorMessage = "Failed to create the PHP file.";
        }

        // Redirect to the newly created page
        header("Location: products/view_page.php?page_id=$lastInsertId");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Page</title>
</head>
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
<body>
    <h1>Create New Page</h1>

    <?php if ($errorMessage): ?>
        <div style="color: red;"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <?php if ($successMessage): ?>
        <div style="color: green;"><?php echo $successMessage; ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div>
            <label for="Title">Title:</label>
            <input type="text" id="Title" name="Title" required>
        </div>
        <div>
            <label for="Content">Content:</label>
            <textarea id="Content" name="Content" rows="5" required></textarea>
        </div>
        <div>
            <label for="image">Attach an image (optional):</label>
            <input type="file" id="image" name="image" accept="image/*">
        </div>
        <button type="submit">Create Page</button>
    </form>

    <footer>
        <p>&copy; 2024 Anderson Robotics. All rights reserved.</p>
    </footer>
</body>
</html>
