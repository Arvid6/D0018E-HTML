<?php

session_start();
if (isset($_SESSION["login"])) {
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="registerBody">
<header>
    <nav>
        <ul>
            <li><a class="link" href="index.php">START</a></li>
            <li><a class="link" href="checkout.php">CHECKOUT</a></li>
        </ul>
    </nav>
</header>
<br><br><br>
    <div class="reg">
        <?php
        global $conn;
        if (isset($_POST["login"])) {
            $email = $_POST["email"];
            $uPassword = $_POST["password"];
            require_once "connect.php";
            $emailCheck = "SELECT * FROM User WHERE email = '$email'";
            $result = $conn -> query($emailCheck);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($user) {
                if (password_verify($uPassword, $user["password"])) {
                    $getUserId = "SELECT userId FROM User WHERE email = '$email'";
                    $idResult = $conn -> query($getUserId);
                    $userId = mysqli_fetch_array($idResult, MYSQLI_NUM);
                    session_start();
                    $_SESSION["login"] = true;
                    $_SESSION["userId"] = $userId;
                    header("Location: index.php");
                    die();
                }else{
                    echo "<div>Incorrect password</div>";
                }
            }else{
                echo "<div>E-mail doesn't exist</div>";
            }
        }
        ?>
        <form action="login.php" method="post">
            <div class="reg-group">
                <input type="email" placeholder="E-mail" name="email">
            </div>
            <div class="reg-group">
                <input type="password" placeholder="Password" name="password">
            </div>
            <div class="reg-submit">
                <input type="submit" VALUE="LOGIN" name="login">
            </div>
            <div>
                <h5 class="reg-group">Don't have an account?<br><a href="registration.php">Register</a></h5
            </div>
        </form>
    </div>
</body>
</html>