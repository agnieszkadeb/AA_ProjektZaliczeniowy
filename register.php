<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Business Casual - Registration</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
        <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
    
<script type="text/javascript"> 
 $(document).ready(function () {
    $("#register").click(function () {
        // Get input values
        var fname = $("#fname").val();
        var lname = $("#lname").val();
        var email = $("#email").val();  // Corrected this selector
        var password = $("#password").val();
        var confirmPassword = $("#confirmPassword").val();

        // Make an AJAX request
        $.ajax({
            type: "POST",
            url: "adduser.php",
            data: {
                name: fname,
                surname: lname,
                email: email,
                password: password,
                confirmPassword: confirmPassword
            },
            success: function (response) {
                if (response === 'true') {
                    // If account creation was successful
                    $("#add_err2").html('<div class="alert alert-success">\
                        <strong>Account</strong> processed.\
                    </div>');
                    window.location.href = "index.php";
                } else if (response === 'false') {
                    // If email is already in the system
                    $("#add_err2").html('<div class="alert alert-danger">\
                        <strong>Email Address</strong> already in system.\
                    </div>');
                } else if (response === 'fname') {
                    // If the first name is missing
                    $("#add_err2").html('<div class="alert alert-danger">\
                        <strong>First Name</strong> is required.\
                    </div>');
                } else if (response === 'lname') {
                    // If the last name is missing
                    $("#add_err2").html('<div class="alert alert-danger">\
                        <strong>Last Name</strong> is required.\
                    </div>');
                }
            },
            error: function () {
                // Handle any errors during the AJAX request
                $("#add_err2").html('<div class="alert alert-danger">\
                    <strong>Error!</strong> Something went wrong.\
                </div>');
            }
        });
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
                            <span class="section-heading-upper">Join Our Community</span>
                            <span class="section-heading-lower">Register Now</span>
                        </h2>

                        <!-- Registration Form -->
                        <form action="register.php" method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="name" name="name" maxlength="25" placeholder="Enter your name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="surname" class="form-label">Last Name</label>
                                <input type="surname" class="form-control" id="surname" name="surname" maxlength="25" placeholder="Enter your surname" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" maxlength="35" placeholder="name@example.com" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" maxlength="250" placeholder="Password" required>
                            </div>

                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" maxlength="250" placeholder="Confirm your password" required>
                            </div>

                            <div class="mb-3 text-center">
                                <button id="register" type="submit" class="btn btn-primary btn-xl">Register</button>
                            </div>
                        </form>
                        <!-- End of Registration Form -->

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
