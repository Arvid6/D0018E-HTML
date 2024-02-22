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
echo("HALLÅ");
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
    $temp_customer = session_id();
    $sql = $conn->query("SELECT TempcartId FROM TEMPCART WHERE USERID = '$temp_customer'"); //Get Tempcart
    $fetch = $sql->fetch_assoc();
    $cartId = $fetch['TempcartId']; //Fetch the ID

    //Get the product and the amount of each product grouped by ID
    $lol = $conn->query("SELECT ProductId, SUM(Amount) as TotalAmount FROM TEMPCARTITEMS WHERE TempcartId = $cartId GROUP BY ProductId");
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
                    $ProductId = $row['ProductId'];
                    $tName = $conn->query("SELECT * FROM Product WHERE ProductId = $ProductId"); //get the name from ID
                    $fetch = $tName->fetch_assoc();
                    $Name = $fetch['ProductName'];
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
            #$_SESSION['temp_customer'] = 2;
            #$hm = $_SESSION['temp_customer'];

            $temp_customer = session_id();
            echo($temp_customer);

            $checkUser = "SELECT TempcartId FROM TEMPCART WHERE UserId = '$temp_customer'";
            echo($checkUser);
            $refresh_block = $conn->query($checkUser);


            if((!$refresh_block->num_rows > 0)) {
                echo("skjjut mig");
                $conn->query("INSERT INTO TEMPCART (UserId) VALUES ('$temp_customer')");
            }


            // Query to get the products from database
            $sql = "SELECT * FROM Product";
            // Execute the query
            $res = $conn->query($sql);

            if( isset($_GET['add']) )
            {
                //be sure to validate and clean your variables
                $prod = htmlentities($_GET['id']);

                // Select the right cart id
                $ci = $conn->query("SELECT TempcartId FROM TEMPCART WHERE UserId = '$temp_customer'");
                $fetch = $ci->fetch_assoc();
                $tempid = $fetch['TempcartId'];

                // Check if the product already exists in the cart
                $already_in_cart_query = "SELECT ProductId FROM TEMPCARTITEMS WHERE TempcartId = $tempid and ProductId = $prod";
                $already_in_cart = $conn->query($already_in_cart_query);

                if($already_in_cart->num_rows > 0) {
                    $update_quantity = "UPDATE TEMPCARTITEMS SET Amount = Amount + 1 WHERE TempcartId = $tempid AND ProductId = $prod";
                    $conn->query($update_quantity);

                }else {
                    $add_cart_item = "INSERT INTO TEMPCARTITEMS (TempcartId, ProductId, Amount) VALUES($tempid, $prod, 1)";
                    $conn->query($add_cart_item);

                }

                /*$add_cart_item = "INSERT INTO TEMPCARTITEMS (TempcartId, ProductId, Amount) VALUES($tempid, $prod, 1)";
                echo($add_cart_item);
                if($conn->query($add_cart_item)) {
                    echo("Item added to cart");
                }else{
                    echo("Error: couldn't add item to cart");
                }*/
                //then you can use them in a PHP function.
                $result = add($prod);
            }
            function add($prod) {
                echo "<script>console.log('$prod');</script>";
            }


            if($res->num_rows > 0) {

                while($row = $res->fetch_assoc()) {
                    $ProductId = $row['ProductId'];
                    $ProductName = $row['ProductName'];
                    $Stock = $row['Stock'];
                    $Price = $row['Price'];
                    ?>
                    <tr>
                        <td><?php echo $ProductName; ?></td>
                        <td><?php echo $Price; ?></td>
                        <td><?php echo $Stock; ?></td>
                        <td><form method="Get" action="">
                            <input type="hidden" name="id" id="id" value="<?php echo $ProductId; ?>"/>
                            <input type="submit" name="add" class="button" value="Add to cart" />
                        </form></td>
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






