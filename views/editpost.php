<?php 
session_start();

if (isset($_SESSION['login'])) {
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
$user = $_ENV["DATABASE_USER"];
$pass = $_ENV["DATABASE_PASS"];
$databasename = $_ENV["DATABASE_NAME"];
$mysqli = new mysqli($hostname, $user, $pass, $databasename);

// Sprawdzenie połączenia
if ($mysqli->connect_error) {
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Sprawdzenie, czy `id` jest przekazane w GET
if (isset($_GET['edit_post_id'])) {
    $post_id = intval($_GET['edit_post_id']);

    // Przygotowanie zapytania
    $query = "SELECT title, subtitle, post_msg, date FROM posts WHERE post_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $post_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $title = htmlspecialchars($row['title']);
            $subtitle = htmlspecialchars($row['subtitle']);
            $message = nl2br(htmlspecialchars($row['post_msg']));
            $date = htmlspecialchars($row['date']);
        } else {
            header("Location: error404.php");
            exit();
        }
    } else {
        header("Location: error404.php");
        exit();
    }

    $stmt->close();
} else {
    header("Location: error404.php");
    exit();
}

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="<?php echo $subtitle; ?>" />
    <meta name="author" content="" />
    <title><?php echo $title; ?> - CoffeeBlog</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="../js/jquery.js"></script>
    <script src="../js/scripts.js"></script>
    <script>
        $(document).ready(function() {
            $('#postForm').on('submit', function(event) {
                event.preventDefault(); // Prevent the form from submitting normally
                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    type: 'POST',
                    url: 'postchange.php', 
                    data: formData,
                                    success: function (response) {
                    // Trim the response to avoid unexpected whitespace
                    response = response.trim();
                    
                    if (response === 'true') {
                        // If post creation was successful
                        $("#error_msg").html('<div class="alert alert-success"><strong>Post</strong> created successfully.</div>');
                        header("Location: postslist.php"); // Redirect after success
                    } else if (response === 'title') {
                        // If title is missing
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Title</strong> is required.</div>');
                    } else if (response === 'subtitle') {
                        // If subtitle is missing
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Subtitle</strong> is required.</div>');
                    } else if (response === 'message') {
                        // If message is missing
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Message</strong> is required.</div>');
                    } else if (response === 'tshort') {
                        // If the email field is too short
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Title</strong> is too short.</div>');
                    } else if (response === 'sshort') {
                        // If the email format is invalid
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Subtitle</strong> format is not valid.</div>');
                    } else if (response === 'mshort') { 
                        // If the password is too short
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Message</strong> must be at least 10 characters.</div>');
                        
                        } else {
                        // Generic error message
                            $("#error_msg").html('<div class="alert alert-danger"><strong>Error!</strong> Processing request. Please try again.</div>');
                    }
                },
                    error: function() {
                        $('#error_msg').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                    }
                });
            });
        });
    </script>
</head>
<body>
    <?php 
        if ($role == 2) {
            include 'header.php';   
        } else {
            include 'headeradmin.php';    
        }
    ?>

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
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="bg-faded p-5 rounded">
                        <h2 class="section-heading text-center mb-4">
                            <span class="section-heading-upper">Edit Your Post</span>
                            <span class="section-heading-lower">Update Your Post</span>
                        </h2>
                        <div id="error_msg" class="mb-3"></div>
                        <!-- Post Edit Form -->
                        <form id="postForm" role="form" method="post">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" maxlength="100" value="<?php echo $title; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="subtitle" class="form-label">Subtitle</label>
                                <input type="text" class="form-control" id="subtitle" name="subtitle" maxlength="100" value="<?php echo $subtitle; ?>">
                            </div>

                            <p><strong>Date:</strong> <?php echo $date; ?></p>
                            <div class="mb-3">
                                <label for="message" class="form-label">Post Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" maxlength="1000"><?php echo $message; ?></textarea>
                            </div>
                            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                            <div class="mb-3 text-center">
                                <button id="postForm" type="submit" class="btn btn-primary btn-xl">Publish</button>
                            </div>
                        </form>
                        <!-- End of Post Edit Form -->

                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
