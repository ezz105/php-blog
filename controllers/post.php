<?php

require './config.php'; // Connect to the database






// Check if an ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Post not found.");
}

$postId = $_GET['id'];

// Fetch the post from the database
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
$stmt->execute([':id' => $postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the post exists
if (!$post) {
    die("Post not found.");
}







include 'views/includes/header.php';

include 'views/post.templet.html';

include 'views/includes/footer.php';
