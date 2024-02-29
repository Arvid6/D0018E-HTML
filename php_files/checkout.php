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
        table, th, td {
            border: 1px solid black;
            font-size: larger;
            height: 1%;
            height: 60px;
            width: 15%;
        }
    </style>
    <table >
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
        $TotalAmount = $row['TotalAmount'];
        $price = $fetch['price']; // Needs to be dynamic cant be gotten as a pointer to the product like it is now
        $totprice += $price * $TotalAmount;
        ?>
        <tr>
            <td><?php echo $Name ; ?></td>
            <td><?php echo $TotalAmount; ?></td>
        </tr>
            <?php
        }
        ?>
    </table><br><br><br>

    <form method="Get" action="" id="chbutton">
        <h1>TOTAL COST: <?php echo $totprice ?></h1>
        <input type="hidden" name="data" id="data" value="<?php ?>"/>
        <input type="submit" name="co" class="button" id="chout" value="CHECK-OUT" />
    </form>
        <?php
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

            $qr = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($orderId, $product_id, $quantity, 500)";
            echo($qr);
            $conn->query($qr);
            echo("HMMMMMMMMMM");
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

