<?php
require_once __DIR__ . '/../../config/database.php';

$roleId = $_GET['id'] ?? null;
if (!$roleId) {
    echo "<div class='alert alert-danger'>Invalid Role ID</div>";
    exit;
}

// Fetch role name
$stmt = $conn->prepare("SELECT name FROM role WHERE id = ?");
$stmt->bind_param("i", $roleId);
$stmt->execute();
$result = $stmt->get_result();
$role = $result->fetch_assoc();

if (!$role) {
    echo "<div class='alert alert-danger'>Role not found.</div>";
    exit;
}

// Define all possible permissions
$allPermissions = [
    'view_users' => 'View Users',
    'edit_users' => 'Edit Users',
    'delete_users' => 'Delete Users',
    'manage_roles' => 'Manage Roles',
    'view_reports' => 'View Reports',
];

// Fetch assigned permissions
$stmt = $conn->prepare("SELECT permission FROM role_permissions WHERE role_id = ?");
$stmt->bind_param("i", $roleId);
$stmt->execute();
$permResult = $stmt->get_result();
$assigned = [];
while ($row = $permResult->fetch_assoc()) {
    $assigned[] = $row['permission'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Role Permissions - <?= htmlspecialchars($role['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3>🔑 Permissions for: <?= htmlspecialchars($role['name']) ?></h3>
    <p class="text-muted">Check the permissions you want to assign to this role.</p>

    <form method="POST" action="update_permissions.php">
        <input type="hidden" name="role_id" value="<?= $roleId ?>">

        <?php foreach ($allPermissions as $key => $label): ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="<?= $key ?>" id="<?= $key ?>"
                    <?= in_array($key, $assigned) ? 'checked' : '' ?>>
                <label class="form-check-label" for="<?= $key ?>"><?= $label ?></label>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary mt-3">💾 Save Permissions</button>
        <a href="roles.php" class="btn btn-secondary mt-3">← Back to Roles</a>
    </form>
</div>
</body>
</html>

