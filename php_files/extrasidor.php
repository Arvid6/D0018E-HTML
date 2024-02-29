<?php



    session_start();
    include("connect.php"); 

   
    
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
$product_id = $_GET['id']; 


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
                header("Location:extrasidor.php?id=$product_id");

            }

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
            <img src="<?php echo $img?>" id="imageextra" height="300px" width="300px">
            <p id="priceextra">Price: <?php echo $price . "kr"; ?></p>
            <p id="stockextra">Stock: <?php echo $stock; ?></p>

            <?php if($stock > 0): ?>
                <form method="Get" action="">
                    <input type="hidden" name="id" id="idextra" value="<?php echo $product_id; ?>"/>
                    <input type="submit" name="add" class="buttonextra" value="Add to cart" />
                </form>
            <?php else: ?>
                <button class="buttonextra" disabled>Out of stock</button>
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