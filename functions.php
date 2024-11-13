<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// User Authentication 

function getUsers() {
    return [
        ['email' => 'user1@email.com', 'password' => 'password'],
        ['email' => 'user2@email.com', 'password' => 'password'],
        ['email' => 'user3@email.com', 'password' => 'password'],
        ['email' => 'user4@email.com', 'password' => 'password'],
        ['email' => 'user5@email.com', 'password' => 'password'],
    ];
}

// Checks if a user's session is active by verifying the existence of an email address in the session

function checkUserSessionIsActive() {
    if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
        header("Location: dashboard.php");
        exit;
    }
}

// Validates login credentials by checking if the provided email and password are valid.

function validateLoginCredentials($email, $password) {
    $errors = [];
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    return $errors;
}

// Checks if a given email and password match any user in the provided list of users.

function checkLoginCredentials($email, $password, $users) {
    foreach ($users as $user) {
        if ($user['email'] === $email && $user['password'] === $password) {
            return true;
        }
    }
    return false;
}

// Takes an array of error messages ($errors) and returns a formatted HTML string displaying the errors in an unordered list, preceded by a bold heading "System Errors".

function displayErrors($errors) {
    if (empty($errors)) return '';
    $html = '<div class="alert alert-danger"><strong>System Errors</strong><ul>';
    foreach ($errors as $error) {
        $html .= '<li>' . htmlspecialchars($error) . '</li>';
    }
    $html .= '</ul></div>';
    return $html;
}

// Takes an error message as input and returns a formatted HTML alert box containing the error message.

function renderErrorsToView($error) {
    if (empty($error)) return '';
    return '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
            . htmlspecialchars($error)
            . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}

//Checks if a user is logged in by verifying the existence and non-emptiness of the email key in the $_SESSION superglobal array.

function guard() {
    if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
        header("Location: index.php");
        exit;
    }
}

// Validates subject data by checking if 'subject_code' and 'subject_name' are not empty.

function validateSubjectData($subject_data) {
    $errors = [];
    
    // Check if the subject code is empty
    if (empty($subject_data['subject_code'])) {
        $errors[] = "Subject Code is required";
    }
    
    // Check if the subject name is empty
    if (empty($subject_data['subject_name'])) {
        $errors[] = "Subject Name is required";
    }
    
    return $errors;
}

// Checks if a subject already exists in the session data by comparing its code or name with existing subjects.

function checkDuplicateSubjectData($subject_data) {
    // Assuming subjects are stored in session
    foreach ($_SESSION['subjects'] as $subject) {
        // Check if the subject code or name already exists
        if ($subject['subject_code'] === $subject_data['subject_code'] || $subject['subject_name'] === $subject_data['subject_name']) {
            return "Duplicate Subject: " . $subject_data['subject_code'] . " or " . $subject_data['subject_name'] . " already exists.";
        }
    }
    
    return false;  // No duplicates found
}
?>