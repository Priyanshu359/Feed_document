<?php

function sendResponse($statusCode, $status, $message, $data=null) {
    http_response_code($statusCode);
    echo json_encode([
        'status' => $status,   // 'success' or 'error'
        'message' => $message,
        'data' => $data
    ]);
    exit;
};
?>