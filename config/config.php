<?php

define('BASE_URL', 'http://localhost/employee-api');

define('UPLOAD_DIR', __DIR__.'/../uploads/tasks/');
define('MAX_FILE_SIZE', 5*1024*1024); //5MB

define('ADMIN_LOG_FILE', __DIR__.'/../logs/admin_actions.log');

define('AUTH_HEADER', 'Authorization');

date_default_timezone_set('Asia/Kolkata');

?>