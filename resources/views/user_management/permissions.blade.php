<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../app/controllers/UserManagementController.php';

$controller = new UserManagementController($pdo);
$permissions = $controller->getAllPermissions();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Permissions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f8f9fa;
        }

        h2 {
            color: #333;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            background: #fff;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #343a40;
            color: white;
        }

        .btn {
            padding: 6px 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .status-active {
            color: green;
            font-weight: bold;
        }

        .status-disabled {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>🔐 Manage Permissions</h2>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Role</th>
            <th>Page/Menu</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($permissions)): ?>
            <?php foreach ($permissions as $index => $perm): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($perm['role_name']) ?></td>
                    <td><?= htmlspecialchars($perm['menu_name']) ?></td>
                    <td class="<?= $perm['status'] ? 'status-active' : 'status-disabled' ?>">
                        <?= $perm['status'] ? 'Active' : 'Disabled' ?>
                    </td>
                    <td><?= htmlspecialchars($perm['time_created']) ?></td>
                    <td>
                        <a class="btn" href="edit_permission.php?id=<?= $perm['id'] ?>">Edit</a>
                        <a class="btn" href="delete_permission.php?id=<?= $perm['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">No permissions found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>

