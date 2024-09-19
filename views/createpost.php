<?php 
session_start();

if(isset($_SESSION['login'])){
    
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];
    $role = $_SESSION['role'];

}else{
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Create New Post</title>
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
  
    <!-- Your Ajax Script -->
    <script type="text/javascript">
    $(document).ready(function () {
        $("#publish").click(function (event) {
            event.preventDefault(); // Prevent the default form submission

            // Get input values
            var title = $("#title").val().trim();
            var subtitle = $("#subtitle").val();
            var message = $("#message").val();

            // Make an AJAX request
            $.ajax({
                type: "POST",
                url: "addpost.php",
                data: {
                    title: title,
                    subtitle: subtitle,
                    message: message
                },
                success: function (response) {
                    // Trim the response to avoid unexpected whitespace
                    response = response.trim();
                    
                    if (response === 'true') {
                        // If post creation was successful
                        $("#error_msg").html('<div class="alert alert-success"><strong>Post</strong> created successfully.</div>');
                        window.location.href = "index.php"; // Redirect after success
                    } else if (response === 'title') {
                        // If title is missing
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Title</strong> is required.</div>');
                    } else if (response === 'subtitle') {
                        // If subtitle is missing
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Subtitle</strong> is required.</div>');
                    } else if (response === 'message') {
                        // If message is missing
                        $("#error_msg").html('<div class="alert alert-danger"><strong>Message</strong> is required.</div>');
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

    <section class="page-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="bg-faded p-5 rounded">
                        <h2 class="section-heading text-center mb-4">
                            <span class="section-heading-upper">Create a New Post</span>
                            <span class="section-heading-lower">Publish Your Post</span>
                        </h2>
                        <div id="error_msg" class="mb-3"></div>
                        <!-- Post Creation Form -->
                        <form id="postForm" role="form" method="post">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" maxlength="100" placeholder="Enter the title of your post">
                            </div>
                            
                            <div class="mb-3">
                                <label for="subtitle" class="form-label">Subtitle</label>
                                <input type="text" class="form-control" id="subtitle" name="subtitle" maxlength="100" placeholder="Enter the subtitle of your post">
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Post Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" maxlength="1000" placeholder="Enter your message"></textarea>
                            </div>

                            <div class="mb-3 text-center">
                                <button id="publish" type="submit" class="btn btn-primary btn-xl">Publish</button>
                            </div>
                        </form>
                        <!-- End of Post Creation Form -->

                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <!-- Bootstrap core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
