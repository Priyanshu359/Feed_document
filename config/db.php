<?php

// phpinfo();
// exit;
$host = '127.0.0.1';
$db = 'employee_management-1';
$user = 'root';
$password = '';
$charset = 'utf8mb4';

// Create connection
$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset($charset);

//echo "Connected successfully";
?>
