<?php
    include ("php\dbConnect.php");
    session_start();
?>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "Get"){
        if(isset($_POST['logout'])){
            session_unset();
            session_destroy();
            header("location:login.php");
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
    <link rel="stylesheet" href="styles\style.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    
    <header>
        <div class="welcomemessage">
            Welcome.
        </div>
        <div class="navs">
            <nav>
                <a class="navmenu" href="#"> home <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                    <!--!Font Awesome Free 7.0.0 by @fontawesome - https://fontawesome.com License
                     - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                     <path d="M341.8 72.6C329.5 61.2 310.5 61.2 298.3 72.6L74.3 280.6C64.7 289.6 61.5 303.5 66.3
                      315.7C71.1 327.9 82.8 336 96 336L112 336L112 512C112 547.3 140.7 576 176 576L464 576C499.3
                       576 528 547.3 528 512L528 336L544 336C557.2 336 569 327.9 573.8 315.7C578.6 303.5 575.4
                        289.5 565.8 280.6L341.8 72.6zM304 384L336 384C362.5 384 384 405.5 384 432L384 528L256 528L256
                         432C256 405.5 277.5 384 304 384z"/></svg> </a>
                <a class="navmenu" href="#"> friends <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                    <!--!Font Awesome Free 7.0.0 by @fontawesome - https://fontawesome.com License
                     - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                     <path d="M96 192C96 130.1 146.1 80 208 80C269.9 80 320 130.1 320 192C320 253.9 269.9
                      304 208 304C146.1 304 96 253.9 96 192zM32 528C32 430.8 110.8 352 208 352C305.2 352 384 430.8
                       384 528L384 534C384 557.2 365.2 576 342 576L74 576C50.8 576 32 557.2 
                       32 534L32 528zM464 128C517 128 560 171 560 224C560 277 517 320 464 320C411 320 368 277 368 224C368
                        171 411 128 464 128zM464 368C543.5 368 608 432.5 608 512L608 534.4C608 557.4 589.4 576 566.4
                         576L421.6 576C428.2 563.5 432 549.2 432 534L432 528C432 476.5 414.6 429.1 385.5 391.3C408.1 376.6
                          435.1 368 464 368z"/></svg> </a>
                <a class="navmenu" href="#"> market <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                    <!--!Font Awesome Free 7.0.0 by @fontawesome - https://fontawesome.com License
                     - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                     <path d="M53.5 245.1L110.3 131.4C121.2 109.7 143.3 96 167.6 96L472.5 96C496.7 96 518.9 109.7
                      529.7 131.4L586.5 245.1C590.1 252.3 592 260.2 592 268.3C592 295.6 570.8 318 544 319.9L544
                       512C544 529.7 529.7 544 512 544C494.3 544 480 529.7 480 512L480 320L384 320L384 496C384
                        522.5 362.5 544 336 544L144 544C117.5 544 96 522.5 96 496L96 319.9C69.2 318 48 295.6 48
                         268.3C48 260.3 49.9 252.3 53.5 245.1zM160 320L160 432C160 440.8 167.2 448 176 448L304
                          448C312.8 448 320 440.8 320 432L320 320L160 320z"/></svg> </a>
                <a class="navmenu" href="#"> explore <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
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
                        <p> User Name.........</p>
                        <p> Email........</p>
                        <p> Account Type.......</p>
                        <p href="#"> Number.....</p>
                        <p href="#"> Rank.....</p>
                        <a href="#"> Potfolio.....</a>
                        <a href="#"> Settings.....</a>
                        <a href="#"> Account Info.....</a><br>
                    </div>
            </span>

            <span class="logout">
                <form action="" method="GET">
                    <input type="button" name="logout" id="logoutbtn" value="Log Out">
                </form>
            </span>
        </div>

    </header>

    <div class="container">
        <div class="chart1">
            dahrt here dgsdgsgfsdfsfsfsfdfsfsdfgfgddfgsgfdsg<br><br><br><br><br><br><br>
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            dahrt here dgsdgsgfsdfsfsfsfdfsfsdfsdfsdfsdfsdfs
        </div>

        <div class="chart2">
            dahrt here dgsdgsgfsdfsfsfsfdfsfsdfgfgddfgsgfdsg<br><br><br><br><br><br><br>
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            dahrt here dgsdgsgfsdfsfsfsfdfsfsdfsdfsdfsdfsdfs
        </div>
    </div>
    <script src="js\header.js"></script>
</body>
</html>