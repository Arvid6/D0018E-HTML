<div id="minimeny">
    <?php
    // Fetch user id if logged in, or get session id if not logged in.

    if (isset($_SESSION['userId'])) {
        $user_id = implode($_SESSION['userId']);

        $checkUser = "SELECT cart_id FROM cart WHERE UserId = $user_id";
        $refresh_block = $conn->query($checkUser);
        if ((!$refresh_block->num_rows > 0)) {
            $conn->query("INSERT INTO cart (UserId) VALUES ($user_id)");
            echo("Ingen vagn");
        }
        // Fetch the user cart id
        $sql = $conn->query("SELECT cart_id FROM cart WHERE UserId = $user_id");
        $fetch = $sql->fetch_assoc();
        $cart_id = $fetch['cart_id'];

    } else {
        $session_id = session_id();

        $check_session = "SELECT cart_id FROM cart WHERE session_id = '$session_id'";
        $q = $conn->query($check_session);
        if ((!$q->num_rows > 0)) {
            $conn->query("INSERT INTO cart (session_id) VALUES ('$session_id')");
        }
        // Fetch the session cart id
        $sql = $conn->query("SELECT cart_id FROM cart WHERE session_id = '$session_id'");
        $fetch = $sql->fetch_assoc();
        $cart_id = $fetch['cart_id'];
    }
    $_SESSION["cart_id"] = $cart_id;
    $items_in_cart = array(0);

    $sql = "SELECT * FROM product";
    $res = $conn->query($sql);
    $i = 1;
    if ($res->num_rows > 0) {

        while ($row = $res->fetch_assoc()) {
            $check_cart = $conn->query("SELECT product_id FROM cart_items WHERE cart_id = $cart_id and product_id = $i");

            if ($check_cart->num_rows > 0) {
                $get_quant = $conn->query("SELECT quantity FROM cart_items WHERE cart_id = $cart_id AND product_id = $i");
                $gq_fetch = $get_quant->fetch_assoc();
                $quant = $gq_fetch['quantity'];
                array_push($items_in_cart, $quant);
            } else {
                array_push($items_in_cart, 0);
            }

            $i = $i + 1;
        }
    }
    $_SESSION['items_in_cart'] = $items_in_cart;

    if (isset($_GET['add'])) {
        //be sure to validate and clean your variables
        $prod = htmlentities($_GET['id']);

        // Check if the product already exists in the cart
        $already_in_cart_query = "SELECT product_id FROM cart_items WHERE cart_id = $cart_id and product_id = $prod";
        $already_in_cart = $conn->query($already_in_cart_query);

        // Get product price when clicked
        $price_query = $conn->query("SELECT price FROM product WHERE product_id= $prod");
        $fetch_info = $price_query->fetch_assoc();
        $prod_price = $fetch_info['price'];

        //$update_stock = "UPDATE product SET stock = stock - 1 WHERE product_id = $prod";

        if ($already_in_cart->num_rows > 0) {
            $update_quantity = "UPDATE cart_items SET quantity = quantity + 1 WHERE cart_id = $cart_id AND product_id = $prod";
            //$conn->query($update_stock);
            $conn->query($update_quantity);

        } else {
            $add_cart_item = "INSERT INTO cart_items (cart_id, product_id, quantity, price) VALUES($cart_id, $prod, 1, $prod_price)";
            echo($add_cart_item);
            //$conn->query($update_stock);
            $conn->query($add_cart_item);
        }

        $get_quant = $conn->query("SELECT quantity FROM cart_items WHERE cart_id = $cart_id AND product_id = $prod");
        $gq_fetch = $get_quant->fetch_assoc();
        $quant = $gq_fetch['quantity'];
        $add_item = array($prod => $quant);
        $new_item_count = array_replace($items_in_cart, $add_item);
        $_SESSION['items_in_cart'] = $new_item_count;


        header($path);

    }
    if (isset($_GET['remove_one'])) {
        $prod_id = htmlentities($_GET['id']);
        $in_cart = $_SESSION['items_in_cart'][$prod_id];
        if ($in_cart > 1) {
            $conn->query("UPDATE cart_items SET quantity = quantity - 1 WHERE cart_id = $cart_id AND product_id = $prod_id");
        } else if ($in_cart == 1) {
            $conn->query("DELETE FROM cart_items WHERE cart_id = $cart_id AND product_id = $prod_id");
            unset($_SESSION['items_in_cart'][$prod_id]);
        }
        header($path);

    }
    $lol = $conn->query("SELECT product_id, SUM(quantity) as TotalAmount FROM cart_items WHERE cart_id = $cart_id GROUP BY product_id");
    ?>
    <div id="minimeny2">
        <button class="buttonaverage"><a class="linkmeny" href="index.php">START</a></button>
        <button class="buttonaverage"><?php if (isset($_SESSION['login'])) { ?>
                <a class="linkmeny" href="logout.php">SIGN OUT</a>
            <?php } else { ?>
                <a class="linkmeny" href="login.php">SIGN IN</a>
            <?php } ?></button>
        <div class="dropdown">
            <button id="buttondrop">Cart</button>
            <div class="content">
                <?php
                if ($lol->num_rows > 0) { //
                    //Get all the id per product, calculate the total price and display everything in a table.
                    while ($row = $lol->fetch_assoc()) {
                        $product_ids = $row['product_id'];
                        $tName = $conn->query("SELECT * FROM product WHERE product_id = $product_ids"); //get the name from ID
                        $fetch = $tName->fetch_assoc();
                        $Name = $fetch['product_name'];
                        $TotalAmount = $row['TotalAmount'];
                        $img = "img/" . $Name . ".png";
                        ?>
                        <a href="extrasidor.php?id=<?php echo $product_ids; ?>"
                           style="color: black; text-decoration: none;"> <img src="<?php echo $img ?>" height="30px"
                                                                              width="30px"><?php echo $Name, " | ", $TotalAmount; ?>
                        </a>
                        <form method="Get" action="">
                            <input type="hidden" name="id" id="id" value="<?php echo $product_ids; ?>"/>
                            <input type="submit" name="add" class="button" value="+1" width="30%"/>
                        </form>
                        <form method="Get" action="">
                            <input type="hidden" name="id" id="id" value="<?php echo $product_ids; ?>"/>
                            <input type="submit" name="remove_one" class="button" value="-1" width="30%"/>
                        </form>
                        <?php
                    }
                } else {
                    ?>
                    <a href="">No items in cart :(</a>
                    <?php
                }
                ?>
                <button class="buttonaverage"><a href="checkout.php">CHECKOUT</a></button>
            </div>
        </div>
        <?php
        if (isset($_SESSION["userType"]) && $_SESSION['userType'][0] == "1") {
            ?>
            <button class="buttonaverage"><a class="linkmeny" href="adminSettings.php">Admin</a></button>
        <?php } ?>

    </div>
</div>