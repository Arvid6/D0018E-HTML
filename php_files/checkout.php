<?php 
session_start();
 include("connect.php"); 
$cart_id = $_SESSION['cart_id'];
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Checkout</title>
</head>

<body>

<?php include("onatop.php"); ?>

<main>
    <div id="order">
    <style>
        table{
            border: black 1px;
        }
        table, th, td {
            font-size: larger;
            height: 1%;
            width: 10%;
        }
    </style>
    <table>
<?php
    //Get the product and the amount of each product grouped by ID
    $lol = $conn->query("SELECT product_id, SUM(quantity) as TotalAmount FROM cart_items WHERE cart_id = $cart_id GROUP BY product_id");
    $totprice = 0;

    if( isset($_GET['co']) ){
        $prod = htmlentities($_GET['data']);
    }


if($lol->num_rows > 0) { //
    //Get all the id per product, calculate the total price and display everything in a table.
    while($row = $lol->fetch_assoc()) {
        $product_id = $row['product_id'];
        $tName = $conn->query("SELECT * FROM product WHERE product_id = $product_id"); //get the name from ID
        $fetch = $tName->fetch_assoc();
        $Name = $fetch['product_name'];
        $stock = $fetch['stock'];
        $TotalAmount = $row['TotalAmount'];
        $stock_balance = $stock - $TotalAmount;

        $price = $fetch['price']; // Needs to be dynamic cant be gotten as a pointer to the product like it is now
        $totprice += $price * $TotalAmount;
        $img = "img/" . $Name . ".png";
        ?>
        <tr>
            <td><img src="<?php echo $img?>" height="100px" width="100px"></td>
            <?php if($stock_balance >= 0): ?>
                <td><strong><?php echo $Name ; ?> </strong><br><?php echo $TotalAmount . "st" ?> <br> <small> <?php echo $price * $TotalAmount . "kr"?> </small></td>
            <?php else: ?>
                <td>
                    <strong>
                        <?php echo $Name ; ?>
                    </strong>
                    <br>
                        <?php echo $TotalAmount . "st" ?>
                    <form method="Get" action="">
                        <input type="hidden" name="id" id="id" value="<?php echo $product_id; ?>"/>
                        <input type="submit" name="remove_many" class="button" value="Not enough in stock. Remove <?php echo($stock_balance*(-1)) ?> item(s)" />
                    </form>
                </td>
            <?php endif ?>
        </tr>
            <?php
        }
        ?>
    </table><br><br><br>
    <?php if($stock_balance >= 0): ?>
        <form method="Get" action="" id="chbutton">
            <h1>TOTAL COST: <?php echo $totprice ?></h1>
            <input type="hidden" name="data" id="data" value="<?php ?>"/>
            <input type="submit" name="co" class="button" id="chout" value="CHECK-OUT" />
        </form>
    <?php else: ?>
        <h1>TOTAL COST: <?php echo $totprice ?></h1>
        <button class="button" disabled>Remove items before checking out</button>
    <?php endif ?>
        <?php
}

if(isset($_GET['remove_one'])) {
    $prod_id = htmlentities($_GET['id']);
    $conn->query("UPDATE cart_items SET quantity = quantity - 1 WHERE cart_id = $cart_id AND product_id = $prod_id");
}


if(isset($_GET['remove_many'])) {
    $prod_id = htmlentities($_GET['id']);
    $prod_info = $conn->query("SELECT * FROM product WHERE product_id = $prod_id");
    $fetch = $prod_info->fetch_assoc();
    $curr_stock = $fetch['stock'];
    $in_cart = $_SESSION['items_in_cart'][$prod_id];
    $balance = $curr_stock - $in_cart;
    if($balance < 0) {
        $conn->query("UPDATE cart_items SET quantity = quantity + $balance WHERE cart_id = $cart_id AND product_id = $prod_id");
    }
    header("Location:checkout.php");

}


if(isset($_GET['co'])) {
    // Add from cart to order, and delete the cart after
    $lol = $conn->query("SELECT * FROM cart_items  WHERE cart_id = $cart_id");

    // Add new order if there are items in the cart
    if($lol->num_rows > 0) {

        if(isset($_SESSION['userId'])) {
            echo("Inloggad");
            $user_id = implode($_SESSION['userId']);
            $tbi = "INSERT INTO `order` (UserId) VALUES ($user_id)";
            $conn->query($tbi);
            // Fetch order ID
            $fetch_orderId = $conn->query("SELECT order_id FROM `order` WHERE UserId = $user_id ORDER BY order_id Desc");

        }else{
            $session_id = session_id();
            echo("utloggad");
            $conn->query("INSERT INTO `order` (session_id) VALUES ('$session_id')");
            // Fetch order ID
            $fetch_orderId = $conn->query("SELECT order_id FROM `order` WHERE session_id = '$session_id' ORDER BY order_id Desc");

        }

        $orderId = ($fetch_orderId->fetch_assoc())['order_id'];

        while($row = $lol->fetch_assoc()) {
            $product_id = $row['product_id'];
            $quantity = $row['quantity'];

            $add_item = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($orderId, $product_id, $quantity, 500)";
            $update_stock = "UPDATE product SET stock = stock - $quantity WHERE product_id = $product_id";

            $conn->query($add_item);
            $conn->query($update_stock);
        }

        // Delete every cart item corresponding to the right user id
        $conn->query("DELETE FROM cart_items WHERE cart_id = $cart_id");
        header("Refresh:0");
        echo("Order made!");
    }else {
        echo("Your cart is empty");
    }

}

?>
<p> </p>
    </div>
</main>
<br>



<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script text="text/javscript" src="script.js"></script>
</body>
</html>

