<?php
require_once '../../config/db.php';
require_once '../../middleware/auth.php';
require_once '../../middleware/role.php';
require_once '../../utils/response.php';
require_once '../../utils/validate.php';

$user = authenticate();
authorize($user['role'], [ROLE_MANAGER, ROLE_LEAD]);

$data = json_decode(file_get_contents("php://input"), true);
$required = ['task_id', 'employee_id'];
$check = validateRequiredFields($data, $required);
if (!$check['valid']) {
    sendResponse(400, 'error', 'Missing field: ' . $check['missing']);
}

$taskId = intval($data['task_id']);
$empId = intval($data['employee_id']);

$stmt = $pdo->prepare("UPDATE tasks SET assigned_to = ? WHERE id = ?");
$success = $stmt->execute([$empId, $taskId]);

if ($success) {
    sendResponse(200, 'success', 'Task assigned');
} else {
    sendResponse(500, 'error', 'Assignment failed');
}
?>
