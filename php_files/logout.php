<?php

session_start();
unset($_SESSION["userId"]);
unset($_SESSION["login"]);
unset($_SESSION["userType"]);
unset($_SESSION['items_in_cart']);
header("Location: index.php");
$path = "Location:logout.php";
?>
