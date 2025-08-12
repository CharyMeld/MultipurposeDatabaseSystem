<?php
require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roleId = intval($_POST['role_id']);
    $permissions = $_POST['permissions'] ?? [];

    // Remove existing permissions
    $stmt = $conn->prepare("DELETE FROM role_permissions WHERE role_id = ?");
    $stmt->bind_param("i", $roleId);
    $stmt->execute();

    // Insert selected permissions
    $stmt = $conn->prepare("INSERT INTO role_permissions (role_id, permission) VALUES (?, ?)");
    foreach ($permissions as $perm) {
        $stmt->bind_param("is", $roleId, $perm);
        $stmt->execute();
    }

    header("Location: roles.php");
    exit();
}

