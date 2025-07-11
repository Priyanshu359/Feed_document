<?php
require_once '../../config/db.php';
require_once '../../middleware/auth.php';
require_once '../../middleware/role.php';
require_once '../../utils/response.php';
require_once '../../utils/validate.php';
require_once '../../utils/logger.php';

$user = authenticate();
authorize($user['role'], [ROLE_MANAGER]);

$data = json_decode(file_get_contents("php://input"), true);
$required = ['project_id', 'title', 'description'];
$check = validateRequiredFields($data, $required);
if (!$check['valid']) {
    sendResponse(400, 'error', 'Missing field: ' . $check['missing']);
}

$projectId = intval($data['project_id']);
$title = sanitizeInput($data['title']);
$description = sanitizeInput($data['description']);

$stmt = $conn->prepare("UPDATE projects SET title = ?, description = ? WHERE id = ?");
$stmt->bind_param("ssi", $title, $description, $projectId);
$success = $stmt->execute();

if ($success) {
    logAdminAction("Project ID $projectId updated", $user['name']);
    sendResponse(200, 'success', 'Project updated');
} else {
    sendResponse(500, 'error', 'Failed to update project');
}
?>