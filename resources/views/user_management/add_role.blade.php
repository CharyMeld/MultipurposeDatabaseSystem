<?php
require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $status = 1;

    if (!empty($name) && !empty($description)) {
        $stmt = $pdo->prepare("INSERT INTO Role (name, description, status, time_created) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("ssi", $name, $description, $status);
        $stmt->execute();
    }
}

header("Location: index.php?action=roles");
exit;

