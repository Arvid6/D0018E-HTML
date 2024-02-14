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
    <title>Eden</title>
</head>

<body>
<header>
    <nav id="nav">
        <div id="logo">
            <!--<a href="index.html">
            <img src="images/logo.png" width="100%" alt="logo" id="bild">
            </a>-->
        </div>
        <ul>
            <li><a class="link" href="index.html">START</a></li>
            <li><a class="link" href="kontakt.html">KONTAKT</a></li>
        </ul>
    </nav>
</header>

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
            <?php
            session_start();
            // Query to get the products from database
            $sql = "SELECT * FROM Product";
            // Execute the query
            $res = $conn->query($sql);

            if($res->num_rows > 0) {

                while($row = $res->fetch_assoc()) {
                    $ProductName = $row['ProductName'];
                    $Stock = $row['Stock'];
                    $Price = $row['Price'];

                    ?>
                    <tr>
                        <td><?php echo $ProductName; ?></td>
                        <td><?php echo $Price; ?></td>
                        <td><?php echo $Stock; ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>

    </div>

    <div id="container">
        <div class="bilda">d
        </div>
        <div class="bilda">

        </div>
    </div>
</main>
<br>

<footer>
    <p>arvfal-0@student.ltu.se</p>
</footer>



<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script text="text/javscript" src="script.js"></script>
</body>
</html>






