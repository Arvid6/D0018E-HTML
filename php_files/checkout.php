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



?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Checkout</title>
</head>

<body>
<header>
    <nav>
        <ul>
            <li><a class="link" href="index.php">START</a></li>
            <li><a class="link" href="checkout.php">CHECKOUT</a></li>
            <li><a class="link" href="login.php">SIGN IN</a></li>
        </ul>
    </nav>
</header>

<main>
    <div id="order">
    <style>
        table, th, td {
            border: 1px solid black;
        }
    </style>
    <table>
<?php
    session_start();
    $sql = $conn->query("SELECT TempcartId FROM TEMPCART WHERE USERID = 2"); //FIX, SHOULD GET CART ID TO THEN GET ITEMS
    $fetch = $sql->fetch_assoc();
    $cartId = $fetch['TempcartId'];
    $lol = $conn->query("SELECT * FROM TEMPCARTITEMS  WHERE TempcartId = $cartId");
    $totPrice = 0;

    if( isset($_GET['co']) ){
        $prod = htmlentities($_GET['data']);
    }


if($lol->num_rows > 0) { //

    while($row = $lol->fetch_assoc()) {
        $ProductId = $row['ProductId'];
        $Amount = $row['Amount'];
        //$Price = $row['SELECT Price FROM ProductId']; //???? FILL LATER WHEN PRICE IS ADDED AS VARIABLE
        //$totPrice += $Price
        ?>
        <tr>
            <td><?php echo $ProductId; ?></td>
            <td><?php echo $Amount; ?></td>
            <?php
        }
        ?>
    </table>
    <form method="Get" action="">
        <h1>TOTAL COST: <?php echo $totPrice ?></h1>
        <input type="hidden" name="data" id="data" value="<?php ?>"/>
        <input type="submit" name="co" class="button" value="CHECK-OUT" />
    </form>
        <?php
}

if(isset($_GET['co'])) {
    // Add from cart to order, and delete the cart after
    $tempid = $_SESSION['temp_customer'];
    $lol = $conn->query("SELECT * FROM TEMPCARTITEMS  WHERE TempcartId = $cartId");

    if($lol->num_rows > 0) {
        // Add new order if there are items in the cart
        $conn->query("INSERT INTO TEMPORDER (UserId) VALUES ($tempid)");
        // Fetch order ID
        $fetch_orderId = $conn->query("SELECT TemporderId FROM TEMPORDER WHERE UserId = 2 ORDER BY TemporderId Desc");

        $orderId = ($fetch_orderId->fetch_assoc())['TemporderId'];
        echo($orderId);
        while($row = $lol->fetch_assoc()) {
            $ProductId = $row['ProductId'];
            $Amount = $row['Amount'];

            $qr = "INSERT INTO TEMPORDERITEM (TemporderId, ProductId, Amount, Price) VALUES ($orderId, $ProductId, $Amount, 500)";
            echo($qr);
            $conn->query($qr);
            echo("HMMMMMMMMMM");
        }

        // Delete every cart item corresponding to the right user id

        echo($cartId);
        $conn->query("DELETE FROM TEMPCARTITEMS WHERE TempcartId = $cartId");
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

