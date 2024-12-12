<?php
require 'connect.php';

$page_id = null; // Initialize variable for later use

if (isset($_GET['page_id']) && is_numeric($_GET['page_id'])) {
    $page_id = intval($_GET['page_id']); // Sanitize the input to ensure it's an integer

    try {
        // Query the database for the page with the given PageID
        $stmt = $pdo->prepare("SELECT * FROM pages WHERE PageID = ?");
        $stmt->execute([$page_id]);

        // Fetch the page data
        $page = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($page) {
            // Display the page content
            
        } else {
            echo "Page not found.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Database error: " . htmlspecialchars($e->getMessage());
        exit;
    }
} else {
    echo "Invalid or missing page ID.";
    echo "<br>DEBUG: " . htmlspecialchars(var_export($_GET, true));
    exit;
}

// Fetch comments for the page
try {
    $stmt = $pdo->prepare("SELECT * FROM comments WHERE PageID = ? ORDER BY CreatedAt DESC");
    $stmt->execute([$page_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching comments: " . htmlspecialchars($e->getMessage());
    $comments = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page['Title'] ?? 'Page'); ?></title>
</head>
<body>
    <!-- Header Section -->
    <header>
    <a href="index.php">
            <img src="images/ar_logo_blue.png" alt="Anderson Robotics Logo" width="50" height="50">
        </a>
        <div class="header-text">
            <h1>Anderson Robotics</h1>
            <p>More than human</p>
        </div>
    </header>

    <!-- Main Content Section -->
    <div class="content">
        <h1><?= htmlspecialchars($page['Title'] ?? 'Unknown Page'); ?></h1>
        <p><?= nl2br(htmlspecialchars($page['Content'] ?? 'No content available.')); ?></p>

        <!-- Comments Section -->
        <h2>Comments</h2>
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p><strong><?= htmlspecialchars($comment['Author'] ?? 'Anonymous'); ?></strong></p>
                    <p><?= nl2br(htmlspecialchars($comment['Content'])); ?></p>

                    <?php if (!empty($comment['ImagePath'])): ?>
                        <img src="<?= htmlspecialchars($comment['ImagePath']); ?>" alt="Attached Image" style="max-width: 200px;">
                    <?php endif; ?>

                    <p><small><?= htmlspecialchars($comment['CreatedAt']); ?></small></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php endif; ?>

        <!-- Add Comment Form -->
        <h2>Add a Comment</h2>
        <form action="add_comment.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="page_id" value="<?= htmlspecialchars($page['PageID']); ?>">
            <p>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name">
            </p>
            <p>
                <label for="comment">Comment:</label>
                <textarea name="comment" id="comment" required></textarea>
            </p>
            <p>
                <label for="image">Attach an image (optional):</label>
                <input type="file" name="image" id="image" accept="image/*">
            </p>
            <p>
                <button type="submit">Submit</button>
            </p>
        </form>
    </div>
</body>
</html>