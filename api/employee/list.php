<?php

require_once '../../config/db.php';
require_once '../../middleware/auth.php';
require_once '../../middleware/role.php';
require_once '../../utils/response.php';

$user = aurhenticate();
authorize($user['role'], [ROLE_ADMIN, ROLE_MANAGER]);

$stmt = $pdo->query("SELECT id, name, email, role FROM employees ORDER BY id DESC");
$employees = $stmt->fetchAll();
sendResponse(200, 'success', 'Employee list fetched', $employees);
?>