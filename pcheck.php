<?php
session_start();

$email =  $_POST['email'];
$password =  $_POST['password'];


// Połączenie z bazą danych
$mysqli = new mysqli('localhost:3308', 'root', '', 'CoffeeBlog');

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