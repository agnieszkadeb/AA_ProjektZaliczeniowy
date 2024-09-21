<?php 
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany i ma odpowiednie uprawnienia
if(isset($_SESSION['login']) && $_SESSION['role'] == '1'){
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];
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

// Połączenie z bazą danych
$mysqli = new mysqli($hostname, $dbUser, $dbPass, $databasename);

// Sprawdzenie połączenia
if ($mysqli->connect_error) {
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Inicjalizacja zmiennych
$title = $subtitle = $message = $date = "";
$error = "";

// Sprawdzenie, czy ID postu jest przekazane metodą GET lub POST
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['edit_post_id'])) {
    $post_id = intval($_GET['edit_post_id']);

    // Przygotowanie zapytania do pobrania danych postu
    $query = "SELECT post_id, title, subtitle, post_msg, date FROM posts WHERE post_id = ?";
    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $title = htmlspecialchars($row['title']);
            $subtitle = htmlspecialchars($row['subtitle']);
            $message = htmlspecialchars($row['post_msg']);
            $date = htmlspecialchars($row['date']);
        } else {
            // Jeśli post nie istnieje
            header("Location: error404.php");
            exit();
        }

        $stmt->close();
    } else {
        // Błąd przygotowania zapytania
        header("Location: error404.php");
        exit();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_id'])) {
    // Pobranie i zabezpieczenie danych z POST
    $post_id = intval($_POST['post_id']);
    $title = trim($_POST['title']);
    $subtitle = trim($_POST['subtitle']);
    $message = trim($_POST['message']);

    // Walidacja danych
    if (empty($title)) {
        $error = 'title';  // Tytuł jest wymagany
    } elseif (empty($subtitle)) {
        $error = 'subtitle';  // Podtytuł jest wymagany
    } elseif (empty($message)) {
        $error = 'message';  // Treść posta jest wymagana
    } elseif (strlen($title) <= 4) {
        $error = 'tshort';  // Tytuł jest za krótki
    } elseif (strlen($subtitle) <= 4) {
        $error = 'sshort';  // Podtytuł jest za krótki
    } elseif (strlen($message) < 10) {
        $error = 'mshort';  // Treść posta jest za krótka
    }

    if (empty($error)) {
        // Przygotowanie zapytania do aktualizacji posta
        $update_query = "UPDATE posts SET title = ?, subtitle = ?, post_msg = ? WHERE post_id = ?";
        $stmt = $mysqli->prepare($update_query);
        if ($stmt) {
            $stmt->bind_param("sssi", $title, $subtitle, $message, $post_id);
            if ($stmt->execute()) {
                // Pomyślna aktualizacja, przekierowanie do listy postów
                header("Location: postslist.php");
                exit();
            } else {
                // Błąd podczas aktualizacji
                $error = 'update_error';
            }
            $stmt->close();
        } else {
            // Błąd przygotowania zapytania
            $error = 'update_error';
        }
    }
}

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="<?php echo isset($subtitle) ? $subtitle : ''; ?>" />
    <meta name="author" content="" />
    <title>Edit Post - CoffeeBlog</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap) -->
    <link href="../css/styles.css" rel="stylesheet" />
    <!-- jQuery -->
    <script src="../js/jquery.js"></script>
    <!-- Core theme JS -->
    <script src="../js/scripts.js"></script>
    <!-- Bootstrap core JS (dla modali) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    
</head>
<body>
    <?php 
    if ($role == 2) {
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
                        Welcome <?php echo htmlspecialchars($name . " " . $surname); ?>
                    </li> 
                    <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="page-section">
        <div class="container bg-faded rounded p-5">
            <h2 class="text-center">Edit Post</h2>
            <?php 
            if (!empty($error)) {
                if ($error == 'update_error') {
                    echo '<div class="alert alert-danger">Wystąpił błąd podczas aktualizacji posta. Spróbuj ponownie.</div>';
                } else {
                    // Możesz dodać inne komunikaty błędów tutaj
                    echo '<div class="alert alert-danger">Nieprawidłowe dane. Proszę sprawdzić pola.</div>';
                }
            }
            ?>
            <div id="error_msg" class="mb-3"></div>
            <!-- Post Edit Form -->
            <form id="postForm" role="form" method="post">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" maxlength="100" value="<?php echo isset($title) ? $title : ''; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="subtitle" class="form-label">Subtitle</label>
                    <input type="text" class="form-control" id="subtitle" name="subtitle" maxlength="100" value="<?php echo isset($subtitle) ? $subtitle : ''; ?>" required>
                </div>

                <p><strong>Date:</strong> <?php echo isset($date) ? $date : ''; ?></p>
                <div class="mb-3">
                    <label for="message" class="form-label">Post Message</label>
                    <textarea class="form-control" id="message" name="message" rows="5" maxlength="1000" required><?php echo isset($message) ? $message : ''; ?></textarea>
                </div>
                <input type="hidden" name="post_id" value="<?php echo isset($post_id) ? $post_id : ''; ?>">
                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-primary btn-xl">Update Post</button>
                </div>
            </form>
            <!-- End of Post Edit Form -->

        </div>
    </section>

    <?php include 'footer.php'; ?>
    <!-- Bootstrap core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
