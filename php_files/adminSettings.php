<?php

session_start();
include("connect.php");
$cart_id = $_SESSION['cart_id'];
$path = "Location:adminSettings.php";
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
    <div class="flex-container">
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
                            <form method="post" action="">
                                <input type="text" id="proname" name="saveproductname" placeholder="Update name">
                                <label for="proname"><?php echo $product_name ; ?></label><br>
                                <input type="text" id="proprice" name="saveproductprice" placeholder="Update price">
                                <label for="proprice"><?php echo $price ; ?></label><br>
                                <input type="text" id="prostock" name="saveproductstock" placeholder="Update stock">
                                <label for="prostock"><?php echo $stock ; ?></label><br>
                                <input type="text" name="saveproductinfo" placeholder="Product info">
                                <input type="hidden" name="id" id="id" value="<?php echo $product_id; ?>"/>
                                <input type="submit" name="save" class="button" value="Save" />
                            </form>
                        </div>
                        <?php
                    }
                }

                if (isset($_POST["save"])) {
                    $save_id = $_POST["id"];
                    $save_name = $_POST["saveproductname"];
                    $save_price = floatval($_POST["saveproductprice"]);
                    $save_stock = intval($_POST["saveproductstock"]);
                    $save_info = $_POST["saveproductinfo"];

                    $errors = array();

                    if (!is_float($save_price)) {
                        print_r($save_price);
                        array_push($errors, "Input must be a number");
                    }
                    if (!is_int($save_stock)) {
                        print_r($save_stock);
                        array_push($errors, "Input must be a number");
                    }
                    if (count($errors) > 0) {
                        foreach ($errors as $error) {
                            echo "<div>$error</div>";
                        }
                    } else {
                        if (!empty($save_name)) {
                            $sql = "UPDATE product SET product_name=? WHERE product_id=?";
                            $stmt = $conn->stmt_init();
                            $prepareStmt = $stmt->prepare($sql);
                            if ($prepareStmt) {
                                $stmt->bind_param("si", $save_name, $save_id);
                                $stmt->execute();
                                echo "<div>Product updated successfully</div>";
                            } else {
                                die("Something went wrong");
                            }
                        }
                        if (!empty($save_price)) {
                            $sql = "UPDATE product SET price=? WHERE product_id=?";
                            $stmt = $conn->stmt_init();
                            $prepareStmt = $stmt->prepare($sql);
                            if ($prepareStmt) {
                                $stmt->bind_param("di", $save_price, $save_id);
                                $stmt->execute();
                                echo "<div>Product updated successfully</div>";
                            } else {
                                die("Something went wrong");
                            }
                        }
                        if (!empty($save_stock)) {
                            $sql = "UPDATE product SET stock=? WHERE product_id=?";
                            $stmt = $conn->stmt_init();
                            $prepareStmt = $stmt->prepare($sql);
                            if ($prepareStmt) {
                                $stmt->bind_param("ii", $save_stock, $save_id);
                                $stmt->execute();
                                echo "<div>Product updated successfully</div>";
                            } else {
                                die("Something went wrong");
                            }
                        }
                        if (!empty($save_info)) {
                            $sql = "UPDATE product SET product_info=? WHERE product_id=?";
                            $stmt = $conn->stmt_init();
                            $prepareStmt = $stmt->prepare($sql);
                            if ($prepareStmt) {
                                $stmt->bind_param("si", $save_info, $save_id);
                                $stmt->execute();
                                echo "<div>Product updated successfully</div>";
                            } else {
                                die("Something went wrong");
                            }
                        }
                    }
                }
            ?>
        </div>
        <div class="UserList">
            <?php
            $sql = "SELECT * FROM User";
            $userRes = $conn -> query($sql);

            if($userRes->num_rows > 0) {
                while($row = $userRes->fetch_assoc()) {
                    $user_id = $row['userId'];
                    $first_name = $row['firstName'];
                    $last_name = $row['lastName'];
                    $email = $row['email'];
                    $number = $row['phoneNumber'];
                    $user_type = $row['userType']
                    ?>
                    <div class="adminUsers">
                        <strong><?php echo $user_id ; ?></strong>
                        <form method="post" action="">
                            <input type="text" name="savefirstname" placeholder="<?php echo $first_name ; ?>">
                            <input type="text" name="savelastname" placeholder="<?php echo $last_name ; ?>">
                            <input type="text" name="saveemail" placeholder="<?php echo $email ; ?>">
                            <input type="text" name="savephonenumber" placeholder="<?php echo $number ; ?>">
                            <input type="checkbox" name="saveusertype" value="<?php echo $user_type ; ?>" <?php if ($user_type == 1) echo "checked='checked'"; ?>>
                            <input type="hidden" name="usertype" id="id" value="<?php echo $user_type; ?>"/>
                            <input type="hidden" name="user_id" id="id" value="<?php echo $user_id; ?>"/>
                            <input type="submit" name="confirm_changes" class="button" value="Save" />
                        </form>
                    </div>
                    <?php
                }
            }

            if (isset($_POST["confirm_changes"])) {
                $user_id = $_POST["user_id"];
                $save_first_name = $_POST["savefirstname"];
                $save_last_name = $_POST["savelastname"];
                $save_email = $_POST["saveemail"];
                $save_number = $_POST["savephonenumber"];
                $save_type = $_POST["usertype"];

                $errors = array();

                if (!is_float($save_number)) {
                    print_r($save_number);
                    array_push($errors, "Input must be a number");
                }
                if (count($errors) > 0) {
                    foreach ($errors as $error) {
                        echo "<div>$error</div>";
                    }
                } else {
                    if (!empty($save_first_name)) {
                        $sql = "UPDATE User SET firstName=? WHERE userId=?";
                        $stmt = $conn->stmt_init();
                        $prepareStmt = $stmt->prepare($sql);
                        if ($prepareStmt) {
                            $stmt->bind_param("si", $save_first_name, $user_id);
                            $stmt->execute();
                            echo "<div>User updated successfully</div>";
                        } else {
                            die("Something went wrong");
                        }
                    }
                    if (!empty($save_last_name)) {
                        $sql = "UPDATE User SET lastName=? WHERE userId=?";
                        $stmt = $conn->stmt_init();
                        $prepareStmt = $stmt->prepare($sql);
                        if ($prepareStmt) {
                            $stmt->bind_param("si", $save_last_name, $user_id);
                            $stmt->execute();
                            echo "<div>User updated successfully</div>";
                        } else {
                            die("Something went wrong");
                        }
                    }
                    if (!empty($save_email)) {
                        $sql = "UPDATE User SET email=? WHERE userId=?";
                        $stmt = $conn->stmt_init();
                        $prepareStmt = $stmt->prepare($sql);
                        if ($prepareStmt) {
                            $stmt->bind_param("si", $save_email, $user_id);
                            $stmt->execute();
                            echo "<div>User updated successfully</div>";
                        } else {
                            die("Something went wrong");
                        }
                    }
                    if (!empty($save_number)) {
                        $sql = "UPDATE User SET phoneNUmber=? WHERE userId=?";
                        $stmt = $conn->stmt_init();
                        $prepareStmt = $stmt->prepare($sql);
                        if ($prepareStmt) {
                            $stmt->bind_param("ii", $save_number, $user_id);
                            $stmt->execute();
                            echo "<div>User updated successfully</div>";
                        } else {
                            die("Something went wrong");
                        }
                    }
                    if (isset($_POST['saveusertype'])) {
                        if($save_type == 0) {
                            $temp_user_type = 1;
                            $sql = "UPDATE User SET userType=? WHERE userId=?";
                            $stmt = $conn->stmt_init();

                            $prepareStmt = $stmt->prepare($sql);
                            if ($prepareStmt) {
                                $stmt->bind_param("ii", $temp_user_type, $user_id);
                                $stmt->execute();
                                echo "<div>User updated successfully</div>";
                            } else {
                                die("Something went wrong");
                            }
                        }
                    }else {
                        if($save_type == 1) {
                            $temp_user_type = 0;
                            $sql = "UPDATE User SET userType=? WHERE userId=?";
                            $stmt = $conn->stmt_init();

                            $prepareStmt = $stmt->prepare($sql);
                            if ($prepareStmt) {
                                $stmt->bind_param("ii", $temp_user_type, $user_id);
                                $stmt->execute();
                                echo "<div>User updated successfully</div>";
                            } else {
                                die("Something went wrong");
                            }
                        }
                    }
                }
            }
            ?>
        </div>
    </div>
</body>
</html>