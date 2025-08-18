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

<?php

    $ruser_name_err = $rpassword_err = $remail_err = $rphone_number_err = "";
    $valid = true;
    $how_far_message = "";
    $upd_id = $_SESSION['user_id'];
    $puser_name = $_SESSION['username'];
    $pemail = $_SESSION['email'];
    $ppassword = $_SESSION['password'];
    $pphone_number = $_SESSION['number'];

    // Function to test input
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        // Removed htmlspecialchars for password
        return $data;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['register'])) {
            // Signup validation
            //Username validation
            if(empty($_POST["username"])) {
                $ruser_name_err = "Username is required";
                $valid = false;
            } 
            elseif(!preg_match("/^[a-zA-Z-' ]*$/", $_POST["username"])) {
                $ruser_name_err = "Only letters and white space allowed";
                $valid = false;
            } 
            else {
                $username = test_input($_POST["username"]);

                //Email validation
                if(empty($_POST["email"])) {
                    $remail_err = "Email is required";
                    $valid = false;
                } 
                elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                    $remail_err = "Invalid email format";
                    $valid = false;
                } 
                else {
                    $email = test_input($_POST["email"]);
        
                    
                    if(!isset($_POST["password-option"]) || isset($_POST['password-option']) && $_POST['password-option'] == 'keep'){
                        //Phone-number validation
                        if(empty($_POST["phone-number"])) {
                            $rphone_number_err = "Phone is required";
                            $valid = false;
                        } 
                        elseif(!preg_match("/^\+?\d{1,4}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}$/", $_POST["phone-number"])) {
                            $rphone_number_err = "Invalid number format";
                            $valid = false;
                        }
                        elseif(strlen($_POST["phone-number"]) < 10){
                            $rphone_number_err = "Number should be 10 or more";
                            $valid = false;
                        }
                        else {
                            $phone_number = test_input($_POST["phone-number"]);
                        }
                        
                        // If valid, proceed with signup logic
                        if($valid) {
                            // Prepare and execute SQL statement here
                            try {
                                // Check for existing records
                                $query = "SELECT user_id, email, phone_number FROM users WHERE (email = ? OR phone_number = ?) AND user_id != ?";
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("sss", $email, $phone_number, $upd_id);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if (mysqli_num_rows($result) > 0) {
                                    $row = mysqli_fetch_assoc($result);
                                    if ($row['email'] == $email) {
                                        $how_far_message = "Email Taken!";
                                        $valid = false;
                                    } 
                                    elseif ($row['phone_number'] == $phone_number) {
                                        $how_far_message = "Number Taken!";
                                        $valid = false;
                                    }
                                } 
                                else {
                                    // Update user information
                                    $query = "UPDATE users SET user_name = ?, email = ?, phone_number = ? WHERE user_id = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("ssss", $username, $email, $phone_number, $upd_id);
                                    $stmt->execute();
                                    // Handle successful update
                                    $how_far_message = "Info Updated!";
                                }
                            } 
                            catch (mysqli_sql_exception $e) {
                                // Handle database error
                                $how_far_message = "An error occurred: " . $e->getMessage();
                                $valid = false;
                            }
                        }
                    
                    }
                    elseif(isset($_POST['password-option']) && $_POST['password-option'] == 'change'){
                        echo "<script>
                                window.onload = function() {
                                    document.getElementById('rpasswordl').style.opacity = 1;
                                };
                            </script>";
                        //Password validation
                        if (empty($_POST["password"])) {
                        $rpassword_err = "Password is required";
                        $valid = false;
                        }
                        elseif(strlen($_POST["password"]) < 8){
                            $rpassword_err = "Password should be more than 8 characters";
                            $valid = false;
                        }
                        else {
                            $password = $_POST["password"]; // Don't trim or alter password
                            $hash = password_hash($password, PASSWORD_DEFAULT);
                        }

                        //Phone-number validation
                        if(empty($_POST["phone-number"])) {
                            $rphone_number_err = "Phone is required";
                            $valid = false;
                        } 
                        elseif(!preg_match("/^\+?\d{1,4}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}$/", $_POST["phone-number"])) {
                            $rphone_number_err = "Invalid number format";
                            $valid = false;
                        }
                        elseif(strlen($_POST["phone-number"]) < 10){
                            $rphone_number_err = "Number should be 10 or more";
                            $valid = false;
                        }
                        else {
                            $phone_number = test_input($_POST["phone-number"]);
                        }

                        // If valid, proceed with signup logic
                        if($valid) {
                            // Prepare and execute SQL statement here
                            $sql = "UPDATE users
                                SET user_name = '$username', email = '$email', password = '$hash', phone_number = '$phone_number'
                                WHERE user_id = $upd_id";

                            try {
                                // Check for existing records
                                $query = "SELECT user_id, email, phone_number FROM users WHERE (email = ? OR phone_number = ?) AND user_id != ?";
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("sss", $email, $phone_number, $upd_id);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if (mysqli_num_rows($result) > 0) {
                                    $row = mysqli_fetch_assoc($result);
                                    if ($row['email'] == $email) {
                                        $how_far_message = "Email Taken!";
                                        $valid = false;
                                    } 
                                    elseif ($row['phone_number'] == $phone_number) {
                                        $how_far_message = "Number Taken!";
                                        $valid = false;
                                    }
                                } 
                                else {
                                    // Update user information
                                    $query = "UPDATE users SET user_name = ?, email = ?, phone_number = ? WHERE user_id = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("ssss", $username, $email, $phone_number, $upd_id);
                                    $stmt->execute();
                                    // Handle successful update
                                    $how_far_message = "Info Updated!";
                                    
                                }
                            } 
                            catch (mysqli_sql_exception $e) {
                                // Handle database error
                                $how_far_message = "An error occurred: " . $e->getMessage();
                                $valid = false;
                            }
                        }
                    }
                  
                }
                
            }
    
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Info</title>
    <link rel="stylesheet" href="..\styles\style.css">
    <link rel="stylesheet" href="..\styles\loginstyle.css">
</head>
<body>

    <header>
        <div class="welcomemessage">
            Welcome <?php echo $_SESSION['username'];?>
        </div>
        <div class="navs">
            <nav>
                <a class="navmenu" href="home.php"> Home <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                    <!--!Font Awesome Free 7.0.0 by @fontawesome - https://fontawesome.com License
                     - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                     <path d="M341.8 72.6C329.5 61.2 310.5 61.2 298.3 72.6L74.3 280.6C64.7 289.6 61.5 303.5 66.3
                      315.7C71.1 327.9 82.8 336 96 336L112 336L112 512C112 547.3 140.7 576 176 576L464 576C499.3
                       576 528 547.3 528 512L528 336L544 336C557.2 336 569 327.9 573.8 315.7C578.6 303.5 575.4
                        289.5 565.8 280.6L341.8 72.6zM304 384L336 384C362.5 384 384 405.5 384 432L384 528L256 528L256
                         432C256 405.5 277.5 384 304 384z"/></svg> </a>
                <a class="navmenu" href="peers.php"> Peers <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                    <!--!Font Awesome Free 7.0.0 by @fontawesome - https://fontawesome.com License
                     - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                     <path d="M96 192C96 130.1 146.1 80 208 80C269.9 80 320 130.1 320 192C320 253.9 269.9
                      304 208 304C146.1 304 96 253.9 96 192zM32 528C32 430.8 110.8 352 208 352C305.2 352 384 430.8
                       384 528L384 534C384 557.2 365.2 576 342 576L74 576C50.8 576 32 557.2 
                       32 534L32 528zM464 128C517 128 560 171 560 224C560 277 517 320 464 320C411 320 368 277 368 224C368
                        171 411 128 464 128zM464 368C543.5 368 608 432.5 608 512L608 534.4C608 557.4 589.4 576 566.4
                         576L421.6 576C428.2 563.5 432 549.2 432 534L432 528C432 476.5 414.6 429.1 385.5 391.3C408.1 376.6
                          435.1 368 464 368z"/></svg> </a>
                <a class="navmenu" href="market.php"> Market <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                    <!--!Font Awesome Free 7.0.0 by @fontawesome - https://fontawesome.com License
                     - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                     <path d="M53.5 245.1L110.3 131.4C121.2 109.7 143.3 96 167.6 96L472.5 96C496.7 96 518.9 109.7
                      529.7 131.4L586.5 245.1C590.1 252.3 592 260.2 592 268.3C592 295.6 570.8 318 544 319.9L544
                       512C544 529.7 529.7 544 512 544C494.3 544 480 529.7 480 512L480 320L384 320L384 496C384
                        522.5 362.5 544 336 544L144 544C117.5 544 96 522.5 96 496L96 319.9C69.2 318 48 295.6 48
                         268.3C48 260.3 49.9 252.3 53.5 245.1zM160 320L160 432C160 440.8 167.2 448 176 448L304
                          448C312.8 448 320 440.8 320 432L320 320L160 320z"/></svg> </a>
                <a class="navmenu" href="explore.php"> Explore <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                    <!--!Font Awesome Free 7.0.0 by @fontawesome - https://fontawesome.com License
                     - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path d="M64 320C64 178.6 178.6 64 320 64C461.4 64 576 178.6 576 320C576 461.4
                     461.4 576 320 576C178.6 576 64 461.4 64 320zM544 320C544 196.3 443.7 96 320 96C196.3
                      96 96 196.3 96 320C96 443.7 196.3 544 320 544C443.7 544 544 443.7 544 320zM224.9
                       188.6L311.8 225.7L274.7 312.6L187.8 275.5L224.9 188.6zM334.9 357.7L381.5 451.7L366.9
                        451.7L316.9 351.7L268 451.7L254 451.7L305.1 344.8L282.8 335.4L288.8 321.4L357.4 350.5L351.4
                         364.8L334.9 357.7zM323.1 241.4L391.7 270.8L362.3 339.1L294 310L323.1 241.4zM403.4 284.3L458
                          307.4L434.6 361.7L380.3 338.6L403.4 284.3z"/></svg></a>
            </nav>
        </div>

        <div class="profile_logout">
            <span class="profile">Profile <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                <!--!Font Awesome Free 7.0.0 by @fontawesome - https://fontawesome.com License
                 - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                 <path d="M320 312C386.3 312 440 258.3 440 192C440 125.7 386.3 72 320 72C253.7
                  72 200 125.7 200 192C200 258.3 253.7 312 320 312zM290.3 368C191.8 368 112 447.8
                   112 546.3C112 562.7 125.3 576 141.7 576L498.3 576C514.7 576 528 562.7
                    528 546.3C528 447.8 448.2 368 349.7 368L290.3 368z"/></svg>

                    <div class="options">
                        <p> User Name.........<?php echo $_SESSION['username'];?></p>
                        <p> Email........<?php echo $_SESSION['email'];?></p>
                        <p> Account Type.......<?php echo $_SESSION['account_type'];?></p>
                        <p> Number.....<?php echo $_SESSION['number'];?></p>
                        <p> Rank.....<?php echo $_SESSION['rank'];?></p>
                        <a href="potfolio.php"> Potfolio.....</a>
                        <a href="settings.php"> Settings.....</a>
                        <a href="accountInfo.php"> Account Info.....</a><br>
                    </div>
            </span>

            <span class="logout">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                    <input type="submit" name="logout" id="logoutbtn" value="Log Out">
                </form>
            </span>
        </div>

    </header>

    <div class="container">
        <style>
            form span.error {
                color: rgb(200, 5, 5);
                font-size: 12px;
                font-weight: bold;
                margin-left: 5px;
                transition: opacity 0.3s ease-in-out;
            }

            .error:empty {
                opacity: 0;
            }

            .how-far-message{
                color: rgba(221, 135, 15, 1);
                margin-left: 8rem;
                box-shadow: 0px 0px 10px rgba(221, 135, 15, 1);
                text-shadow: 0px 0px 10px rgba(221, 135, 15, 1);
            }

            #update-info{
                display: block;
                border: 1px solid;
                border-radius: 10px;
                width: fit-content;
                height: fit-content;
                padding: 10px;
                position: relative;
                opacity: 1;
                padding-top: 3rem;
                box-shadow: 0px 0px 10px rgb(0, 0, 0);
            }

            #update-info .password-option{
                margin-left: 4rem;
            }

            #rpasswordl{
                opacity: 0;
            }

        </style>

        <div class="register">
            <form id="update-info" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <span class="how-far-message"> <?php echo $how_far_message; ?></span><br><br>
                <label for="username" >User Name</label>
                <input type="text" name="username" id="rusername" placeholder="Enter Username" value="<?php echo !$valid && isset($_POST['register']) ? $_POST["username"] : $puser_name; ?>">
                <span class="error">* <?php echo $ruser_name_err; ?></span><br><br><br>
                <label for="email" >Email</label>
                <input type="email" name="email" id="remail" placeholder="Enter Email" value="<?php echo !$valid && isset($_POST['register']) ? $_POST["email"] : $pemail; ?>">
                <span class="error">* <?php echo $remail_err; ?></span><br><br><br>
                <label for="phone-number" >Phone Number</label>
                <input type="text" name="phone-number" id="phone-number" placeholder="Enter Phone Number" value="<?php echo !$valid && isset($_POST['register']) ? $_POST["phone-number"] : $pphone_number; ?>">
                <span class="error">* <?php echo $rphone_number_err; ?></span><br><br><br>
                <span id="rpasswordl"><label for="password" >Password</label>
                <input type="text" name="password" id="rpassword" placeholder="Enter New Password">
                <span class="error" id="opass_err"> *<?php echo $rpassword_err; ?></span></span><br>
                <input type="radio" class="password-option" name="password-option" id="change-pass" value="change">
                <label for="change-pass">Change Password</label><br>
                <input type="radio" class="password-option" name="password-option" id="keep-pass" value="keep">
                <label for="keep-pass">Keep Password</label><br><br><br>
                <input class="log-ster-btn" id="registerbtn" type="submit" name="register" value="Update">
            </form>

        </div>
    </div>
    <script>
        document.getElementById('change-pass').addEventListener('click', (e)=>{
            //e.preventDefault();
            document.getElementById('rpasswordl').style.opacity = 1;
            document.getElementById('opass_err').innerHtml = "* <?php echo $rpassword_err; ?>";
        });
        
        document.getElementById('keep-pass').addEventListener('click', (e)=>{
           //e.preventDefault();
           document.getElementById('opass_err').type = "hidden";
           document.getElementById('rpasswordl').style.opacity = 0;
           //location.reload();
        }); 

    </script>
    <script src="..\js\header.js"></script>
</body>
</html>