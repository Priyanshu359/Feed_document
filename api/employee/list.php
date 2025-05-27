<?php
require_once '../../config/db.php';
require_once '../../middleware/auth.php';
require_once '../../middleware/role.php';
require_once '../../utils/response.php';

$user = authenticate();
$userId = $user['id'];
$userRole = $user['role'];
$userDepartment = $user['department'];

// For Manager/Team Lead: fetch all subordinates recursively via manager_id
if ($userRole === 'Manager' || $userRole === 'Team Lead') {
    // Recursive CTE to get all employees under this manager/team lead
    $sql = "
        WITH RECURSIVE subordinates AS (
            SELECT id, first_name, last_name, email, department
            FROM employees
            WHERE manager_id = ?
            
            UNION ALL
            
            SELECT e.id, e.first_name, e.last_name, e.email, e.department
            FROM employees e
            INNER JOIN subordinates s ON e.manager_id = s.id
        )
        SELECT s.id, s.first_name, s.last_name, s.email, s.department, ea.role
        FROM subordinates s
        JOIN employee_auth ea ON s.id = ea.employee_id
        ORDER BY s.id DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $employees = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    sendResponse(200, 'success', 'Employees under your hierarchy fetched', $employees);
    exit;

} elseif ($userRole === 'Employee') {
    // Normal employees see employees in the same department only
    $sql = "
        SELECT e.id, e.first_name, e.last_name, e.email, e.department, ea.role
        FROM employees e
        JOIN employee_auth ea ON e.id = ea.employee_id
        WHERE e.department = ?
        ORDER BY e.id DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userDepartment);
    $stmt->execute();
    $employees = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    sendResponse(200, 'success', 'Employees in your department fetched', $employees);
    exit;

} else {
    sendResponse(403, 'error', 'Unauthorized to view employee list');
    exit;
}
?>
