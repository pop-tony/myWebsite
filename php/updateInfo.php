<?php
    include ("dbConnect.php");
    session_start();
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
    <link rel="stylesheet" href="..\styles\loginstyle.css">
</head>
<body>
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
                color: rgba(97, 207, 99, 1);
                margin-left: 8rem;
                box-shadow: 0px 0px 10px rgba(97, 207, 99, 1);
                text-shadow: 0px 0px 10px rgba(97, 207, 99, 1);
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
           location.reload();
        }); 
    </script>
</body>
</html>