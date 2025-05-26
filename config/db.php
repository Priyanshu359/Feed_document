<?php

$host = 'localhost';
$db = 'employee_management';
$user = 'root';
$password = 'Priyanshu@8077';
$charset = 'utf8mb4';

$dsn = "mysql:host = $host; dbname = $db;charset=$charset";

$options = [
    PDO:: ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO:: ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO:: ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode ([
        'status' => 'error',
        'message' => 'Database connection failed: ',
        'details' => $e->getMessage()
    ]);
    exit;
}
?>