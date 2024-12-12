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
        $uploadDir = 'uploads/';
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
    header("Location: aplomado_series_facility_defense_unit.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplomado Series Facility Defense Unit</title>
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
    </header>
</head>
<body>
    <div class="container">
        
        <main>
        <section class="product-description">
            <h2>Aplomado Series Facility Defense Unit</h2>
            <p>
                <strong>Overview:</strong><br>
                The Aplomado Series Facility Defense Unit is a state-of-the-art security solution designed to provide unparalleled protection for your assets. Combining advanced robotics with precision targeting systems, the Aplomado Series ensures the safety of high-priority locations.
            </p>

            <h3>Key Features:</h3>
            <ul>
                <li><strong>Compact and Adaptable Design:</strong> The Aplomado units are engineered to operate in confined spaces, making them ideal for indoor and outdoor security applications.</li>
                <li><strong>Precision Targeting System:</strong> Equipped with advanced targeting technology, these units can identify and neutralize threats with exceptional accuracy.</li>
                <li><strong>Multi-Layered Defense Mechanisms:</strong> Combining non-lethal deterrents with high-powered armaments, the Aplomado Series offers a scalable response to various threats.</li>
                <li><strong>Real-Time Monitoring:</strong> Integrated with live-feed video and audio capabilities, the units provide constant surveillance of the area.</li>
                <li><strong>Autonomous Navigation:</strong> Using advanced AI algorithms, the units navigate complex environments, patrolling assigned areas without human intervention.</li>
                <li><strong>Durable and Weather-Resistant:</strong> Built to withstand extreme conditions, the Aplomado Series operates reliably in all environments.</li>
            </ul>

            <h3>Applications:</h3>
            <p>The Aplomado Series Facility Defense Unit is ideal for:</p>
            <ul>
                <li>High-security facilities</li>
                <li>Critical infrastructure protection</li>
                <li>Warehousing and storage areas</li>
                <li>Military installations</li>
                <li>Corporate campuses</li>
            </ul>

            <h3>Unmatched Facility Protection:</h3>
            <p>
                With the Aplomado Series Facility Defense Unit, Anderson Robotics delivers a robust and reliable solution for safeguarding your most valuable assets. Invest in cutting-edge security technology and experience peace of mind like never before.
            </p>
        </section>
    </main>
        
        <h3>Leave a Comment:</h3>
        <form method="post" enctype="multipart/form-data">
            <!-- The page ID for the Aplomado Series Facility Defense Unit -->
            <input type="hidden" name="page_id" value="2"> 

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
        $stmt = $pdo->prepare("SELECT Author, Content, ImagePath FROM comments WHERE PageID = 2"); // Use the actual page ID
        $stmt->execute();
        $comments = $stmt->fetchAll();
        
        foreach ($comments as $comment) {
            echo '<div class="comment">';
            echo '<p><strong>' . htmlspecialchars($comment['Author']) . ':</strong></p>';
            echo '<p>' . nl2br(htmlspecialchars($comment['Content'])) . '</p>';
            if ($comment['ImagePath']) {
                echo '<img src="' . htmlspecialchars($comment['ImagePath']) . '" alt="User uploaded image" style="max-width: 300px;">';
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