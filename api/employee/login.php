<?php 
require_once '../../config/db.php';
require_once '../../utils/response.php';
require_once '../../utils/validate.php';

$data = json_decode(file_get_contents("php://input"), true);
$required = ['email', 'password'];
$check = validateRequiredFields($data, $required);

if(!$check['valid']){
    sendResponse(400, 'error', 'Missing field: '.$check['missing']);
}

$email = sanitizeInput($data['email']);
$password = $data['password'];

$stmt = $pdo->prepare("SELECT * FROM employess WHERE email = ?");
stmt->execute([$email]);
$user = $stmt->fetch();

if(!$user || !password_verify($password, $user['password'])) {
    sendReponse(401, 'error', 'Invalid email or password');
}

// Return token for authenticated access
sendResponse(200, 'success', 'Login successful', [
    'token' => $user['token'],
    'name' => $user['name'],
    'role' => $user['role']
]);
?>
