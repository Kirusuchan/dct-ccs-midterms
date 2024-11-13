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

// Validates student data by checking if 'student_id', 'first_name', and 'last_name' are not empty.

function validateStudentData($student_data) {
    $errors = [];
    if (empty($student_data['student_id'])) {
        $errors[] = "Student ID is required";
    }
    if (empty($student_data['first_name'])) {
        $errors[] = "First Name is required";
    }
    if (empty($student_data['last_name'])) {
        $errors[] = "Last Name is required";
    }
    return $errors;
}

// Checks if a student with the same ID already exists in the session data.

function checkDuplicateStudentData($student_data) {
    foreach ($_SESSION['students'] as $student) {
        if ($student['student_id'] === $student_data['student_id']) {
            return "Duplicate Student ID";
        }
    }
    return "";
}

// Finds and returns the index of a student in the $_SESSION['student_data'] array based on the provided $student_id.

function getSelectedStudentIndex($student_id) {
    foreach ($_SESSION['students'] as $index => $student) {
        if ($student['student_id'] === $student_id) {
            return $index;
        }
    }
    return null;
}

// Retrieves a student's data from the session variable student_data based on the provided index.

function getSelectedStudentData($index) {
    return $_SESSION['students'][$index] ?? null;
}

// Checks if at least one subject is selected in the provided $subject_data.

function validateAttachedSubject($subject_data) {
    if (empty($subject_data)) {
        return ["At least one subject should be selected"];
    }
    return [];
}

// Finds the index of a subject in the $_SESSION['subject_data'] array based on its $subject_code

function getSelectedSubjectIndex($subject_code) {
    foreach ($_SESSION['subjects'] as $index => $subject) {
        if ($subject['subject_code'] === $subject_code) {
            return $index;
        }
    }
    return null;
}

// Retrieves subject data from the session variable $_SESSION['subject_data'] based on the provided index.

function getSelectedSubjectData($index) {
    return $_SESSION['subjects'][$index] ?? null;
}

function getBaseURL() {
    // Check for HTTPS (if the connection is secure)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    
    // Get the server name (e.g., localhost, or actual domain)
    $serverName = $_SERVER['SERVER_NAME'];
    
    // Check if there's a custom port and include it
    $port = $_SERVER['SERVER_PORT'] != 80 ? ':' . $_SERVER['SERVER_PORT'] : '';  // Add the port if it's not 80
    
    // Get the base directory of the script
    $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';
    
    // Construct and return the full base URL
    return $protocol . $serverName . $port . $baseDir;
}
?>