<?php
require_once '../../config/db.php';
require_once '../../middleware/auth.php';
require_once '../../utils/response.php';
require_once '../../utils/validate.php';

$user = authenticate();

$data = json_decode(file_get_contents("php://input"), true);
$required = ['project_id', 'title', 'description', 'due_date'];
$check = validateRequiredFields($data, $required);
if (!$check['valid']) {
    sendResponse(400, 'error', 'Missing field: ' . $check['missing']);
    exit;
}

$title = sanitizeInput($data['title']);
$desc = sanitizeInput($data['description']);
$projectId = intval($data['project_id']);
$due = sanitizeInput($data['due_date']);

$stmt = $conn->prepare("INSERT INTO tasks (project_id, title, description, created_by, due_date) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    sendResponse(500, 'error', 'Prepare failed: ' . $conn->error);
    exit;
}

// Bind parameters: i = int, s = string
$stmt->bind_param("issis", $projectId, $title, $desc, $user['id'], $due);

$success = $stmt->execute();

if ($success) {
    sendResponse(201, 'success', 'Task created successfully');
} else {
    sendResponse(500, 'error', 'Failed to create task: ' . $stmt->error);
}
?>
