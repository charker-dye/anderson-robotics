<?php
require 'connect.php'; // Ensure you have a proper database connection in 'connect.php'

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
    header("Location: amur_series_recon_drone.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gyrfalcon Series Prosthetics</title>
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
            <h2>Gyrfalcon Series Prosthetics</h2>
            <p>
                <strong>Overview:</strong><br>
                The Gyrfalcon Series Prosthetics represents the pinnacle of biomechanical engineering, designed to seamlessly integrate with the human body while enhancing natural movement and functionality. Precision-crafted and tailored to individual needs, the Gyrfalcon Series is redefining the possibilities of prosthetic technology.
            </p>

            <h3>Key Features:</h3>
            <ul>
                <li><strong>Advanced Biomechanical Design:</strong> Engineered for maximum comfort and natural movement, ensuring users can perform daily activities with ease.</li>
                <li><strong>Custom Fit and Functionality:</strong> Each prosthetic is uniquely tailored to the user's specific anatomy and requirements, offering a perfect blend of form and function.</li>
                <li><strong>Enhanced Sensory Feedback:</strong> Equipped with state-of-the-art sensors, the Gyrfalcon Series provides a heightened sense of touch and control.</li>
                <li><strong>Durable and Lightweight Materials:</strong> Built from aerospace-grade alloys and advanced polymers, these prosthetics are both strong and easy to wear.</li>
                <li><strong>Stylish and Modern Aesthetics:</strong> With a sleek, futuristic design, the Gyrfalcon Series offers a look as sophisticated as its technology.</li>
                <li><strong>Wireless Connectivity:</strong> Integrated with Anderson Robotics’ proprietary software, allowing real-time updates and diagnostics.</li>
            </ul>

            <h3>Applications:</h3>
            <p>The Gyrfalcon Series is ideal for:</p>
            <ul>
                <li>Individuals seeking cutting-edge prosthetic solutions</li>
                <li>Professionals in demanding fields requiring durable, high-performance prosthetics</li>
                <li>Athletes aiming to push their physical capabilities</li>
                <li>Military and law enforcement personnel</li>
            </ul>

            <h3>A Future Without Limits:</h3>
            <p>
                Anderson Robotics is proud to present the Gyrfalcon Series Prosthetics, a groundbreaking solution for those who refuse to let physical limitations define their potential. Experience the perfect harmony of technology and humanity with the Gyrfalcon Series.
            </p>
        </section>
    </main>
    <img src="images/GSP.png" alt="Gyrfalcon Series Prosthetics" width="500" height="250">
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
        $stmt = $pdo->prepare("SELECT Author, Content, ImagePath FROM comments WHERE PageID = 1"); // Use the actual page ID
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