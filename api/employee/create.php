<?php
require_once '../../config/db.php';
require_once '../../utils/response.php';
require_once '../../utils/validate.php';

$user = authenticate();
authorize($user['role'], [ROLE_ADMIN]);
$data = json_decode(file_get_contents("php://input"), true);

// Define required fields
$required = [
    'first_name', 'last_name', 'email', 'phone_number', 'date_of_birth',
    'gender', 'department', 'date_of_joining', 'employment_type',
    'salary', 'current_address', 'permanent_address', 'city',
    'state', 'postal_code', 'country', 'designation'
];

$check = validateRequiredFields($data, $required);
if (!$check['valid']) {
    sendResponse(400, 'error', 'Missing field: ' . $check['missing']);
}

// Sanitize input
foreach ($data as $key => $value) {
    $data[$key] = sanitizeInput($value);
}

$email = $data['email'];

// Check for duplicate email
$checkStmt = $conn->prepare("SELECT id FROM employees WHERE email = ?");
$checkStmt->execute([$email]);
if ($checkStmt->rowCount() > 0) {
    sendResponse(400, 'error', 'Email already exists');
}

// Generate employee code and token
$employee_code = uniqid('EMP');
$token = bin2hex(random_bytes(16));

// Insert into employees
$insertStmt = $conn->prepare("
    INSERT INTO employees (
        employee_code, first_name, last_name, email, phone_number, date_of_birth,
        gender, department, date_of_joining, employment_type, salary,
        current_address, permanent_address, city, state, postal_code,
        country, token
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$success = $insertStmt->execute([
    $employee_code,
    $data['first_name'],
    $data['last_name'],
    $data['email'],
    $data['phone_number'],
    $data['date_of_birth'],
    $data['gender'],
    $data['department'],
    $data['date_of_joining'],
    $data['employment_type'],
    $data['salary'],
    $data['current_address'],
    $data['permanent_address'],
    $data['city'],
    $data['state'],
    $data['postal_code'],
    $data['country'],
    $token
]);

if (!$success) {
    sendResponse(500, 'error', 'Failed to create employee');
}

$employee_id = $conn->lastInsertId();

// Handle designation
$designation = $data['designation'];

// Check if designation exists
$desigStmt = $conn->prepare("SELECT id FROM designations WHERE name = ?");
$desigStmt->execute([$designation]);

if ($desigStmt->rowCount() > 0) {
    $designation_id = $desigStmt->fetchColumn();
} else {
    // Insert new designation
    $addDesig = $conn->prepare("INSERT INTO designations (name) VALUES (?)");
    $addDesig->execute([$designation]);
    $designation_id = $conn->lastInsertId();
}

// Assign to employee_designations
$assignStmt = $conn->prepare("INSERT INTO employee_designations (employee_id, designation_id) VALUES (?, ?)");
$assignStmt->execute([$employee_id, $designation_id]);

// Final response
sendResponse(201, 'success', 'Employee registered successfully', [
    'employee_id' => $employee_id,
    'employee_code' => $employee_code,
    'token' => $token
]);
?>
