<?php

require_once '../../config/db.php';
require_once '../../utils/response.php';
require_once '../../utils/validate.php';

$data = json_decode(file_get_contents("php://input"), true);

$required = ['name', 'email', 'password', 'role'];
$check = validateRequiredFields($data, $required);
if(!$check['valid']) {
    sendResponse(400, 'error', 'Missing field: '.$check['missing']);
}

$name = sanitizeInput($data['name']);
$email = sanitizeInput($data['email']);
$password = password_hash($data['password'], PASSWORD_BCRYPT);
$role = sanitizeInput($data['role']);

// Check for duplicate mail
$stmt = $pdo->prepare("SELECT id FROM employees WHERE email = ?");
$stmt->execute([$email]);
if($stmt->fetch()) {
    sendResponse(400, 'error', 'Email already registered');
}

// Generate a simple auth token
$token = bin2hex(randoom_bytes(16));

$stmt = $pdo->prepare("INSERT INTO employees (name, email, password, role, token) VALUES (?,?,?,?,?)");
$success = $stmt->execute([$name, $email, $password, $role, $token]);

if($success) {
    sendResponse(201, 'success', 'Employee registered successfully', ['token' => $token]);
}
else {
    sendResponse(500, 'error', 'Failed to register employee');
}
?>