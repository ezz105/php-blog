<?php
require './config.php'; 

// Fetch the latest posts from the database
$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 3"); // Fetch latest 3 posts
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

include './views/includes/header.php';
include './views/about.templet.html';
include './views/includes/footer.php';
