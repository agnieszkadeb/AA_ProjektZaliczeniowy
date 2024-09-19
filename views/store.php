<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Business Casual - Start Bootstrap Theme</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="../css/styles.css" rel="stylesheet" />
        <!-- jQuery  -->
    <script src="../js/jquery.js"></script>
    <script type="text/javascript">
$(document).ready(function() {
    // click send message button to activate
    $("#contact").click(function(e) {
        e.preventDefault(); // Zapobiega domyślnemu wysłaniu formularza

        // Pobranie wartości z pól formularza
        var name = $("#name").val();
        var email = $("#email").val();
        var message = $("#message").val();

        // Prosta walidacja front-end
        if(name === "" ||email === "" || message === "") {
            $("#error_msg").html('<div class="alert alert-danger"><strong>All fields are required!</strong></div>');
            return;
        }

        // Przygotowanie danych do wysłania
        var postData = {
            name: name,
            email: email,
            message: message
            
        };

        $.ajax({
            type: "POST",
            url: "sendmsg.php",
            data: postData,
            dataType: "text", // Zakładając, że pcheck.php zwraca tekst ('true' lub 'false')
            success: function (response) {
                var trimmedResponse = response.trim(); // Usunięcie białych znaków

                if(trimmedResponse === 'true') {
                    $("#error_msg").html('<div class="alert alert-success"><strong>Message Sent!</strong></div>');
                    
                } 
                 else if (response === 'name_long') {
                        // If the last name is missing
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Name</strong> cannot exceed 25 characters.</div>');
                 }else if (response === 'name_short') {
                        // If the last name is missing
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Name</strong> cannot exceed 2 characters.</div>');
                 }else if (response === 'email_long') {
                        // If the last name is missing
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Email</strong> cannot exceed 25 characters.</div>');
                 }else if (response === 'emial_short') {
                        // If the last name is missing
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Email</strong> cannot exceed 2 characters.</div>');
                 }else if (response === 'message_long') {
                        // If the last name is missing
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Name</strong> cannot exceed 500 characters.</div>');
                 }else if (response === 'message_short') {
                        // If the last name is missing
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Name</strong> cannot exceed 2 characters.</div>');
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

    <section class="page-section cta">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 mx-auto">
                    <div class="cta-inner bg-faded text-center rounded">
                        <h2 class="section-heading mb-5">
                            <span class="section-heading-upper">Our future plans</span>
                            <span class="section-heading-lower">Coffie Shop Opening</span>
                        </h2>
                        <ul class="list-unstyled list-hours mb-5 text-left mx-auto">
                            Exciting news! We're working on bringing our passion for coffee to life by planning our very own coffee shop. From handpicked beans to a welcoming atmosphere, we can't wait to create a space where coffee lovers can gather, sip, and savor. Stay tuned as we share updates on this exciting journey!
                        </ul>
                        <p class="address mb-5">
                            <em>
                                <strong>You will find us:</strong><br />
                                1116 Orchard Street
                                <br />
                                Golden Valley, Minnesota
                            </em>
                        </p>
                        <p class="mb-0">
                            <small><em>Call Anytime</em></small>
                            <br />
                            (317) 585-8468
                            <br />
                            <small><em>Contact us</em></small>
                            <br />
                            <a class="nav-link" href="mailto:info@coffeblog.com">info@coffeblog.com</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="page-section contact-heading">
        <div class="container">

            <div class="row">
                <div class="col-xl-9 col-lg-10 mx-auto">
                    <div class="bg-faded rounded p-5">
                        <h2 class="section-heading mb-4 text-center">
                            <span class="section-heading-upper">Get in Touch</span>
                            <span class="section-heading-lower">Contact Us</span>
                        </h2>
                        <div id="error_msg"></div>
                        
                        <form id="contactForm" action="contact_process.php" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" maxlength="25" placeholder="Enter your name"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" maxlength="25" placeholder="Enter your email"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="6" maxlength="500" ></textarea>
                            </div>
                            <div class="form-group text-center">
                                <button id="contact" type="submit" class="btn btn-primary btn-xl ">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>
</html>
