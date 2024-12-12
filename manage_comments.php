<?php

session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Administrator') {
    header('Location: verify.php');
    exit;
}


require 'connect.php';



// Get the search query (if any)
$search = $_GET['search'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10; // Number of comments per page
$offset = ($page - 1) * $perPage;

// Debugging - Check the search query
echo 'Search Query: ' . htmlspecialchars($search) . '<br>';

$totalCommentsQuery = "
    SELECT COUNT(*) 
    FROM comments c 
    JOIN pages p ON c.PageID = p.PageID 
    WHERE (c.Content LIKE :search OR c.Author LIKE :search OR p.Title LIKE :search)
";
$stmt = $pdo->prepare($totalCommentsQuery);
$stmt->execute([':search' => "%$search%"]);
$totalComments = $stmt->fetchColumn();
$totalPages = ceil($totalComments / $perPage);

echo 'Total Comments: ' . $totalComments . '<br>';
echo 'Total Pages: ' . $totalPages . '<br>';

// Fetch paginated comments
$commentsQuery = "
    SELECT c.*, p.Title AS page_title 
    FROM comments c 
    JOIN pages p ON c.PageID = p.PageID 
    WHERE (c.Content LIKE :search OR c.Author LIKE :search OR p.Title LIKE :search)
    ORDER BY c.CreatedAt DESC 
    LIMIT :perPage OFFSET :offset
";

$stmt = $pdo->prepare($commentsQuery);
$stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$comments = $stmt->fetchAll();

// Debugging: Check the number of fetched comments
echo 'Fetched Comments Count: ' . count($comments) . '<br>';

// Display comments
if ($comments) {
    foreach ($comments as $comment) {
        echo '<p>' . htmlspecialchars($comment['Content']) . '</p>';
    }
} else {
    echo '<p>No comments found.</p>';
}

// Handle hide/delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['hide'])) {
        $stmt = $pdo->prepare("UPDATE comments SET is_hidden = 1 WHERE CommentID = ?");
        $stmt->execute([$_POST['comment_id']]);
    } elseif (isset($_POST['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM comments WHERE CommentID = ?");
        $stmt->execute([$_POST['comment_id']]);
    }
    header("Location: manage_comments.php?search=" . urlencode($search) . "&page=" . $page);
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
    <title>Manage Comments</title>
</head>
<body>
<h1>Manage Comments</h1>
    <p>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</p>
    
    <!-- Search Form -->
    <form method="get" action="manage_comments.php">
        <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" placeholder="Search comments...">
        <button type="submit">Search</button>
    </form>

    <!-- Comments Table -->
    <table>
        <thead>
            <tr>
                <th>Page</th>
                <th>Name</th>
                <th>Comment</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($comments): ?>
                <?php foreach ($comments as $comment): ?>
                    <tr>
                        <td><?= htmlspecialchars($comment['page_title']); ?></td>
                        <td><?= htmlspecialchars($comment['Author'] ?: 'Anonymous'); ?></td>
                        <td><?= htmlspecialchars($comment['Content']); ?></td>
                        <td><?= htmlspecialchars($comment['CreatedAt']); ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="comment_id" value="<?= $comment['CommentID']; ?>">
                                <button name="hide" <?= $comment['is_hidden'] ? 'disabled' : ''; ?>>Hide</button>
                            </form>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="comment_id" value="<?= $comment['CommentID']; ?>">
                                <button name="delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No comments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="manage_comments.php?search=<?= urlencode($search); ?>&page=<?= $i; ?>"
               style="<?= $i === $page ? 'font-weight:bold;' : ''; ?>">
               <?= $i; ?>
            </a>
        <?php endfor; ?>
    </div>

    <form method="post" action="logout.php">
        <button type="submit">Logout</button>
    </form>
</body>
</html>