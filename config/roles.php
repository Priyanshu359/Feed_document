<?php 

define('ROLE_ADMIN', 'admin');
define('ROLE_MANAGER', 'manager');
define('ROLE_LEAD', 'lead');
define('ROLE_EMPLOYEE', 'employee');
$roleHierarchy = [
    ROLE_ADMIN => 4,
    ROLE_MANAGER => 3,
    ROLE_LEAD => 2,
    ROLE_EMPLOYEE =>1
];
?>