<?php
require_once '../../config/db.php';
require_once '../../utils/response.php';
require_once '../../utils/validate.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(405, 'error', 'Method Not Allowed. Use POST.');
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$required = ['email', 'password'];
$check = validateRequiredFields($data, $required);
if (!$check['valid']) {
    sendResponse(400, 'error', 'Missing field: ' . $check['missing']);
    exit;
}

$email = sanitizeInput($data['email']);
$password = $data['password'];

$stmt = $conn->prepare("SELECT ea.*, e.first_name, e.last_name 
                        FROM employee_auth ea 
                        JOIN employees e ON ea.employee_id = e.id 
                        WHERE ea.email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($password, $user['password'])) {
    sendResponse(401, 'error', 'Invalid email or password');
    exit;
}

sendResponse(200, 'success', 'Login successful', [
    'token' => $user['token'],
    'name' => $user['first_name'] . ' ' . $user['last_name'],
    'role' => $user['role'],
    'employee_id' => $user['employee_id']
]);
