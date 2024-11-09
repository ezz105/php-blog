<?php
// Check if the post ID is valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: blog.php');
    exit();
}

// Fetch the post data
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
$stmt->execute([':id' => $_GET['id']]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header("Location: blog.php");
    exit();
}

$title = "Delete Post - EzzBlog";

// If the form is submitted, delete the post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete the post from the database
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
    $stmt->execute([':id' => $_GET['id']]);

    // Redirect after deletion
    header("Location: blog.php");
    exit();
}

// Include the header and delete confirmation form
include 'views/includes/header.php';
?>
<h1>Are you sure you want to delete this post?</h1>
<p><strong>Title:</strong> <?php echo htmlspecialchars($post['title']); ?></p>
<p><strong>Content:</strong> <?php echo htmlspecialchars($post['content']); ?></p>

<form method="POST" action="delete.php?id=<?php echo $post['id']; ?>">
    <button type="submit" onclick="return confirm('Are you sure you want to delete this post?');">Yes, Delete</button>
    <a href="blog.php">Cancel</a>
</form>
<?php
include 'views/includes/footer.php';
?>
