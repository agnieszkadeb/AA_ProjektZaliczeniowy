<?php
require_once realpath(__DIR__ . "/../vendor/autoload.php");
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$hostname = $_ENV["DATABASE_HOST"];
$user = $_ENV["DATABASE_USER"];
$pass = $_ENV["DATABASE_PASS"];
$databasename = $_ENV["DATABASE_NAME"];
// Connection to the database
$mysqli = new mysqli($hostname, $user, $pass, $databasename);

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
