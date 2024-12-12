<?php
require '../connect.php'; // Database connection

// Get the page_id from the URL
$page_id = isset($_GET['PageID']) ? intval($_GET['PageID']) : 0;

// Fetch the page details from the database
$stmt = $pdo->prepare("SELECT * FROM pages WHERE PageID = ?");
$stmt->execute([$page_id]);
$page = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$page) {
    echo "Page not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page['Title']) ?></title>
</head>
<body>
    <header>
        <h1><?= htmlspecialchars($page['Title']) ?></h1>
    </header>
    <main>
        <p><?= nl2br(htmlspecialchars($page['Content'])) ?></p>
        <?php if (!empty($page['ImageURL'])): ?>
            <img src="<?= htmlspecialchars($page['ImageURL']) ?>" alt="<?= htmlspecialchars($page['Title']) ?>">
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; 2024 Anderson Robotics. All rights reserved.</p>
    </footer>
</body>
</html>