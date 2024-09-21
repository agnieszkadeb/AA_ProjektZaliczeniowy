<?php 
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany i ma uprawnienia administratora
if(isset($_SESSION['login']) && $_SESSION['role'] == '1'){
    $name = htmlspecialchars($_SESSION['name']);
    $surname = htmlspecialchars($_SESSION['surname']);
    $role = $_SESSION['role'];
} else {
    header("Location: login.php");
    exit();
}

// Połączenie z bazą danych
require_once realpath(__DIR__ . "/../vendor/autoload.php");
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$hostname = $_ENV["DATABASE_HOST"];
$dbUser = $_ENV["DATABASE_USER"];
$dbPass = $_ENV["DATABASE_PASS"];
$databasename = $_ENV["DATABASE_NAME"];
$userrole1 = $_ENV["USER_ROLE1"];
$userrole2 = $_ENV["USER_ROLE2"];

// Połączenie z bazą danych
$mysqli = new mysqli($hostname, $dbUser, $dbPass, $databasename);

// Sprawdzenie połączenia
if ($mysqli->connect_error) {
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Pobranie postów z bazy danych, posortowanych od najnowszych
$query = "SELECT post_id, title, subtitle, post_msg, date FROM posts ORDER BY date DESC";
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
    <!-- jQuery -->
    <script src="../js/jquery.js"></script>
</head>
<body>
    <?php 
    if($role == 2){
        include 'header.php';   
    } else{
        include 'headeradmin.php';    
    }
    ?>
    
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-dark py-lg-4" id="mainNav">
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
            <div class="d-flex justify-content-end mb-3">
                <a href="createpost.php" class="btn btn-primary btn-xl">Add Post</a>
            </div>
            
            <!-- Responsywna Tabela -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Subtitle</th>
                            <th>Post Message</th>
                            <th>Date</th>
                            <th>Delete</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['post_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['subtitle']) . "</td>";
                                
                                // Skrócenie wiadomości dla lepszej czytelności w tabeli
                                $short_message = strlen($row['post_msg']) > 50 ? substr($row['post_msg'], 0, 50) . '...' : $row['post_msg'];
                                echo "<td>" . htmlspecialchars($short_message) . "</td>";
                                
                                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                                
                                // Przyciski "Delete" i "Edit"
                                echo "<td><a class='btn btn-danger btn-sm' href='deletepost.php?delete_post_id=" . urlencode($row['post_id']) . "' onclick=\"return confirm('Are you sure you want to delete this post?');\">Delete</a></td>";
                                echo "<td><a class='btn btn-primary btn-sm' href='editpost.php?edit_post_id=" . urlencode($row['post_id']) . "'>Edit</a></td>";
                                
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>No posts found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- Koniec Responsywnej Tabeli -->
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="../js/scripts.js"></script>
</body>
</html>
