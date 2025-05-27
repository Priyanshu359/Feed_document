<?php
require_once '../../config/db.php';
require_once '../../middleware/auth.php';
require_once '../../utils/response.php';

$user = authenticate();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
     var_dump($data,  $_REQUEST);

    $taskId = $_POST['task_id'];
    $comment = $_POST['comment'];
    $mention = $_POST['mention'] ?? null;
    $parentId = $_POST['parent_id'] ?? null;
    $filePath = null;

    //Handle file upload NEEDS TO BE IGNORED FOR NOW
    // if (!empty($_FILES['file']['name'])) {
    //     $filename = time() . '_' . basename($_FILES['file']['name']);
    //     $dest = UPLOAD_DIR . $filename;
    //     if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
    //         $filePath = 'uploads/tasks/' . $filename;
    //     }
    // }

    $stmt = $conn->prepare("INSERT INTO task_comments (task_id, user_id, comment, mention, parent_id, file_path) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        sendResponse(500, 'error', 'Prepare failed: ' . $conn->error);
        exit;
    }
    // Bind parameters — 'iissis': int, int, string, string, int, string (nulls handled as strings)
    $stmt->bind_param(
        "iissss",
        $taskId,
        $user['id'],
        $comment,
        $mention,
        $parentId,
        $filePath
    );

    if ($stmt->execute()) {
        sendResponse(201, 'success', 'Comment added');
    } else {
        sendResponse(500, 'error', 'Failed to add comment: ' . $stmt->error);
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $taskId = $_GET['task_id'] ?? 0;

    $stmt = $conn->prepare("SELECT * FROM task_comments WHERE task_id = ? ORDER BY created_at ASC");
    if ($stmt === false) {
        sendResponse(500, 'error', 'Prepare failed: ' . $conn->error);
        exit;
    }
    $stmt->bind_param("i", $taskId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $comments = $result->fetch_all(MYSQLI_ASSOC);
        sendResponse(200, 'success', 'Comments fetched', $comments);
    } else {
        sendResponse(500, 'error', 'Failed to fetch comments: ' . $stmt->error);
    }
}
?>
