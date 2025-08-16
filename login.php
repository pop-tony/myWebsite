<?php
    include ("php\dbConnect.php");
    session_start();
?>

<?php

    $user_name_err = $password_err = $email_err = $phone_number_err = "";
    $valid = true; 
    $valid1 = 0;

    // Function to test input
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        // Removed htmlspecialchars for password
        return $data;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['register'])) {
            // Signup validation
            //Username validation
            if (empty($_POST["username"])) {
                $user_name_err = "Username is required";
                $valid = false;
            } 
            elseif (!preg_match("/^[a-zA-Z-' ]*$/", $_POST["username"])) {
                $user_name_err = "Only letters and white space allowed";
                $valid = false;
            } 
            else {
                $username = test_input($_POST["username"]);

                //Email validation
                if (empty($_POST["email"])) {
                    $email_err = "Email is required";
                    $valid = false;
                } 
                elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                    $email_err = "Invalid email format";
                    $valid = false;
                } 
                else {
                    $email = test_input($_POST["email"]);
        
                    //Password validation
                    if (empty($_POST["password"])) {
                        $password_err = "Password is required";
                        $valid = false;
                    }
                    elseif(strlen($_POST["password"]) < 8){
                        $password_err = "Password should be more than 8 characters";
                        $valid = false;
                    }
                    else {
                        $password = $_POST["password"]; // Don't trim or alter password
                        $hash = password_hash($password, PASSWORD_DEFAULT);

                        //Phone-number validation
                        if (empty($_POST["phone-number"])) {
                            $phone_number_err = "Phone is required";
                            $valid = false;
                        } 
                        elseif (!preg_match("/^\+?\d{1,4}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}$/", $_POST["phone-number"])) {
                            $phone_number_err = "Invalid number format";
                            $valid = false;
                        }
                        elseif(strlen($_POST["phone-number"]) < 10){
                            $phone_number_err = "Number should be 10 or more";
                            $valid = false;
                        }
                        else {
                            $phone_number = test_input($_POST["phone-number"]);
                        }
                    }
                }
    
            }
    
            // If valid, proceed with signup logic
            if ($valid) {
                // Prepare and execute SQL statement here
                $sql = "INSERT INTO users (user_name, password, email, phone_number)
                        VALUES ('$username', '$hash', '$email', '$phone_number')";
                try{
                mysqli_query($conn, $sql);
                echo "You now registered!";
                }
                catch (mysqli_sql_exception $e) {
                    if ($e->getCode() == 1062) { // 1062 is the error code for duplicate entry
                        echo "Username or Number or Email Taken!";
                        $valid = false;
                    } else {
                        echo "An error occurred: " . $e->getMessage();
                    }
                }
            }
        } 
        elseif (isset($_POST['login'])) {
            // Login validation and logic
            //Email validation
            if (empty($_POST["email"])) {
                $email_err = "Email is required";
                $valid1 = 1;
            } 
            elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $email_err = "Invalid email format";
                $valid1 = 1;
            } 
            else {
                $email = test_input($_POST["email"]);

                //Password validation
                if (empty($_POST["password"])) {
                    $password_err = "Password is required";
                    $valid1 = 1;
                }
                elseif(strlen($_POST["password"]) < 8){
                    $password_err = "Password should be more than 8 characters";
                    $valid1 = 1;
                }
                else {
                    $password = $_POST["password"]; // Don't trim or alter password
                    //$hash = password_hash($password, PASSWORD_DEFAULT);
                }
            }

            // Login logic here
            if($valid1 === 0){
                $sql = "SELECT * FROM users WHERE email = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    if (password_verify($password, $row['password'])) {
                        // Login successful
                        // Start session, set login variables, etc.
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['password'] = $row['password'];
                        $_SESSION['logged_in'] = true;
                        header("location:home.php");
                        exit;
                    } 
                    else {
                        // Password incorrect
                        echo "Incorrect password";
                    }
                } 
                else {
                    // Email not found
                    echo "Email not found";
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
    <title>Login</title>
    <link rel="stylesheet" href="styles\loginstyle.css">
</head>
<body>
    <div class="container">
        <div class="login">
            <form id="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <label for="email" >Email</label>
                <input type="email" name="email" id="lemail" placeholder="Enter Email" value="<?php if(isset($_POST['login'])){ echo $_POST["email"];} ?>">
                <span class="error">* <?php echo $email_err; ?></span><br><br>
                <label for="password" >Password</label>
                <input type="password" name="password" id="lpassword" placeholder="Enter Password">
                <span class="error">* <?php echo $password_err; ?></span><br><br>
                <input class="log-ster-btn" id="loginbtn" type="submit" name="login" value="Log in">
                <span class="alt-method">
                    <p id="to-registertxt">Don't have an account?...</p>
                    <p id="to-registerbtn">Register</p>
                </span>
            </form>
            
        </div>

        <div class="register">
            <form id="register" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="hidden" id="valid" value="<?php echo $valid; ?>">
                <label for="username" >User Name</label>
                <input type="username" name="username" id="rusername" placeholder="Enter Username" value="<?php if(!$valid && isset($_POST['register'])) { echo $_POST["username"];} ?>">
                <span class="error">* <?php echo $user_name_err; ?></span><br><br><br>
                <label for="email" >Email</label>
                <input type="email" name="email" id="remail" placeholder="Enter Email" value="<?php if(!$valid && isset($_POST['register'])) { echo $_POST["email"];} ?>">
                <span class="error">* <?php echo $email_err; ?></span><br><br><br>
                <label for="password" >Password</label>
                <input type="password" name="password" id="rpassword" placeholder="Enter Password" >
                <span class="error">* <?php echo $password_err; ?></span><br><br><br>
                <label for="phone-number" >Phone Number</label>
                <input type="text" name="phone-number" id="phone-number" placeholder="Enter Phone Number" value="<?php if(!$valid && isset($_POST['register'])) { echo $_POST["phone-number"];} ?>">
                <span class="error">* <?php echo $phone_number_err; ?></span><br><br><br>
                <input class="log-ster-btn" id="registerbtn" type="submit" name="register" value="Register">
                <span class="alt-method">
                    <p id="to-logintxt">Have an account?...</p>
                    <p id="to-loginbtn">Log in</p>
                </span>
            </form>

        </div>
    </div>
    
    <script>
        console.log(document.getElementById('valid').value);
        if(document.getElementById('valid').value == ''){
            document.getElementById('login').style.display = 'none';
            document.getElementById('register').style.display = 'block';
            document.getElementById('register').style.opacity = '1';
        }
    </script>
    <script src="js\logins.js"></script>
</body>
</html>