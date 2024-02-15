<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register an Account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <?php
    if (isset($_POST["submit"])){
        global $conn;
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $passwordRepeat = $_POST["repeat-password"];

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $errors = array();

        if (empty($name) or empty($email) or empty($password) or empty($passwordRepeat)) {
            array_push($errors, "All fields need to be filled in");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Please provide a valid email");
        }
        if (strlen($password) < 8) {
            array_push($errors, "Password must be at least 8 characters long");
        }
        if ($password != $passwordRepeat) {
            array_push($errors, "Password does not match");
        }
        require_once "connect.php";
        $emailCheck = "SELECT * FROM isaksTestbord WHERE email = '$email'";
        $result = $conn -> query($emailCheck);
        $emailAmount = $result -> num_rows;
        if ($emailAmount > 0 ) {
            array_push($errors, "Email already exists");
        }


        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }else{
            $sql = "INSERT INTO isaksTestbord (name, email, password) VALUES (?,?,?)";
            $stmt = $conn -> stmt_init();
            $prepareStmt = $stmt -> prepare($sql);
            if ($prepareStmt) {
                $stmt -> bind_param("sss",$name, $email, $passwordHash);
                $stmt -> execute();
                echo "<div class='alert alert-success'>You were registered successfully</div>";
            }else{
                die("Something went wrong");
            }
        }
    }
    ?>
    <form action="registration.php" method="post">
        <div class="reg-group">
            <input type="text" name="name" placeholder="Name">
        </div>
        <div class="reg-group">
            <input type="email" name="email" placeholder="E-mail">
        </div>
        <div class="reg-group">
            <input type="password" name="password" placeholder="Password">
        </div>
        <div class="reg-group">
            <input type="password" name="repeat-password" placeholder="Repeat Password">
        </div>
        <div class="form-submit">
            <input type="submit" value="Register" name="submit">
        </div>
    </form>
</div>
</body>
</html>