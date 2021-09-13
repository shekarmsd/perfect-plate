<?php

session_start();
$name = $_SESSION['name'];

if (!isset($_SESSION["is_cheflogin"]) || $_SESSION["is_cheflogin"] !== true) {
    header("location: cooklogin.php");
    exit;
}
$amts = 0;
try {
    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $cart = new MongoDB\Driver\Query([]);
    $filter = ['Chef_Id' => $_SESSION['chef_id']];
    $cart1 = new MongoDB\Driver\Query($filter);
    $rows = $mng->executeQuery("PerfectPlate.chef_orders", $cart);
    $tot = $mng->executeQuery("PerfectPlate.chef_orders", $cart1);
    $item_count = count($tot->toArray());
    foreach($rows as $amt){
        $amts = $amts + ($amt->Total_Amount);
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}

$enrs = (80/100)*$amts;

?>


<!doctype html>
<html class="no-js" lang="en">

<?php require "include/head.php" ?>

<body>
    <?php require "include/chef_navbar.php" ?>

    <main>

        <?php if (!empty($item_count)) { ?>
            <section class="new-product-area" style="background-color: #F6F6F6;">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-7 col-lg-8 col-md-10">
                            <div class="section-tittle mb-70 text-center"><br /><br />
                                <!-- <h3 style="color: red;">Active Orders</h3> -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-header">My Earning's</h4>&nbsp;
                                    <h6 class="card-title">Price Details:</h6>
                                    <h6 class="card-title text-center">Total Recipes = <span style="color: red;"><?=$item_count?></span></h6>
                                    <h6 class="card-title text-center">Recipe MRP = ₹ <span style="color: red;"><?=$amts?></span></h6>
                                    <h6 class="card-title text-center">Your Earning's 80% of <?=$amts;?> = ₹ <span style="color: red;"><?= $enrs;?></span></h6>
                                    <a href="#" class="card-link">Card link</a>
                                    <a href="#" class="card-link">Another link</a>
                                </div>
                            </div>
                        </div>
                        <div class="col col-6">
                            <?php
                            try {
                                $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                                $filter = ['Chef_Id' => $_SESSION['chef_id']];
                                $cart = new MongoDB\Driver\Query($filter);
                                $rows = $mng->executeQuery("PerfectPlate.chef_orders", $cart);
                                foreach ($rows as $row) {
                                    $oneim = $row->Recipe_Image;
                                    $item_id = $row->_id;
                            ?>
                                    <div class="card-group">
                                        <div class="card">

                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col">
                                                    <form method="POST" action="chef_update.php">
                                                            <div class="default-select" id="default-select">
                                                                <select id="update" name="update" required>
                                                                    <option value="Delivered">Delivery</option>
                                                                </select>
                                                            </div>&nbsp;
                                                            <h6>Ordred By:</h6>
                                                            <h6>Phone:</h6>
                                                            <h6>Updated:</h6>
                                                        </div>
                                                        <input type="hidden" id="item_id" name="item_id" value="<?php echo $row->Item_Id; ?>" />
                                                        <input type="hidden" id="earnings" name="earnings" value="<?php echo $enrs; ?>" />
                                                        <div class="col">
                                                            <div>
                                                                <button type="submit" id="update_item" name="update_item" style="padding: 19px 13px; border-radius: 4px; font-size: 10px; background: green;" class="btn btn-primary">Update Order</button>
                                                            </div>
                                                            </form>
                                                    &nbsp;
                                                        <h6 class="text-muted"><?php echo $row->Customer; ?></h6>
                                                        <h6 class="text-muted"><?php echo $row->Phone; ?></h6>
                                                        <h6 class="text-muted"><?php echo $row->DateTime->date; ?></h6>
                                                    </div>
                                                </div>&nbsp;

                                                <div class="row">
                                                    <div class="col-8">
                                                        <h5 class="card-title"><?php echo $row->Recipe; ?></h5>
                                                    </div>

                                                    <div class="col" style="text-align: right;">
                                                        <h6 style="color: red;">New</h6>
                                                    </div>
                                                </div>

                                                <img class="card-img-top" src="data:jpeg;base64,<?= base64_encode($oneim->cover->getData()) ?>" alt="Card image cap">
                                                &nbsp;

                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } catch (MongoDB\Driver\Exception\Exception $e) {
                                echo "Exception:", $e->getMessage();
                            }
                            ?>
                        </div>
                    </div>

                </div><br />
                </div>
            </section>
        <?php } else { ?>
            <section class="subscribe_part section_padding">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="subscribe_part_content">
                                <p><i class="fas fa-shopping-bag fa-7x"></i></p>
                                <h2>You have not take up any orders!</h2>
                                &nbsp;&nbsp;&nbsp;<a href="cookdboard.php"><button type="button" class="btn btn-secondary btn-lg">Active orders</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php } ?>


    </main>

    <?php require "include/footer.php" ?>
</body>

</html>