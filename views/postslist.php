<?php 
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany i ma uprawnienia administratora
if(isset($_SESSION['login']) && $_SESSION['role'] == '1'){
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];
    $role = $_SESSION['role'];
} else {
    header("Location: login.php");
    exit();
}
//połącznie z bazą
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

// Pobranie użytkowników z bazy danych
$query = "SELECT post_id, title, subtitle, post_msg, date FROM posts";
$result = $mysqli->query($query);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Admin Dashboard</title>
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="../js/jquery.js"></script>
    </head>
    <body>
        <?php 
        if($role == 2){
          include 'header.php';   
        }else{
           include 'headeradmin.php';    
        }
        ?>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark py-lg-4 " id="mainNav">
            <div class="container">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item px-lg-4 nav-link text-uppercase">
                            Welcome <?php echo $name . " " . $surname; ?>
                        </li> 
                        <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <section class="page-section about-heading">
            <div class="container bg-faded rounded p-5">
                <h2 class="text-center">Admin Dashboard</h2>
                <a class="nav-link text-uppercase" href="createpost.php"><button id="publish" type="submit" class="btn btn-primary btn-xl">Add Post</button></a>
                
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Subtitle</th>
                            <th>Post Message</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['post_id'] . "</td>";
                                echo "<td>" . $row['title'] . "</td>";
                                echo "<td>" . $row['subtitle'] . "</td>";
                                echo "<td>" . $row['post_msg'] . "</td>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td><a class='btn btn-primary btn-xl' href='deletepost.php?delete_post_id=" . $row['post_id'] . "'>Delete</a></td>";
                                
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No users found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <?php include 'footer.php'; ?>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="../js/scripts.js"></script>
    </body>
</html>
