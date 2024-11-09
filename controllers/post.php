<?php
session_start(); // Start the session for CSRF token handling

require './config.php'; // Connect to the database

// Initialize variables
$postId = $_GET['id'] ?? null;
$post = null;
$errorMessage = "Post not found.";

// Validate the post ID
if ($postId === null || !is_numeric($postId)) {
    header("Location: 404"); // Redirect to a 404 page if post ID is invalid
    exit();
}

// Handle form submission for delete post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && isset($_POST['csrf_token'])) {
    // Validate CSRF token
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }

    // Delete the post
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
    $stmt->execute([':id' => $postId]);

    // Redirect to blog after deletion
    header("Location: blog");
    exit();
}

// Handle form submission for edit post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit']) && isset($_POST['csrf_token'])) {
    // Validate CSRF token
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }

    // Update the post in the database
    $stmt = $pdo->prepare("UPDATE posts SET title = :title, content = :content WHERE id = :id");
    $stmt->execute([
        ':title' => $_POST['title'],
        ':content' => $_POST['content'],
        ':id' => $postId
    ]);

    // Redirect to the same page to show the update
    header("Location: post?id=$postId");
    exit();
}

// Fetch the post from the database
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
$stmt->execute([':id' => $postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the post exists
if (!$post) {
    header("Location: 404"); // Redirect to a 404 page if post not found
    exit();
}

// Generate a CSRF token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Include header, template, and footer
include __DIR__ . '/../views/includes/header.php';
include __DIR__ . '/../views/post.templet.html';
include __DIR__ . '/../views/includes/footer.php';
