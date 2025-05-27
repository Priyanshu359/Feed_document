<?php
require_once '../../config/db.php';
require_once '../../middleware/auth.php';
require_once '../../middleware/role.php';
require_once '../../utils/response.php';
require_once '../../utils/validate.php';
require_once '../../utils/logger.php';

$user = authenticate();
authorize($user['role'], [ROLE_MANAGER]);
$data = json_decode(file_get_contents('php://input'), true);

$required = ['title', 'description'];
$check = validateRequiredFields($data, $required);
if (!$check['valid']) {
    sendResponse(400, 'error', 'Missing field: ' . $check['missing']);
}

$title = sanitizeInput($data['title']);
$description = sanitizeInput($data['description']);

$stmt = $conn->prepare("INSERT INTO projects (title, description, created_by) VALUES (?,?,?)");
$stmt->bind_param("ssi", $title, $description, $user['id']); 
$success = $stmt->execute();

if($success) {
    logAdminAction("Project '$title' created", $user['id']);
    sendResponse(201, 'success', 'Project created');
}
else {
    sendResponse(500, 'error', 'Failed to create project');
}
?>
