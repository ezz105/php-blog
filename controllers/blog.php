

<?php

require './config.php';

$title = "Blog - EzzBlog";

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
    $stmt->execute([
        ':id' => $_GET['edit']
    ]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        header("Location: blog");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postTitle = trim($_POST['title']);
        $postContent = trim($_POST['content']);

        $stmt = $pdo->prepare("UPDATE posts SET title = :title, content = :content WHERE id = :id");
        $stmt->execute([
            ':title' => $postTitle,
            ':content' => $postContent,
            ':id' => $_GET['edit']
        ]);

        header("Location: blog");
        exit();
    }

    exit();
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
    $stmt->execute([
        ':id' => $_GET['delete']
    ]);

    header("Location: blog");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postTitle = trim($_POST['title']);
    $postContent = trim($_POST['content']);
    $imageUrl = null;

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $imageUrl = $targetFile;
            }
        }
    }

    $stmt = $pdo->prepare("INSERT INTO posts (title, content, image_url, created_at) VALUES (:title, :content, :image_url, NOW())");
    $stmt->execute([
        ':title' => $postTitle,
        ':content' => $postContent,
        ':image_url' => $imageUrl,
    ]);

    header("Location: blog");
    exit();
}

$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'views/includes/header.php';
include 'views/blog.templet.html';
include 'views/includes/footer.php';

