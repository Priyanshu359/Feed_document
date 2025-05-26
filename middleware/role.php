<?php 

require_once __DIR__.'/../utils/response.php';
require_once __DIR__.'/..config/roles.php';

function authorize($userRole, $allowedRoles = []) {
    if(!in_array($userRole, $allowedRoles)) {
        sendResponse(403, 'error', 'Access denied for role: '.$userRole);
    }
}
?>