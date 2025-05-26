<?php

require_once __DIR__.'/../cofig/config.php';

function logAdminAction($action, $performedBy) {
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] By: $performedBy | Action : $action".PHP_EOL;

    file_put_contents(ADMIN_LOG_FILE, $logEntry, FILE_APPEND);
}
?>