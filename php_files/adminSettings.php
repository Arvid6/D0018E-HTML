<?php

session_start();
include("connect.php");
$cart_id = $_SESSION['cart_id'];
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


<?php include("onatop.php"); ?>


<body class="registerBody">
    <br><br><br>
    <div class="add_items">
        <?php
        if (isset($_POST["addProduct"])){
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            global $conn;
            $productName = $_POST["productName"];
            $price = $_POST["productPrice"];
            $stock = $_POST["stock"];

            $errors = array();

            if (empty($productName) or empty($price) or empty($stock)) {
                array_push($errors, "All fields need to be filled in");
            }
            if(!is_int($price)) {
                array_push($errors, "Input must be a number");
            }
            if(!is_int($stock)) {
                array_push($errors, "Input must be a number");
            }

            //require_once "connect.php";


            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div>$error</div>";
                }
            }else{
                $sql = "INSERT INTO product (product_name, price, stock) VALUES (?,?,?)";
                $stmt = $conn -> stmt_init();
                $prepareStmt = $stmt -> prepare($sql);
                if ($prepareStmt) {
                    $stmt -> bind_param("sii",$productName,$price, $stock);
                    $stmt -> execute();
                    echo "<div>Product added successfully</div>";
                }else{
                    die("Something went wrong");
                }
            }
        }
        ?>
        <form action="adminSettings.php" method="post">
            <div class="addProduct-group">
                <input type="text" placeholder="Product Name" name="productName">
            </div>
            <div class="addProduct-group">
                <input type="number" placeholder="Product Price" name="productPrice">
            </div>
            <div class="addProduct-group">
                <input type="number" VALUE="Stock" name="stock">
            </div>
            <div class="addProduct-submit">
                <input type="submit" VALUE="Add Product" name="addProduct">
            </div>
        </form>
    </div>
</body>
</html>