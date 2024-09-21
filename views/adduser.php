<?php 
session_start();

// Włączanie wyświetlania błędów podczas rozwoju (wyłącz na produkcji)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Połączenie z bazą danych
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

// Sprawdzenie połączenia
if ($mysqli->connect_error) {
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Pobranie i zabezpieczenie danych z POST
$name = trim($_POST['name']);
$surname = trim($_POST['surname']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);

// Walidacja
if (strlen($name) < 2) {
    echo 'fname';  // Imię jest za krótkie
    exit();
} 
elseif (strlen($surname) < 2) {
    echo 'lname';  // Nazwisko jest za krótkie
    exit();
} 
elseif (strlen($email) <= 4) {
    echo 'eshort';  // Email jest za krótki
    exit();
} 
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'eformat';  // Niepoprawny format emaila
    exit();
} 
elseif (strlen($password) <= 4) {
    echo 'pshort';  // Hasło jest za krótkie
    exit();
} 
else {
    // Szyfrowanie hasła za pomocą bcrypt z kosztem 10
    $spassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);

    // Sprawdzenie, czy email już istnieje w bazie danych
    $stmt = $mysqli->prepare("SELECT * FROM members WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_row = $result->num_rows;
    $stmt->close();

    if ($num_row >= 1) {
        echo 'false'; // Email już istnieje
        exit();
    }

    // Wstawienie nowego członka do bazy danych
    $stmt = $mysqli->prepare("INSERT INTO members (name, surname, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $surname, $email, $spassword);

    // Sprawdzenie, czy wstawienie się powiodło
    if ($stmt->execute()) {
        // Ustawienie zmiennych sesyjnych dla nowego użytkownika
        $_SESSION['login'] = $mysqli->insert_id;  // Zapisanie ID użytkownika w sesji
        $_SESSION['name'] = $name;                // Zapisanie imienia w sesji
        $_SESSION['surname'] = $surname;          // Zapisanie nazwiska w sesji
        $_SESSION['role'] = 2;  
        echo 'true';                              // Sukces
    } else {
        // Jeśli wstawienie się nie powiodło, zwróć 'error'
        echo 'error';
    }

    $stmt->close();
    exit();
}
?>
