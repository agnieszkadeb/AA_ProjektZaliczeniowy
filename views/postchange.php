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
$mysqli = new mysqli($hostname, $user, $pass, $databasename);

// Sprawdzenie połączenia
if ($mysqli->connect_error) {
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Pobranie i zabezpieczenie danych z POST
$post_id = intval($_POST['post_id']); // Zakładam, że post_id jest przesyłane w POST
$title = mysqli_real_escape_string($mysqli, $_POST['title']);
$subtitle = mysqli_real_escape_string($mysqli, $_POST['subtitle']);
$message = mysqli_real_escape_string($mysqli, $_POST['message']);

// Walidacja
if (strlen($title) == "") {
    echo 'title';  // Tytuł jest za krótki
    exit();
} 
elseif (strlen($subtitle) == "") {
    echo 'subtitle';  // Podtytuł jest za krótki
    exit();
} 
elseif (strlen($message)  == "") {
    echo 'message';  // Treść posta jest za krótka
    exit();
} elseif (strlen($title) <= 4) {
    echo 'tshort';  // tytuł jest za krótki
    exit();
} 
elseif (strlen($subtitle) <= 4) {
    echo 'sshort';  // podtytuł jest za krótki
    exit();
} 
elseif (strlen($message) < 10) {
    echo 'mshort';  // post jest za krótki
    exit();
} else {
    // Aktualizacja posta w bazie danych
    $update_row = $mysqli->query("UPDATE posts SET title = '$title', subtitle = '$subtitle', post_msg = '$message' WHERE post_id = $post_id");
    
    // Sprawdzenie, czy aktualizacja się powiodła
    if ($update_row) {
        header("Location: postslist.php");
    } else {
        // Jeśli aktualizacja się nie powiodła, zwróć 'error'
        header("Location: error404.php"); // Błąd
    }
}
exit();
?>
