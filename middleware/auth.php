<?php 

require_once __DIR__.'/../config/db.php';
require_once __DIR__.'/../utils/response.php';

function authenticate() {
    if(!isset($_SERVER['HTTP_AUTHORIZATION'])) {
        sendResponse(401, 'error'.'Authorization token missing');
    }

    $token = $_SERVER['HTTP_AUTHORIZATION'];

    global $pdo;
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if(!$user) {
        sendResponse(403, 'error', 'Invalid or expired token');
    }
    return $user;  // Return employee details for use in endpoints
}

?>