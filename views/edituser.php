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

// Połączenie z bazą danych
require_once realpath(__DIR__ . "/../vendor/autoload.php");
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$hostname = $_ENV["DATABASE_HOST"];
$user = $_ENV["DATABASE_USER"];
$pass = $_ENV["DATABASE_PASS"];
$databasename = $_ENV["DATABASE_NAME"];

// Połączenie z bazą danych
$mysqli = new mysqli($hostname, $user, $pass, $databasename);

// Sprawdzenie połączenia
if ($mysqli->connect_error) {
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Sprawdzenie, czy ID użytkownika zostało przekazane metodą GET
if (isset($_GET['edit_post_id'])) {
    $user_id = intval($_GET['edit_post_id']);

    // Przygotowanie zapytania, aby pobrać dane użytkownika
    $query = "SELECT id, name, surname, email, role FROM members WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Pobranie danych użytkownika
            $user_data = $result->fetch_assoc();
        } else {
            // Jeśli użytkownik nie istnieje, przekieruj na stronę błędu
            header("Location: error404.php");
            exit();
        }
    } else {
        header("Location: error404.php");
        exit();
    }

    $stmt->close();
} else {
    // Jeśli `edit_post_id` nie jest ustawione
    header("Location: error404.php");
    exit();
}

// Aktualizacja danych użytkownika po przesłaniu formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    
    // Walidacja danych
    if (!empty($name) && !empty($surname) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Przygotowanie zapytania aktualizującego dane użytkownika
        $query = "UPDATE members SET name = ?, surname = ?, email = ?, role = ? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ssssi", $name, $surname, $email, $role, $user_id);

        if ($stmt->execute()) {
            // Przekierowanie na stronę z listą użytkowników po sukcesie
            header("Location: adminconsole.php");
            exit();
        } else {
            echo "Błąd podczas aktualizacji danych użytkownika.";
        }

        $stmt->close();
    } else {
        echo "Nieprawidłowe dane. Proszę sprawdzić pola.";
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Edit User</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap) -->
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
    
    <section class="page-section about-heading">
        <div class="container bg-faded rounded p-5">
            <h2 class="text-center">Edit User</h2>
            <form method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="surname" class="form-label">Surname</label>
                    <input type="text" class="form-control" id="surname" name="surname" value="<?php echo htmlspecialchars($user_data['surname']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="admin" <?php echo ($user_data['role'] == "admin") ? 'selected' : ''; ?>>admin</option>
                        <option value="user" <?php echo ($user_data['role'] == "user") ? 'selected' : ''; ?>>user</option>
                    </select>
                </div>

                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-primary btn-xl">Update User</button>
                </div>
            </form>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
