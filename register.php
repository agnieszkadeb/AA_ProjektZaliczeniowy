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
    <!-- jQuery  -->
    <script src="js/jquery.js"></script>
    
        <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
    
  
    <!-- Your Ajax Script -->
    <script type="text/javascript"> 
    $(document).ready(function () {
        $("#register").click(function (event) {
            event.preventDefault(); // Prevent the default form submission

            // Get input values
            var name = $("#name").val().trim();
            var surname = $("#surname").val();
            var email = $("#email").val().trim();
            var password = $("#password").val();

            // Make an AJAX request
            $.ajax({
                type: "POST",
                url: "adduser.php",
                data: {
                    name: name,
                    surname: surname,
                    email: email,
                    password: password
                },
                success: function (response) {
                    // Trim the response to avoid unexpected whitespace
                    response = response.trim();
                    
                    if (response === 'true') {
                        // If account creation was successful
                        $("#error_msg").html('<div class="alert alert-success"><strong>Account</strong> processed successfully.</div>');
                        window.location.href = "index.php"; // Redirect after success
                    } else if (response === 'false') {
                        // If email is already in the system
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Email Address</strong> already exists.</div>');
                    } else if (response === 'name') {
                        // If the first name is missing
                        $("#error_msg").html('<div class="alert alert-danger"><strong>First Name</strong> is required.</div>');
                    } 
                    // Added your conditions below
                    else if (response === 'surname') {
                        // If the last name is missing
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Last Name</strong> is required.</div>');
                    } else if (response === 'eshort') {
                        // If the email field is too short
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Email Address</strong> is too short.</div>');
                    } else if (response === 'eformat') {
                        // If the email format is invalid
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Email Address</strong> format is not valid.</div>');
                    } else if (response === 'pshort') { 
                        // If the password is too short
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Password</strong> must be at least 5 characters.</div>');
                    } else {
                        // Generic error message
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Error!</strong> Processing request. Please try again.</div>');
                    }
                },
                error: function () {
                    // Handle any errors during the AJAX request
                    $("#error_msg").html('<div class="alert alert-danger"><strong>Error!</strong> Something went wrong.</div>');
                },
                beforeSend: function () {
                    // Show a loading message before the request is processed
                    $("#error_msg").html('<div class="alert alert-info"><strong>Loading...</strong></div>');
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
                            <div id="error_msg" class="mb-3">
                             
                            </div>
                        <!-- Registration Form -->
                        <form id="registrationForm" role="form" method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="name" name="name" maxlength="25" placeholder="Enter your name" >
                            </div>
                            
                            <div class="mb-3">
                                <label for="surname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="surname" name="surname" maxlength="25" placeholder="Enter your surname" >
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" maxlength="35" placeholder="name@example.com" >
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" maxlength="250" placeholder="Password" >
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