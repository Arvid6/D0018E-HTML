<?php

$servername = "utbweb.its.ltu.se";
$username = "19990921";
$password = "Projekt99!";
$dbname = "db19990921";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
session_start();

// Fetch user id if logged in, or get session id if not logged in.
if(isset($_SESSION['userId'])){
    $user_id = implode($_SESSION['userId']);

}else{
    $user_id = session_id();
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Eden</title>
</head>

<body>

<div id="minimeny">
    <?php
    $sql = $conn->query("SELECT cart_id FROM cart WHERE USERID = '$user_id'"); //Get cart
    $fetch = $sql->fetch_assoc();
    $cartId = $fetch['cart_id']; //Fetch the ID

    //Get the product and the amount of each product grouped by ID
    $lol = $conn->query("SELECT product_id, SUM(quantity) as TotalAmount FROM cart_items WHERE cart_id = $cartId GROUP BY product_id");
    ?>
    <div id="minimeny2">
        <button class="buttonaverage" ><a class="linkmeny" href="index.php">START</a></button>
        <button class="buttonaverage"><?php if (isset($_SESSION['login'])) { ?>
                    <a class="linkmeny" href="logout.php">SIGN OUT</a>
                <?php }else{ ?>
                <a class="linkmeny" href="login.php">SIGN IN</a>
                <?php } ?></button>
        <div class="dropdown">
            <button id="buttondrop">Cart</button>
            <div class="content">
                <?php
                if($lol->num_rows > 0) { //
                //Get all the id per product, calculate the total price and display everything in a table.
                while($row = $lol->fetch_assoc()) {
                    $product_id = $row['product_id'];
                    $tName = $conn->query("SELECT * FROM product WHERE product_id = $product_id"); //get the name from ID
                    $fetch = $tName->fetch_assoc();
                    $Name = $fetch['product_name'];
                    $TotalAmount = $row['TotalAmount'];
                    ?>
                <a href="#"><?php echo $Name, " | ", $TotalAmount ; ?> </a>
                <?php
                }
                }
                else{
                    ?>
                    <a href="">No items in cart :(</a>
                <?php
                }
                ?>
                <button class="buttonaverage"><a href="checkout.php">CHECKOUT</a></button>
            </div>
        </div>
    </div>
</div>
<main>
    <div id="storbild">
        <h1>Hej och välkommen till våran blomsterbutik!</h1>
    </div>

    <div id="text">
        <p>Produkter </p>
        <style>
            table, th, td {
                border: 1px solid black;
            }
        </style>
        <table>
        <tr>
            <td>Namn</td>
            <td>Pris</td>
            <td>Lagerstatus</td>
            <td>Buy</td>
        </tr>
            <?php
            $checkUser = "SELECT cart_id FROM cart WHERE UserId = '$user_id'";
            echo($checkUser);
            $refresh_block = $conn->query($checkUser);

            if((!$refresh_block->num_rows > 0)) {
                $conn->query("INSERT INTO cart (UserId) VALUES ('$user_id')");

            }

            if( isset($_GET['add']) )
            {
                //be sure to validate and clean your variables
                $prod = htmlentities($_GET['id']);

                // Select the right cart id
                $ci = $conn->query("SELECT cart_id FROM cart WHERE UserId = '$user_id'");
                $fetch = $ci->fetch_assoc();
                $cart_id = $fetch['cart_id'];

                // Check if the product already exists in the cart
                $already_in_cart_query = "SELECT product_id FROM cart_items WHERE cart_id = $cart_id and product_id = $prod";
                $already_in_cart = $conn->query($already_in_cart_query);

                // Get product price when clicked
                $price_query = $conn->query("SELECT price FROM product WHERE product_id= $prod");
                $fetch_price = $price_query->fetch_assoc();
                $prod_price =$fetch_price['price'];

                $update_stock = "UPDATE product SET stock = stock - 1 WHERE product_id = $prod";

                if($already_in_cart->num_rows > 0) {
                    $update_quantity = "UPDATE cart_items SET quantity = quantity + 1 WHERE cart_id = $cart_id AND product_id = $prod";
                    $conn->query($update_stock);
                    $conn->query($update_quantity);

                }else {
                    $add_cart_item = "INSERT INTO cart_items (cart_id, product_id, quantity, price) VALUES($cart_id, $prod, 1, $prod_price)";
                    echo($add_cart_item);
                    $conn->query($update_stock);
                    $conn->query($add_cart_item);
                }
                header("Location:index.php");

            }

            // Query to get the products from database
            $sql = "SELECT * FROM product";
            // Execute the query
            $res = $conn->query($sql);

            if($res->num_rows > 0) {

                while($row = $res->fetch_assoc()) {
                    $product_id = $row['product_id'];
                    $product_name = $row['product_name'];
                    $stock = $row['stock'];
                    $price = $row['price'];
                    ?>
                    <tr>
                        <td><?php echo $product_name; ?></td>
                        <td><?php echo $price; ?></td>
                        <td><?php echo $stock; ?></td>
                        <?php if($stock > 0): ?>
                        <td><form method="Get" action="">
                            <input type="hidden" name="id" id="id" value="<?php echo $product_id; ?>"/>
                            <input type="submit" name="add" class="button" value="Add to cart" />
                        </form></td>
                        <?php else: ?>
                        <td>
                            <button class="button" disabled>Out of stock</button>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>

    </div>

    <div id="container">
        <div class="bilda">
        </div>
        <div class="bilda">

        </div>
    </div>
</main>
<br>



<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script text="text/javscript" src="script.js"></script>
</body>
</html>






