<?php

require_once '../../config/db.php';
require_once '../../middleware/auth.php';
require_once '../../middleware/role.php';
require_once '../../utils/response.php';

// $user = authenticate();
// authorize($user['role'], [ROLE_ADMIN, ROLE_MANAGER]);

$stmt = $conn->query("SELECT id, name, email, role FROM employees ORDER BY id DESC");
$employees = $stmt->fetch_all(MYSQLI_ASSOC);
sendResponse(200, 'success', 'Employee list fetched', $employees);
?>