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
$dbUser = $_ENV["DATABASE_USER"];
$dbPass = $_ENV["DATABASE_PASS"];
$databasename = $_ENV["DATABASE_NAME"];

// Połączenie z bazą danych
$mysqli = new mysqli($hostname, $dbUser, $dbPass, $databasename);

// Sprawdzenie połączenia
if ($mysqli->connect_error) {
    echo 'error';
    exit();
}

// Sprawdzenie, czy żądanie jest metodą POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pobranie i zabezpieczenie danych z POST
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $subtitle = isset($_POST['subtitle']) ? trim($_POST['subtitle']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Walidacja
    if (empty($title)) {
        echo 'title';  // Tytuł jest wymagany
        exit();
    } 
    elseif (empty($subtitle)) {
        echo 'subtitle';  // Podtytuł jest wymagany
        exit();
    } 
    elseif (empty($message)) {
        echo 'message';  // Treść posta jest wymagana
        exit();
    } 
    elseif (strlen($title) <= 4) {
        echo 'tshort';  // Tytuł jest za krótki
        exit();
    } 
    elseif (strlen($subtitle) <= 4) {
        echo 'sshort';  // Podtytuł jest za krótki
        exit();
    } 
    elseif (strlen($message) < 10) {
        echo 'mshort';  // Treść posta jest za krótka
        exit();
    }
    else {
        // Przygotowanie zapytania z prepared statements
        $stmt = $mysqli->prepare("INSERT INTO posts (title, subtitle, post_msg) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $title, $subtitle, $message);

            if ($stmt->execute()) {
                echo 'true';  // Sukces
            } else {
                echo 'error';  // Błąd wykonania zapytania
            }

            $stmt->close();
        } else {
            echo 'error';  // Błąd przygotowania zapytania
        }
    }
} else {
    echo 'error';  // Nieprawidłowa metoda żądania
}

$mysqli->close();
?>
