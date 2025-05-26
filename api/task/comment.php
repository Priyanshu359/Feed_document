<?php
require_once '../../config/db.php';
require_once '../../middleware/auth.php';
require_once '../../utils/response.php';

$user = authenticate();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['task_id'];
    $comment = $_POST['comment'];
    $mention = $_POST['mention'] ?? null;
    $parentId = $_POST['parent_id'] ?? null;
    $filePath = null;

    // Handle upload
    if (!empty($_FILES['file']['name'])) {
        $filename = time() . '_' . basename($_FILES['file']['name']);
        $dest = UPLOAD_DIR . $filename;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
            $filePath = 'uploads/tasks/' . $filename;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO task_comments (task_id, user_id, comment, mention, parent_id, file_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$taskId, $user['id'], $comment, $mention, $parentId, $filePath]);

    sendResponse(201, 'success', 'Comment added');
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $taskId = $_GET['task_id'] ?? 0;
    $stmt = $pdo->prepare("SELECT * FROM task_comments WHERE task_id = ? ORDER BY created_at ASC");
    $stmt->execute([$taskId]);
    $comments = $stmt->fetchAll();
    sendResponse(200, 'success', 'Comments fetched', $comments);
}
?>
