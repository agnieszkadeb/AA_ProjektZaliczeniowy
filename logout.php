<?php
session_start();
unset($_SESSION['login']); 
unset($_SESSION['name']);
unset($_SESSION['surname']);
//session_destroy();

// Redirect to index.php with the logout=true parameter
header("Location: index.php?logout=true");
exit();
?>