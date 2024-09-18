<?php
// Connection to the database
$mysqli = new mysqli('localhost:3308', 'root', '', 'CoffeeBlog');

// Check the connection
if ($mysqli->connect_error) {
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Retrieve and sanitize POST data
$name = mysqli_real_escape_string($mysqli, $_POST['name']);
$email = mysqli_real_escape_string($mysqli, $_POST['email']);
$message = mysqli_real_escape_string($mysqli, $_POST['message']);

// Validation
if (strlen($name) < 2) {
    echo 'name_short';  // Name is too short
    exit();
} 
elseif (strlen($email) < 5) {
    echo 'email_short';  // Email is too short
    exit();
} 
elseif (strlen($message) < 2) {
    echo 'message_short';  // Message is too short
    exit();
} 
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'email_format';  // Invalid email format
    exit();
}

// Insert new contact into the database
$insert_row = $mysqli->query("INSERT INTO contacts (name, email, message) VALUES ('$name', '$email', '$message')");

// Check if insertion was successful
if ($insert_row) {
    echo 'true';  // Success
} else {
    // If insertion failed, return 'error'
    echo 'error';
}

// Close the connection
$mysqli->close();
exit();
?>
