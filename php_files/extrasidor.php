<?php



    session_start();
    include("connect.php"); 
    $cart_id = $_SESSION['cart_id'];

    $product_id = $_GET['id']; 
    ?>



<?php
    include("onatop.php"); 

    $sql = "SELECT * FROM product WHERE product_id = $product_id";
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
  
        while($row = $res->fetch_assoc()) {
            $product_name = $row['product_name'];
            $stock = $row['stock'];
            $price = $row['price'];
            $img = "img/" . $product_name . ".png";
            ?>

            <h1><?php echo $product_name; ?></h1>
            <img src="<?php echo $img?>" height="300px" width="300px">
            <p>Price: <?php echo $price . "kr"; ?></p>
            <p>Stock: <?php echo $stock; ?></p>

            <?php if($stock > 0): ?>
                <form method="Get" action="">
                    <input type="hidden" name="id" id="id" value="<?php echo $product_id; ?>"/>
                    <input type="submit" name="add" class="button" value="Add to cart" />
                </form>
            <?php else: ?>
                <button class="button" disabled>Out of stock</button>
            <?php endif; ?>

            <?php
        }
    } else {
        echo "0 results";
    }
    $conn->close();
?>
<head>

<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>
    <?php
        echo $product_name;
    ?>
</title>
<link rel="stylesheet" href="style.css">

</head>