<?php
session_start();

$email =  $_POST['email'];
$password =  $_POST['password'];

require_once realpath(__DIR__ . "/../vendor/autoload.php");
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$hostname = $_ENV["DATABASE_HOST"];
$user = $_ENV["DATABASE_USER"];
$pass = $_ENV["DATABASE_PASS"];
$databasename = $_ENV["DATABASE_NAME"];
$userrole1 = $_ENV["USER_ROLE1"];
$userrole2 = $_ENV["USER_ROLE2"];
// Connection to the database
$mysqli = new mysqli($hostname, $user, $pass, $databasename);

// Sprawdzenie połączenia
if ($mysqli->connect_error) {
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}


    $query = "SELECT * FROM members WHERE email='$email'";
    $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
    $num_row = mysqli_num_rows($result);
    $row = mysqli_fetch_array($result);

    if ($num_row >=1){
        // Ustawienie zmiennych sesyjnych dla nowego użytkownika
        if(password_verify($password, $row['password'])){
        $_SESSION['login'] = $row['id'];  // Zapisanie ID użytkownika w sesji
        $_SESSION['name'] = $row['name'];                // Zapisanie imienia w sesji
        $_SESSION['surname'] = $row['surname'];          // Zapisanie nazwiska w sesji
        //przypisanie roli 1- admin 2- user
        if($row['role'] == $userrole1 ){
            $_SESSION['role'] = "1";
        }else{
            $_SESSION['role'] = 2;
        }
        
        echo 'true';                              // Sukces
    } else {
        // Jeśli wstawienie się nie powiodło, zwróć 'error'
        echo 'error';
    }
    }else {
        // Jeśli wstawienie się nie powiodło, zwróć 'error'
        echo 'error';
    }

   



?>