<?php

session_start();
unset($_SESSION["userId"]);
unset($_SESSION["login"]);
unset($_SESSION["userType"]);
header("Location: index.php");

?>
