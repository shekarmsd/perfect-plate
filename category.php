<?php
session_start();

try {

    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $cart = new MongoDB\Driver\Query([]);
    $cart_out = $mng->executeQuery("PerfectPlate.cart", $cart);
    foreach($cart_out as $count){
        $cart_id = $count->Recipe;
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}
?>




<!doctype html>
<html class="no-js" lang="en">

<?php require "include/head.php" ?>

<body>

    <?php $page = 'category'; require "include/navbar.php" ?>

    <?php require "include/products.php" ?>

    <br/>

    <?php require "include/footer.php" ?>

</body>

</html>