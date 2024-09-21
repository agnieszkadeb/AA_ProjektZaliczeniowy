<?php
// Start a session if needed (optional)
session_start();

require_once dirname(__FILE__).'/../config.php';

//przekierowanie przeglądarki klienta (redirect)

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>404 - Page Not Found</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .error-container {
            text-align: center;
        }
        h1 {
            font-size: 72px;
            margin: 0;
        }
        h2 {
            font-size: 24px;
            margin: 0;
        }
        p {
            margin-top: 10px;
            font-size: 18px;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>404</h1>
        <h2>Oops! Page not found</h2>
        <p>It looks like the page you're looking for doesn't exist.</p>
        <p><a href="<?phpheader("Location: "._APP_URL."/views/index.php"); ?>">Go back to Home</a></p>
    </div>
</body>
</html>
