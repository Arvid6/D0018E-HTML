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
$path = "Location:index.php";

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


            // Query to get the products from database
            $sql = "SELECT * FROM product";
            // Execute the query
            $res = $conn->query($sql);

            if($res->num_rows > 0) {
                $i = 1;
                while($row = $res->fetch_assoc()) {
                    $product_id = $row['product_id'];
                    $product_name = $row['product_name'];
                    $real_stock = $row['stock'];
                    $items_in_cart = $_SESSION['items_in_cart'][$i];
                    if($real_stock >= $items_in_cart) {
                        $stock = $real_stock - $_SESSION['items_in_cart'][$i];
                    }else{
                        $stock = 0;
                    }


                    $price = $row['price'];
                    $img = "img/" . $product_name . ".png";
                    $i = $i + 1;
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
