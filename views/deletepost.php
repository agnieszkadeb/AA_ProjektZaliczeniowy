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

// Sprawdzamy, czy użytkownik chce usunąć innego użytkownika
if (isset($_GET['delete_post_id'])) {
    $post_id = intval($_GET['delete_post_id']); // Zabezpieczenie ID

    // Tworzymy zapytanie do usunięcia użytkownika
    $delete_query = "DELETE FROM posts WHERE post_id = ?";
    $stmt = $mysqli->prepare($delete_query);
    $stmt->bind_param('i', $post_id);

    if ($stmt->execute()) {
      // Użytkownik został pomyślnie usunięty
        header("Location: postslist.php");

    } else {
        header("Location: error404.php"); // Błąd podczas usuwania użytkownika
    }

    $stmt->close();
    exit();
}

        exit();
    

?>
