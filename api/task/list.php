<?php
require_once '../../config/db.php';
require_once '../../middleware/auth.php';
require_once '../../utils/response.php';

$user = authenticate();

$stmt = $pdo->prepare("
    SELECT t.*, p.title as project_title, e.name as assigned_to_name
    FROM tasks t
    LEFT JOIN projects p ON t.project_id = p.id
    LEFT JOIN employees e ON t.assigned_to = e.id
    ORDER BY t.id DESC
");
$stmt->execute();
$tasks = $stmt->fetchAll();

sendResponse(200, 'success', 'Task list', $tasks);
?>
