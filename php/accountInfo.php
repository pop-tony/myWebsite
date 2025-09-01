<?php
    include ("dbConnect.php");
    session_start();
    
    if(!($_SESSION['logged_in'])) {
        header("location:login.php");
        exit();
    }

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(isset($_GET["logout"])){
            session_unset();
            session_destroy();
            header("location:../index.html");
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Info</title>
    <link rel="stylesheet" href="..\styles\show-style1.css" />
    <link rel="stylesheet" href="..\styles\style.css"/>
</head>
<body>
    
    <?php
        include ("header.php");
    ?>

    <div class="acc-info-container">
        <div class="list">
            <a href="updateInfo.php">Update Info</a>
        </div>
    </div>

    <script src="..\js\header.js"></script>
    <script src="..\js\show-js1.js"></script>
</body>
</html>