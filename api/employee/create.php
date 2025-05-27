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
$stmt = $conn->prepare("SELECT id FROM employees WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
if($stmt->fetch()) {
    sendResponse(400, 'error', 'Email already registered');
}

// Generate a simple auth token
$token = bin2hex(random_bytes(16));

$stmt = $conn->prepare("INSERT INTO employees (name, email, password, role, token) VALUES (?,?,?,?,?)");
$stmt->bind_param("sssss", $name, $email, $password, $role, $token);
$success = $stmt->execute();

if($success) {
    sendResponse(201, 'success', 'Employee registered successfully', ['token' => $token]);
}
else {
    sendResponse(500, 'error', 'Failed to register employee');
}
?>


/*
{
            "id": "7",
            "name": "Priyanshu",
            "email": "priyanshu123@gmail.com",
            "role": "0"
        },
        {
            "id": "6",
            "name": "Vasu",
            "email": "vasu123@gmail.com",
            "role": "0"
        },
        {
            "id": "5",
            "name": "Kashyap",
            "email": "kashyap123@gmail.com",
            "role": "0"
        },
        {
            "id": "4",
            "name": "Kane",
            "email": "kane123@gmail.com",
            "role": "0"
        },
        {
            "id": "3",
            "name": "Rahul",
            "email": "rahul123@gmail.com",
            "role": "0"
        },
*/