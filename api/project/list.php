<?php
require_once '../../config/db.php';
require_once '../../middleware/auth.php';
require_once '../../utils/response.php';

$user = authenticate();

$result = $conn->query("SELECT id, title, description, created_by, created_at FROM projects ORDER BY id DESC");

$projects = [];
while ($row = $result->fetch_assoc()) {
    $projects[] = $row;
}

sendResponse(200, 'success', 'Project list', $projects);
?>
