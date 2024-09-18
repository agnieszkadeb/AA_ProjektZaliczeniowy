<?php
session_start();

// Włączanie wyświetlania błędów podczas rozwoju (wyłącz na produkcji)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Połączenie z bazą danych
$mysqli = new mysqli('localhost:3308', 'root', '', 'CoffeeBlog');

// Sprawdzenie połączenia
if ($mysqli->connect_error) {
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Pobranie i zabezpieczenie danych z POST
$name = mysqli_real_escape_string($mysqli, $_POST['name']);
$surname = mysqli_real_escape_string($mysqli, $_POST['surname']); // Poprawiono literówkę 'surmanme' na 'surname'
$email = mysqli_real_escape_string($mysqli, $_POST['email']);
$password = mysqli_real_escape_string($mysqli, $_POST['password']);

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
    $query = "SELECT * FROM members WHERE email='$email'";
    $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
    $num_row = mysqli_num_rows($result);

    if ($num_row >= 1) {
        echo 'false'; // Email już istnieje
        exit();
    }

    // Wstawienie nowego członka do bazy danych
    $insert_row = $mysqli->query("INSERT INTO members (name, surname, email, password) VALUES ('$name', '$surname', '$email', '$spassword')");
    
    // Sprawdzenie, czy wstawienie się powiodło
    if ($insert_row) {
        // Ustawienie zmiennych sesyjnych dla nowego użytkownika
        $_SESSION['login'] = $mysqli->insert_id;  // Zapisanie ID użytkownika w sesji
        $_SESSION['name'] = $name;                // Zapisanie imienia w sesji
        $_SESSION['surname'] = $surname;          // Zapisanie nazwiska w sesji
        echo 'true';                              // Sukces
    } else {
        // Jeśli wstawienie się nie powiodło, zwróć 'error'
        echo 'error';
    }

    exit();
}
?>
