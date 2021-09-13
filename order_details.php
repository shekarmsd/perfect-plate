<?php
session_start();

try {

    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $filter = [
        'Status' => "Order Placed"
    ];
    $filter1 = [
        'Status' => "Processing"
    ];
    $filter2 = [
        'Status' => "Delivered"
    ];
    $order = new MongoDB\Driver\Query($filter);
    $order1 = new MongoDB\Driver\Query($filter1);
    $order2 = new MongoDB\Driver\Query($filter2);
    $rows = $mng->executeQuery("PerfectPlate.Customer_Orders", $order);
    $rows1 = $mng->executeQuery("PerfectPlate.Customer_Orders", $order1);
    $rows2 = $mng->executeQuery("PerfectPlate.Customer_Orders", $order2);
    $placed = count($rows->toArray());
    $process = count($rows1->toArray());
    $delivered = count($rows2->toArray());
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}

$count = $placed + $process + $delivered;


?>
<!doctype html>
<html class="no-js" lang="en">

<?php require "include/head.php" ?>

<body>

    <?php $page = 'order_details';
    require "include/adminnav.php" ?>
    <?php if (!empty($count)) { ?>
        <section class="new-product-area" style="padding: 30px 0;">
            <div class="container">
                <div style="text-align: center;">
                    <h3>Customer Order Details</h3>
                </div>
            </div>
        </section>
        <section>
            <div class="container" style="padding-bottom: 250px; max-width: 1380px; ">
                <div class="row">
                    <div class="col col-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Customer Order Details</h5>
                                <canvas id="myChart"></canvas>
                            </div>
                        </div><br />
                    </div>
                    <div class="col col-8">
                        <table class="table table-hover table" style="font-size: 13px;">
                            <thead>
                                <tr class="bg-info" style="color: #fff;">
                                    <th scope="col">Customer</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Recipe</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">DateTime</th>
                                    <!-- <th scope="col">Pincode</th> -->
                                    <!-- <th scope="col">Amount</th>
                                <th scope="col">Payment Mode</th> -->
                                    <th scope="col">Status</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    try {
                                        $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                                        $order = new MongoDB\Driver\Query([]);
                                        $rows = $mng->executeQuery("PerfectPlate.Customer_Orders", $order);
                                        foreach ($rows as $row) {
                                            
                                    ?>

                                            <td><?php echo $row->Customer; ?></td>
                                            <td><?php echo $row->Phone; ?></td>
                                            <td><?php echo $row->Recipe; ?></td>
                                            <td><?php echo $row->Quantity; ?></td>
                                            <td><?php echo $row->Total_Amount; ?></td>
                                            <td><?php echo $row->DateTime->date;; ?></td>
                                            <td><?php echo $row->Status; ?></td>
                                            <td>
                                                <form method="POST" action="order_update.php">
                                                    <div class="default-select" id="default-select">
                                                        <select id="status" name="status" required>
                                                            <option value="Processing">Process</option>
                                                            <option value="Delivered">Delivery</option>
                                                            <option value="Order Placed">Place Order</option>
                                                        </select>
                                                    </div>
                                            </td>
                                            <td>
                                                <input type="hidden" id="order_id" name="order_id" value="<?php echo $row->Item_Id; ?>" />
                                            </td>
                                            <td>
                                                <div class="col">
                                                    <button type="submit" id="update_status" name="update_status" style="padding: 19px 13px; border-radius: 4px; font-size: 10px; background: gray;" class="btn btn-primary">Update</button>
                                                </div>
                                            </td>
                                            </form>
                                </tr>

                            <?php   } ?>
                            </tbody>
                        </table>
                    <?php
                                    } catch (MongoDB\Driver\Exception\Exception $e) {
                                        echo "Exception:", $e->getMessage();
                                    }
                    ?>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <form method="POST" action="">
                                <div class="form-row">
                                    <div class="col">
                                        <div class="default-select" id="default-select">
                                            <!-- <input id="ExpiryDate" name="ExpiryDate" class="form-control" type="date" required /></input> -->
                                            <select id="filtodr" name="filtodr" required>
                                                <option value="Placed Orders">Placed Orders</option>
                                                <option value="Processing">Processing</option>
                                                <option value="Delivered">Delivered</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <button type="submit" id="search" name="search" style="padding: 19px 13px; border-radius: 4px; font-size: 10px; background: black;" class="btn btn-primary">Search</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php } else { ?>

        <section class="subscribe_part section_padding" style="padding-bottom: 200px;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="subscribe_part_content">
                            <p><i class="fas fa-book fa-7x"></i></p>
                            <h2>No Orders Details Found!</h2>
                            <p>Get back later.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <?php } ?>



    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script>
        var ctx = document.getElementById("myChart");
        var data = {

            datasets: [{
                data: [<?= $placed ?>, <?= $process ?>, <?= $delivered ?>],
                backgroundColor: [
                    "#FF6384",
                    "#36A2EB",
                    "#FFCE56"
                ],
                hoverBackgroundColor: [
                    "#FF4394",
                    "#36A2EB",
                    "#FFCE56"
                ]
            }],
            labels: [
                "Orders Placed",
                "Processing",
                "Delivered"
            ]
        };
        var options = {
            cutoutPercentage: 40,
        };
        var myDoughnutChart = new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: options
        });
    </script>

    <?php require "include/footer.php" ?>

</body>

</html>