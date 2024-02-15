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
            <li><a class="link" href="login.php">SIGN IN</a></li>
        </ul>
    </nav>
</header>
<br><br><br>
    <div class="reg">
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