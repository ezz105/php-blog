<?php
// Check if the post exists and if we're editing a specific post.
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: blog.php');
    exit();
}

// Fetch the post data
global $pdo;
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
$stmt->execute([':id' => $_GET['id']]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = $GLOBALS['pdo']->prepare("SELECT * FROM posts WHERE id = :id");

$stmt->execute([':id' => $_GET['id']]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header("Location: blog.php");
    exit();
}

$title = "Edit Post - EzzBlog";

// If the form is submitted, update the post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postTitle = trim($_POST['title']);
    $postContent = trim($_POST['content']);

    // Update the post in the database
    $stmt = $pdo->prepare("UPDATE posts SET title = :title, content = :content WHERE id = :id");
    $stmt->execute([
        ':title' => $postTitle,
        ':content' => $postContent,
        ':id' => $post['id']
    ]);

    header("Location: blog.php");
    exit();
}

// Include the header and edit form
include 'views/includes/header.php';
?>
<h1>Edit Post</h1>
<form method="POST" action="edit.php?id=<?php echo $post['id']; ?>">
    <div>
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
    </div>
    <div>
        <label for="content">Content</label>
        <textarea id="content" name="content" rows="4" required><?php echo htmlspecialchars($post['content']); ?></textarea>
    </div>
    <button type="submit">Update Post</button>
</form>
<?php
include 'views/includes/footer.php';
?>
