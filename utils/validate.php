<?php

// Remove whitespaces, special characters
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

// Check if all required fields exist and are not empty
function validateRequiredFields($data, $fields) {
    foreach($fields as $field) {
        if(!isset($data[$field]) || empty(trim($data[$field]))) {
            return [
                'valid' => false,
                'missing' => $field
            ];
        }
    }
    return ['valid' => true];
}

//Email validation
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Password strength validation
function isStrongPassword($password) {
    return (strlen($password) >=6 && preg_match('/[0-9]/', $password));
}
?>

