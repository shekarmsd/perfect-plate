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


    <?php $page = 'index'; require "include/navbar.php" ?>

    <section class="container">
        <?php if (!empty($_SESSION["pro_info"])) { ?>
            <div class="alert alert-info">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Info!</strong> <?php echo $_SESSION["pro_info"]; ?>
            </div>
        <?php }
        unset($_SESSION["pro_info"]); ?>
        <?php if (!empty($_SESSION["order_info"])) { ?>
            <div class="alert alert-info">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Info!</strong> <?php echo $_SESSION["order_info"]; ?>
            </div>
        <?php }
        unset($_SESSION["order_info"]); ?>
    </section>

    <?php require "include/content.php" ?>
    

    <?php require "include/footer.php" ?>


</body>

</html>