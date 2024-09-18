<!DOCTYPE html> 
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <title>Business Casual - Logowanie</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <!-- jQuery  -->
    <script src="js/jquery.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
    
    <!-- Skrypt AJAX do Logowania -->
<script type="text/javascript">
$(document).ready(function() {
    // Upewnij się, że używasz poprawnego selektora ID dla przycisku logowania
    $("#login").click(function(e) {
        e.preventDefault(); // Zapobiega domyślnemu wysłaniu formularza

        // Pobranie wartości z pól formularza
        var email = $("#email").val();
        var password = $("#password").val();

        // Prosta walidacja front-end
        if(email === "" || password === "") {
            $("#error_msg").html('<div class="alert alert-danger"><strong>Wszystkie pola są wymagane.</strong></div>');
            return;
        }

        // Przygotowanie danych do wysłania
        var postData = {
            email: email,
            password: password
        };

        $.ajax({
            type: "POST",
            url: "pcheck.php",
            data: postData,
            dataType: "text", // Zakładając, że pcheck.php zwraca tekst ('true' lub 'false')
            success: function (response) {
                var trimmedResponse = response.trim(); // Usunięcie białych znaków

                if(trimmedResponse === 'true') {
                    $("#error_msg").html('<div class="alert alert-success"><strong>Authenticated</strong></div>');
                    window.location.href = "blog.php"; // Przekierowanie po sukcesie
                } 
                else if(trimmedResponse === 'false') {
                    $("#error_msg").html('<div class="alert alert-danger"><strong>Nieprawidłowy email lub hasło.</strong></div>');
                } 
                else {
                    // Obsługa innych odpowiedzi
                    $("#error_msg").html('<div class="alert alert-danger"><strong>Authentication</strong> failure.</div>');
                    console.error('Nieoczekiwana odpowiedź z serwera:', response);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Obsługa błędów AJAX
                $("#error_msg").html('<div class="alert alert-danger"><strong>Error</strong> processing request. Please try again.</div>');
                console.error('AJAX Error:', textStatus, errorThrown);
            },
            beforeSend: function () {
                // Pokazanie komunikatu ładowania
                $("#error_msg").html('<div class="alert alert-info"><strong>Loading...</strong></div>');
            }
        });

        return false;
    });
});
</script>


</head>
<body>
    <?php include 'header.php'; ?>

    <section class="page-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="bg-faded p-5 rounded">
                        <h2 class="section-heading text-center mb-4">
                            <span class="section-heading-upper">Welcome Back</span>
                            <span class="section-heading-lower">LogIn</span>
                        </h2>
                        <div id="error_msg" class="mb-3">
                            <!-- Tutaj będą wyświetlane komunikaty błędów lub sukcesu -->
                        </div>
                            <div class="mb-3 text-center">
                                <strong>You must be logged in to view the blog!</strong>
                           
                            </div>
                        <!-- Formularz Logowania -->
                        <form id="loginForm" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email adress</label>
                                <input type="email" class="form-control" id="email" name="email" maxlength="35" placeholder="name@example.com" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" maxlength="250" placeholder="password" required>
                            </div>

                            <div class="mb-3 text-center">
                                <button id="login" type="submit" class="btn btn-primary btn-xl">Login</button>
                            </div>
                        </form>
                            <div class="mb-3 text-center">
                                
                                <a href="register.php"><button type="submit" class="btn btn-primary btn-xl">Not a Member? Register here</button></a>
                            </div>
                        <!-- Koniec Formularza Logowania -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
