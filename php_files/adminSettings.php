<?php

session_start();
include("connect.php");
$cart_id = $_SESSION['cart_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <link rel="stylesheet" href="style.css">
</head>


<?php include("onatop.php"); ?>


<body class="registerBody">
    <br><br><br>
    <div class="add_items">
        <?php
        if (isset($_POST["addProduct"])){
            $productName = $_POST["productName"];
            $price = floatval($_POST["productPrice"]);
            $stock = intval($_POST["stock"]);
            $productInfo = $_POST["productInfo"];

            $errors = array();

            if (empty($productName) or empty($price) or empty($stock) or empty($productInfo)) {
                array_push($errors, "All fields need to be filled in");
            }
            if(!is_float($price)) {
                print_r($price);
                array_push($errors, "Input must be a number");
            }
            if(!is_int($stock)) {
                print_r($stock);
                array_push($errors, "Input must be a number");
            }

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div>$error</div>";
                }
            }else{
                $sql = "INSERT INTO product (product_name, price, stock, product_info) VALUES (?,?,?,?)";
                $stmt = $conn -> stmt_init();
                $prepareStmt = $stmt -> prepare($sql);
                if ($prepareStmt) {
                    $stmt -> bind_param("sdis",$productName,$price, $stock, $productInfo);
                    $stmt -> execute();
                    echo "<div>Product added successfully</div>";
                }else{
                    die("Something went wrong");
                }
            }
        }
        ?>
        <form action="adminSettings.php" method="post">
            <div class="addProduct-group">
                <input type="text" placeholder="Product Name" name="productName">
            </div>
            <div class="addProduct-group">
                <input type="number" placeholder="Product Price" step="0.01" name="productPrice">
            </div>
            <div class="addProduct-group">
                <input type="number" placeholder="Stock" name="stock">
            </div>
            <div class="addProduct-group">
                <input type="text" placeholder="Product Info" name="productInfo">
            </div>
            <div class="addProduct-submit">
                <input type="submit" VALUE="Add Product" name="addProduct">
            </div>
        </form>
    </div>
    <div class="productList">
        <?php
            $sql = "SELECT * FROM product";
            $productRes = $conn -> query($sql);

            if($productRes->num_rows > 0) {
                while($row = $productRes->fetch_assoc()) {
                    $product_id = $row['product_id'];
                    $product_name = $row['product_name'];
                    $stock = $row['stock'];
                    $price = $row['price'];
                    $img = "img/" . $product_name . ".png";
                    ?>
                    <div class="adminProduct">
                        <a href="extrasidor.php?id=<?php echo $product_id; ?>" style="color: black; text-decoration: none;"><img src="<?php echo $img?>" height="100px" width="100px"><br>
                            <strong><?php echo $product_name ; ?></a></strong><br>
                        <?php echo $price . "kr" ?> <br> <small> <?php echo "Stock: " .  $stock; ?></small>
                        <?php if($stock > 0): ?>
                            <form method="post" action="adminSettings.php">
                                <input type="text" name="productname" placeholder="<?php echo $product_name ; ?>">
                                <input type="text" name="productprice" placeholder="<?php echo $price ; ?>">
                                <input type="text" name="productstock" placeholder="<?php echo $stock ; ?>">
                                <input type="text" name="productinfo" placeholder="<?php echo $productInfo ; ?>">
                                <input type="hidden" name="id" id="id" value="<?php echo $product_id; ?>"/>
                                <input type="submit" name="save" class="button" value="Save" />
                            </form>
                        <?php else: ?>
                            <button class="button" disabled>Out of stock</button>
                        <?php endif; ?>
                    </div>
                    <?php
                }
            }

            if(isset($_GET["save"])) {
                $save_name = $_POST["productname"];
                $save_price = floatval($_POST["productprice"]);
                $save_stock = intval($_POST["productstock"]);
                $save_info = $_POST["productinfo"];

                $errors = array();

                if(!is_float($save_price)) {
                    print_r($save_price);
                    array_push($errors, "Input must be a number");
                }
                if(!is_int($save_stock)) {
                    print_r($save_stock);
                    array_push($errors, "Input must be a number");
                }
                if (count($errors) > 0) {
                    foreach ($errors as $error) {
                        echo "<div>$error</div>";
                    }
                }else {
                    if(!empty($save_name)) {
                        $sql = "UPDATE product SET product  (product_name) VALUES (?,?,?,?)";
                        $stmt = $conn -> stmt_init();
                        $prepareStmt = $stmt -> prepare($sql);
                        if ($prepareStmt) {
                            $stmt -> bind_param("sdis",$productName,$price, $stock, $productInfo);
                            $stmt -> execute();
                            echo "<div>Product added successfully</div>";
                        }else{
                            die("Something went wrong");
                        }
                    }
                }
            }
        ?>
    </div>
</body>
</html>