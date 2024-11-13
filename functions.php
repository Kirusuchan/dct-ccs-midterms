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

//Checks if a user's session is active by verifying the existence of an email address in the session

function checkUserSessionIsActive() {
    if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
        header("Location: dashboard.php");
        exit;
    }
}

//Validates login credentials by checking if the provided email and password are valid.

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

//Checks if a given email and password match any user in the provided list of users.

function checkLoginCredentials($email, $password, $users) {
    foreach ($users as $user) {
        if ($user['email'] === $email && $user['password'] === $password) {
            return true;
        }
    }
    return false;
}

//Takes an array of error messages ($errors) and returns a formatted HTML string displaying the errors in an unordered list, preceded by a bold heading "System Errors".

function displayErrors($errors) {
    if (empty($errors)) return '';
    $html = '<div class="alert alert-danger"><strong>System Errors</strong><ul>';
    foreach ($errors as $error) {
        $html .= '<li>' . htmlspecialchars($error) . '</li>';
    }
    $html .= '</ul></div>';
    return $html;
}

//Takes an error message as input and returns a formatted HTML alert box containing the error message.

function renderErrorsToView($error) {
    if (empty($error)) return '';
    return '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
            . htmlspecialchars($error)
            . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}
?>