<?php  
session_start();

if(isset($_SESSION['login'])) {
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];
    $role = $_SESSION['role'];
} else {
    header("Location: login.php");
}

// połącznie z bazą
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
$mysqli = new mysqli($hostname, $user, $pass, $databasename);

// Sprawdzenie połączenia
if ($mysqli->connect_error) {
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Ustawienie limitu postów na stronę
$limit = 4;

// Sprawdzenie, która strona jest aktualnie przeglądana
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Obliczenie offsetu
$offset = ($page - 1) * $limit;

// Zapytanie do bazy danych z użyciem limitu i offsetu
$query = "SELECT post_id, title, subtitle, post_msg, date FROM posts ORDER BY date DESC LIMIT $limit OFFSET $offset";
;
$result = $mysqli->query($query);

// Liczenie całkowitej liczby postów w bazie
$total_query = "SELECT COUNT(*) as total FROM posts";
$total_result = $mysqli->query($total_query);
$total_posts = $total_result->fetch_assoc()['total'];

// Obliczenie całkowitej liczby stron
$total_pages = ceil($total_posts / $limit);
?>


<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Business Casual - Start Bootstrap Theme</title>
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
         Google fonts
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i" rel="stylesheet" />
         Core theme CSS (includes Bootstrap)
        <link href="../css/styles.css" rel="stylesheet" />
          jQuery  
    <script src="../js/jquery.js"></script>
</head>
<body>

<?php 
// Includowanie odpowiednich plików nagłówków
if($role == 2) {
    include 'header.php';   
} else {
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
        


<!-- Wyświetlanie postów -->
<?php 
if ($result->num_rows > 0) {
    $imageNumber = 1;
    while($row = $result->fetch_assoc()) {
        $shortMessage = substr($row['post_msg'], 0, 100) . '...';
?>
        <section class="page-section">
            <div class="container">
                <div class="product-item">
                    <div class="product-item-title d-flex">
                        <div class="bg-faded p-5 d-flex ms-auto rounded">
                            <h2 class="section-heading mb-0">
                                <span class="section-heading-upper"><?php echo $row['subtitle']; ?></span>
                                <span class="section-heading-lower"><?php echo $row['title']; ?></span>
                            </h2>
                        </div>
                    </div>
                    <img class="product-item-img mx-auto d-flex rounded img-fluid mb-3 mb-lg-0" 
                         src="../assets/img/products-0<?php echo $imageNumber; ?>.jpg" alt="..." />
                    <div class="product-item-description d-flex me-auto">
                        <div class="bg-faded p-5 rounded">
                            <p class="mb-0"><?php echo $shortMessage; ?></p>
                        </div>
                        <div class="text-center position-relative">
                            <button type="button" class="btn btn-primary btn-xl position-absolute top-100 start-50 translate-middle" 
                                    data-bs-toggle="modal" data-bs-target='#postModal<?php echo $row["post_id"]; ?>'>Read more</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal z pełnym tekstem wiadomości -->
        <div class="modal fade" id='postModal<?php echo $row["post_id"]; ?>' tabindex="-1" aria-labelledby='postModalLabel<?php echo $row["post_id"]; ?>' aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id='postModalLabel<?php echo $row["post_id"]; ?>'><?php echo $row['title']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo $row['post_msg']; ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
<?php 
        $imageNumber = ($imageNumber % 4) + 1;
    }
} else {
    echo "<p>No posts found.</p>";
}
?>

<!-- Nawigacja stronicowania -->
<nav>
    <ul class="pagination justify-content-center">
        <?php if($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
            </li>
        <?php endif; ?>
        
        <!-- Wyświetlanie numerów stron -->
        <?php for($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
        
        <?php if($page < $total_pages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<?php include 'footer.php'; ?>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script src="../js/scripts.js"></script>
</body>
</html>