<?php
require_once '../../config/db.php';
require_once '../../middleware/auth.php';
require_once '../../utils/response.php';

$user = authenticate();

$stmt = $conn->prepare("
    SELECT t.*, p.title AS project_title, e.name AS assigned_to_name
    FROM tasks t
    LEFT JOIN projects p ON t.project_id = p.id
    LEFT JOIN employees e ON t.assigned_to = e.id
    ORDER BY t.id DESC
");

$stmt->execute();

$result = $stmt->get_result(); // get_result() requires mysqlnd driver

$tasks = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
} 

sendResponse(200, 'success', 'Task list', $tasks);
?>
