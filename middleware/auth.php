<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../utils/response.php';

function authenticate() {
    if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
        sendResponse(401, 'error', 'Authorization token missing');
        exit; // stop further execution
    }

    $token = $_SERVER['HTTP_AUTHORIZATION'];

    global $conn;

    // Prepare statement to find user by token
    $stmt = $conn->prepare("SELECT * FROM employees WHERE token = ?");
    if (!$stmt) {
        sendResponse(500, 'error', 'Server error: failed to prepare statement');
        exit;
    }

    $stmt->bind_param("s", $token);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        sendResponse(403, 'error', 'Invalid or expired token');
        exit;
    }

    return $user; // Return employee details
}

?>
