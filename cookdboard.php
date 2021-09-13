<?php

session_start();
$name = $_SESSION['name'];

if (!isset($_SESSION["is_cheflogin"]) || $_SESSION["is_cheflogin"] !== true) {
    header("location: cooklogin.php");
    exit;
}

try {
    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $cart = new MongoDB\Driver\Query([]);
    $rows = $mng->executeQuery("PerfectPlate.items", $cart);
    $item_count = count($rows->toArray());
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}

?>


<!doctype html>
<html class="no-js" lang="en">

<?php require "include/head.php" ?>

<body>
    <?php require "include/chef_navbar.php" ?>

    <main>

        <?php if (!empty($item_count)) { ?>
            <section class="new-product-area">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-7 col-lg-8 col-md-10">
                            <div class="section-tittle mb-70 text-center"><br /><br />
                                <h3 style="color: red;">Active Orders</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        try {
                            $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                            $filter = [];
                            $options = ['sort' => ['_id' => -1]];
                            $cart = new MongoDB\Driver\Query($filter, $options);
                            $rows = $mng->executeQuery("PerfectPlate.Customer_Orders", $cart);
                            foreach ($rows as $row) {
                                $oneim = $row->Recipe_Image;
                                $price = $row->Total_Amount;
                                $status = $row->Status;
                        ?>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                                    <div class="card-group" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s;">
                                        <div class="card">

                                            <img class="card-img-top" src="data:jpeg;base64,<?= base64_encode($oneim->cover->getData()) ?>" alt="Card image cap">

                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h5 class="card-title"><?php echo $row->Recipe; ?></h5>
                                                    </div>

                                                    <div class="col" style="text-align: right;">
                                                        <h6 style="color: red;">New</h6>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <h6>Ordred By:</h6> 
                                                        <h6>Phone:</h6>
                                                        <h6>Quantity:</h6>
                                                        <h6>Updated:</h6>
                                                    </div>
                                                    <div class="col-8">
                                                        <h6 class="text-muted"><?php echo $row->Customer; ?></h6>
                                                        <h6 class="text-muted"><?php echo $row->Phone; ?></h6>
                                                        <h6 class="text-muted"><?php echo $row->Quantity; ?></h6>
                                                        <h6 class="text-muted"><?php echo $row->DateTime->date; ?></h6>
                                                    </div>
                                                </div>
                                                &nbsp;
                                                <h6 class="card-header text-center">Your Earning's 80% of <?=$price;?> = ₹ <?= (80/100)*$price;?></h6>
                                                   &nbsp;
                                                <div style="text-align: center;">
                                                    <?php if($status == "Order Placed") { ?>
                                                        <a href="take_order.php?id=<?= $row->Item_Id ?>" class="genric-btn info-border radius">Take Order</a>
                                                    <?php } else {?>
                                                        <h5 style="color: green;">Processing...</h5>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div><br />
                                </div>
                        <?php
                            }
                        } catch (MongoDB\Driver\Exception\Exception $e) {
                            echo "Exception:", $e->getMessage();
                        }
                        ?>
                    </div><br />
                </div>
            </section>
        <?php } else { ?>
            <section class="subscribe_part section_padding" style="padding-bottom: 200px;">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="subscribe_part_content">
                                <p><i class="fas fa-drumstick-bite fa-7x"></i></p>
                                <h2>STAY TUNED!</h2>
                                <p>Order's yet to come.</p>
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