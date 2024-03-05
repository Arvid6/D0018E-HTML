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

    $checkUser = "SELECT cart_id FROM cart WHERE UserId = $user_id";
    $refresh_block = $conn->query($checkUser);
    if((!$refresh_block->num_rows > 0)) {
        $conn->query("INSERT INTO cart (UserId) VALUES ($user_id)");
        echo("Ingen vagn");
    }
    // Fetch the user cart id
    $sql = $conn->query("SELECT cart_id FROM cart WHERE UserId = $user_id");
    $fetch = $sql->fetch_assoc();
    $cart_id = $fetch['cart_id'];

}else{
    $session_id = session_id();

    $check_session = "SELECT cart_id FROM cart WHERE session_id = '$session_id'";
    $q = $conn->query($check_session);
    if((!$q->num_rows>0)) {
        $conn->query("INSERT INTO cart (session_id) VALUES ('$session_id')");
    }
    // Fetch the session cart id
    $sql = $conn->query("SELECT cart_id FROM cart WHERE session_id = '$session_id'");
    $fetch = $sql->fetch_assoc();
    $cart_id = $fetch['cart_id'];
}
$_SESSION["cart_id"] = $cart_id;

?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Fron.se</title>
</head>

<body>
    
<?php include("onatop.php"); ?>

<main>
    <div id="storbild">
        <h1>Hej och välkommen till våran blomsterbutik!</h1>
    </div>

    <div id="text">
          <h2>Produkter: </h2>
        <div id="produclist">
            <?php


            if( isset($_GET['add']) )
            {
                //be sure to validate and clean your variables
                $prod = htmlentities($_GET['id']);

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
                    $img = "img/" . $product_name . ".png";
                    ?>
                    <div class="procuct">
                        <a href="extrasidor.php?id=<?php echo $product_id; ?>" style="color: black; text-decoration: none;"><img src="<?php echo $img?>" height="100px" width="100px"><br>
                            <strong><?php echo $product_name ; ?></a></strong><br>
                            <?php echo $price . "kr" ?> <br> <small> <?php echo "Stock: " .  $stock; ?></small>
                            <?php if($stock > 0): ?>
                                <form method="Get" action="">
                                    <input type="hidden" name="id" id="id" value="<?php echo $product_id; ?>"/>
                                    <input type="submit" name="add" class="button" value="Add to cart" />
                                </form>
                            <?php else: ?>
                                <br>
                                <button class="button" disabled>Out of stock</button>
                            <?php endif; ?>
                    </div>
                    <?php
                }
                
            }
            ?>

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
