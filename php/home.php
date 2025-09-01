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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>myWebsite</title>
    <link rel="stylesheet" href="..\styles\show-style1.css" />
    <link rel="stylesheet" href="..\styles\style.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    
    <?php
        include ("header.php");
    ?>

    <div class="container">
        <div class="chart1">
            <marquee>Hello! Work in Progress</marquee><br><br><br><br><br><br><br>
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            <marquee>Hello! Work in Progress</marquee>
        </div>

        <div class="chart2">
            <marquee>Hello! Work in Progress</marquee><br><br><br><br><br><br><br>
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            <marquee>Hello! Work in Progress</marquee>
        </div>
    </div>
    <script src="..\js\header.js"></script>
    <script src="..\js\show-js1.js"></script>
</body>
</html>