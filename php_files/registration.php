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
    <title>Register an Account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="registerBody">
<header>
    <nav>
        <ul>
            <li><a class="link" href="index.php">START</a></li>
            <li><a class="link" href="checkout.php">CHECKOUT</a></li>
            <li><a class="link" href="login.php">SIGN IN</a></li>
        </ul>
    </nav>
</header>
<br><br><br>
<div class="reg">
        <?php
        if (isset($_POST["submit"])){
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            global $conn;
            $firstName = $_POST["first-name"];
            $lastName = $_POST["last-name"];
            $email = $_POST["email"];
            $phoneNumber = $_POST["phone-number"];
            $uPassword = $_POST["password"];
            $passwordRepeat = $_POST["repeat-password"];

            $passwordHash = password_hash($uPassword, PASSWORD_DEFAULT);
            $errors = array();

            if (empty($firstName) or empty($lastName) or empty($email) or empty($phoneNumber) or empty($password) or empty($passwordRepeat)) {
               array_push($errors, "All fields need to be filled in");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Please provide a valid email");
            }
            if (!preg_match("/^[0-9]{10}+$/", $phoneNumber)) {
                array_push($errors, "Please provide a valid phone number");
            }
            if (strlen($password) < 8) {
                array_push($errors, "Password must be at least 8 characters long");
            }
            if ($password != $passwordRepeat) {
                array_push($errors, "Password does not match");
            }
            require_once "connect.php";
            $emailCheck = "SELECT * FROM User WHERE email = '$email'";
            $result = $conn -> query($emailCheck);
            $emailAmount = $result -> num_rows;
            if ($emailAmount > 0 ) {
                array_push($errors, "Email already exists");
            }


            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div>$error</div>";
                }
            }else{
                $sql = "INSERT INTO User (firstName, lastName, email, phoneNumber, password) VALUES (?,?,?,?,?)";
                $stmt = $conn -> stmt_init();
                $prepareStmt = $stmt -> prepare($sql);
                if ($prepareStmt) {
                    $stmt -> bind_param("sssss",$firstName,$lastName, $email, $phoneNumber, $passwordHash);
                    $stmt -> execute();
                    echo "<div>You were registered successfully</div>";
                }else{
                    die("Something went wrong");
                }
            }    
        }
        ?>
        <form action="registration.php" method="post">
            <div class="reg-group">
                <input type="text" name="first-name" placeholder="First Name">
            </div>
            <div class="reg-group">
                <input type="text" name="last-name" placeholder="Last Name">
            </div>
            <div class="reg-group">
                <input type="email" name="email" placeholder="E-mail">
            </div>
            <div class="reg-group">
                <input type="tel" name="phone-number" placeholder="Phone Number">
            </div>
            <div class="reg-group">
                <input type="password" name="password" placeholder="Password">
            </div>
            <div class="reg-group">
                <input type="password" name="repeat-password" placeholder="Repeat Password">
            </div>
            <div class="reg-submit">
                <input type="submit" value="Register" name="submit">
            </div>
        </form>
    </div>
</body>
</html>