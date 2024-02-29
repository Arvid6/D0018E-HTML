<div id="minimeny">
    
    <?php
    $lol = $conn->query("SELECT product_id, SUM(quantity) as TotalAmount FROM cart_items WHERE cart_id = $cart_id GROUP BY product_id");
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
        <?php
        if (isset($_SESSION["userType"]) && $_SESSION['userType'][0] == "1")  { 
            ?>
            <button class="buttonaverage" ><a class="linkmeny" href="adminSettings.php">Admin</a></button>
        <?php } ?>

    </div>
</div>