<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/UserManagementController.php';

// Initialize controller
$userController = new UserManagementController($pdo);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userData = [
        'login_name'     => $_POST['login_name'] ?? '',
        'login_password' => $_POST['login_password'] ?? '',
        'firstname'      => $_POST['firstname'] ?? '',
        'lastname'       => $_POST['lastname'] ?? '',
        'middlename'     => $_POST['middlename'] ?? '',
        'gender'         => $_POST['gender'] ?? '',
        'marital_status' => $_POST['marital_status'] ?? '',
        'role_id'        => $_POST['role_id'] ?? '',
    ];

    $userController->createUser($userData); // You must have a createUser() method in the controller

    // Optional: Redirect or show success message
    header('Location: users.php?success=1');
    exit();
}

// Fetch roles for the form
$roles = $userController->getAllRoles(); // This must be defined in controller too
?>

