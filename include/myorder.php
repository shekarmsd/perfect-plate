<?php

try {

    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $filter = [
        'Customer_Id' => $_SESSION['cus_id']
    ];
    $order = new MongoDB\Driver\Query($filter);
    $rows = $mng->executeQuery("PerfectPlate.Customer_Orders", $order);
    $count = count($rows->toArray());
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}
?>

<section class="container">
    <?php if (!empty($_SESSION["order_info"])) { ?>
        <div class="alert alert-info">
            <a href="admin.php" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Info!</strong> <?php echo $_SESSION["order_info"]; ?>
        </div>
    <?php }
    unset($_SESSION["order_info"]); ?>
</section>

<?php if (!empty($count)) {

?>
    <section class="confirmation_part" style="padding-bottom: 110px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8 col-md-10">
                    <div class="section-tittle mb-70 text-center"><br /><br />
                        <h3 style="color: red;">My Orders</h3>
                    </div>
                </div>
            </div>

            <div class="row">

                <?php
                try {

                    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                    $filter = [
                        'Customer_Id' => $_SESSION['cus_id']
                    ];
                    $order = new MongoDB\Driver\Query($filter);     
                    $rows = $mng->executeQuery("PerfectPlate.Customer_Orders", $order);
                    foreach ($rows as $row) {
                        $status = $row->Status;
                ?>
                        <div class="col col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header" style="background-color: litestgray;">
                                        <div class="row">
                                            <div class="col">
                                                <h5>Order Info</h5>
                                            </div>
                                            <div class="col">
                                                <h6>Status: <strong style="color: green;"><?= $row->Status ?></strong></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6"><br />
                                            <h6>Order Number:</h6>
                                            <h6>Customer Name:</h6>
                                            <h6>Recipe:</h6>
                                            <h6>Phone:</h6>
                                            <h6>Quantity:</h6>
                                            <h6>Total Amount:</h6>
                                            <h6>Ordered Date:</h6>
                                        </div>
                                        <div class="col-6" ><br />
                                            <h6 style="color: gray;"><?php echo $row->_id; ?></h6>
                                            <h6 style="color: gray;"><?php echo $row->Customer; ?></h6>
                                            <h6 style="color: red;"><?php echo $row->Recipe; ?></h6>
                                            <h6 style="color: gray;"><?php echo $row->Phone; ?></h6>
                                            <h6 style="color: gray;"><?php echo $row->Quantity; ?></h6>
                                            <h6 style="color: red;">₹ <?php echo $row->Total_Amount; ?></h6>
                                            <h6 style="color: gray;"><?php echo $row->DateTime->date; ?></h6><br />
                                        </div>
                                    </div>
                                    
                                    <?php if ($status != "Order Placed") { ?>
                                    <?php } else { ?>
                                        <div class="card text-center">
                                            <a href="cancle_order.php?id=<?= $row->_id; ?>" class="genric-btn danger radius">Cancle Order</a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                <?php }
                } catch (MongoDB\Driver\Exception\Exception $e) {
                    echo "Exception:", $e->getMessage();
                } ?>
            </div>

        </div>

    </section>

<?php } else { ?>

    <section class="subscribe_part section_padding" style="padding-bottom: 250px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="subscribe_part_content">
                        <p><i class="fas fa-drumstick-bite fa-7x"></i></p>
                        <h2>Hey, you hav not made any orders yet!</h2>
                        <p>Feeling hungry...! Let's add some recipes.</p>
                        &nbsp;&nbsp;&nbsp;<a href="category.php"><button type="button" class="btn btn-secondary btn-lg">Go Eat</button></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php } ?>