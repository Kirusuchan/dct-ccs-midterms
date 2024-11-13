<?php
// Start the session if it hasn't been started
session_start();

// Destroy the session to log out the user
session_destroy();

// Redirect to the login page
header("Location: index.php");
exit;
