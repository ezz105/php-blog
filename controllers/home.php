<?php



require './config.php'; // Connect to the database

$title = "Blog - EzzBlog"; // Page title

// Handle form submission for new post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $postTitle = trim($_POST['title']);
    $postContent = trim($_POST['content']);

    // Insert new post into the database
    $stmt = $pdo->prepare("INSERT INTO posts (title, content, created_at) VALUES (:title, :content, NOW())");
    $stmt->execute([
        ':title' => $postTitle,
        ':content' => $postContent
    ]);

    // Redirect to the same page to show the newly added post
    header("Location: blog");
    exit();
}

// Fetch all posts from the database
$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);




include 'views/includes/header.php';

include 'views/home.templet.html';


include 'views/includes/footer.php';
