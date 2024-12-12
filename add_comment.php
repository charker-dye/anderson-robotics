<?php
require 'connect.php';

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
    $page_id = $_POST['page_id'];
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

    // Redirect back to the page that shows the comments, with the correct page_id in the URL
    header("Location: page.php?page_id=" . $page_id);
    exit;
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
    <title>Add Comment</title>
</head>
<body>
    <h1>Add Comment</h1>
    <?php if (!isset($page_id)): ?>
        <p>Error: No page ID provided!</p>
    <?php else: ?>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="page_id" value="<?= htmlspecialchars($page_id); ?>">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name">
            </div>
            <div>
                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment" rows="5" required></textarea>
            </div>
            <div>
                <label for="image">Attach an image (optional):</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <button type="submit">Submit</button>
        </form>
    <?php endif; ?>
</body>
</html>