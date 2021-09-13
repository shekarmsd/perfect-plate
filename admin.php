<?php
session_start();


if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    header("location: admin_login.php");
    exit;
}

$err_msg = $err_msg1 = $err_msg2 = $err_msg3 = "";
$count = $order = $chef = $name = $email = $catgry = "";

//connection
try {
    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");

    $query = new MongoDB\Driver\Query([]);

    $rows = $mng->executeQuery("PerfectPlate.users", $query);
    $chefs = $mng->executeQuery("PerfectPlate.chef", $query);
    $orders = $mng->executeQuery("PerfectPlate.Customer_Orders", $query);
    $catg = $mng->executeQuery("PerfectPlate.category", $query);

    $ans = count($rows->toArray());
    if (!empty($ans)) {
        $count = $ans;
    } else {
        $err_msg = "0";
    }

    $ans1 = count($chefs->toArray());
    if (!empty($ans1)) {
        $chef = $ans1;
    } else {
        $err_msg1 = "0";
    }

    $ans2 = count($orders->toArray());
    if (!empty($ans2)) {
        $order = $ans2;
        $_SESSION['orders'] = $ans2;
    } else {
        $err_msg2 = "0";
    }

    $ans3 = count($catg->toArray());
    if (!empty($ans3)) {
        $catgry = $ans3;
    } else {
        $err_msg3 = "0";
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}


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
    $order0 = new MongoDB\Driver\Query($filter);
    $order1 = new MongoDB\Driver\Query($filter1);
    $order2 = new MongoDB\Driver\Query($filter2);
    $rows0 = $mng->executeQuery("PerfectPlate.Customer_Orders", $order0);
    $rows1 = $mng->executeQuery("PerfectPlate.Customer_Orders", $order1);
    $rows2 = $mng->executeQuery("PerfectPlate.Customer_Orders", $order2);
    $placed = count($rows0->toArray());
    $process = count($rows1->toArray());
    $delivered = count($rows2->toArray());
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}

$tot = $placed + $process + $delivered;


?>




<!doctype html>
<html class="no-js" lang="en">

<?php require "include/head.php" ?>

<body>

    <?php $page = "index";
    require "include/adminnav.php" ?>
    <main>

        <section class="container">
            <?php if (!empty($_SESSION["info_msg"])) { ?>
                <div class="alert alert-success">
                    <a href="admin.php" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Success!</strong> <?php echo $_SESSION["info_msg"]; ?>
                </div>
            <?php }
            unset($_SESSION["info_msg"]); ?>
            <?php if (!empty($_SESSION["info_err"])) { ?>
                <div class="alert alert-danger">
                    <a href="admin.php" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Info!</strong> <?php echo $_SESSION["info_err"]; ?>
                </div>
            <?php }
            unset($_SESSION["info_err"]); ?>
            <?php if (!empty($_SESSION["delete_info"])) { ?>
                <div class="alert alert-danger">
                    <a href="admin.php" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Info!</strong> <?php echo $_SESSION["delete_info"]; ?>
                </div>
            <?php }
            unset($_SESSION["delete_info"]); ?>
        </section>

        <section class="confirmation_part section_padding" style="padding: 50px 0;">
            <div class="container">
                <div class="panel user" data-toggle="modal" data-target="#exampleModal">
                    <a href="#exampleModal"><span><?php echo $count; ?><?php echo $err_msg; ?> </span>Customers</a>
                </div>
                <div class="panel post" data-toggle="modal" data-target="#exampleModal1">
                    <a href="#"><span><?php echo $chef; ?><?php echo $err_msg1; ?> </span>Chef Members</a>
                </div>
                <div class="panel comment">
                    <a href="order_details.php"><span><?php echo $order; ?><?php echo $err_msg2; ?></span>Total Orders</a>
                </div>
                <div class="panel page">
                    <a href="total_items.php" title="View Recipes"><span><?php echo $catgry; ?><?php echo $err_msg3; ?></span>Recipes</a>
                </div>

            </div>
        </section>

        <!-- Date Modals  -->
        <section>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Customer Details | <span style="color: red;"><?php echo $count; ?><?php echo $err_msg; ?></span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" title="Close">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php if ($count > 0) { ?>
                                <table class="table table-hover">
                                    <thead class="thead">
                                        <tr>
                                            <th scope="col col-md">Name</th>
                                            <th scope="col">Email Address</th>
                                            <th scope="col">Create Date</th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php
                                            try {
                                                $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");

                                                $query = new MongoDB\Driver\Query([]);

                                                $rows = $mng->executeQuery("PerfectPlate.users", $query);
                                                foreach ($rows as $row) {

                                                    $date = $row->DateTime;

                                                    echo "<tr>";

                                                    echo "<td>" . $row->Name . "</td>";
                                                    echo "<td>" . $row->Email . "</td>";
                                                    echo "<td>" . $date->date . "</td>";
                                            ?>
                                                    <td><a href="userdelete.php?id=<?php echo $row->_id; ?>" class="genric-btn danger-border radius">Delete</a></td>
                                            <?php
                                                    echo "</tr>";
                                                }
                                                echo "</tboday>";
                                                echo "</table>";
                                            } catch (MongoDB\Driver\Exception\Exception $e) {
                                                echo "Exception:", $e->getMessage();
                                            }

                                            ?>
                                        <?php } else { ?>
                                            <div class="card-title text-center">
                                                <h4>No Records</h4>
                                            </div>
                                        <?php }  ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Chef Details | <span style="color: red;"><?php echo $chef; ?><?php echo $err_msg1; ?></span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" title="Close">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php if ($chef > 0) { ?>
                                <table class="table table-hover">
                                    <thead class="thead">
                                        <tr>
                                            <th scope="col col-md">Chef Name</th>
                                            <th scope="col">Email Address</th>
                                            <th scope="col">Total Earnings</th>
                                            <th scope="col">Phone Number</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php
                                            try {
                                                $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");

                                                $query = new MongoDB\Driver\Query([]);

                                                $rows = $mng->executeQuery("PerfectPlate.chef", $query);
                                                foreach ($rows as $row) {

                                                    echo "<tr>";

                                                    echo "<td>" . $row->Name . "</td>";
                                                    echo "<td>" . $row->Email . "</td>";
                                                    echo "<td>₹ " . $row->Earnings . "</td>";
                                                    echo "<td>" . $row->Phone_Number . "</td>";
                                            ?>
                                                    <td><a href="chefdelete.php?id=<?php echo $row->_id; ?>" class="genric-btn danger-border radius">Delete</a></td>
                                            <?php
                                                    echo "</tr>";
                                                }
                                                echo "</tboday>";
                                                echo "</table>";
                                            } catch (MongoDB\Driver\Exception\Exception $e) {
                                                echo "Exception:", $e->getMessage();
                                            }

                                            ?>
                                        <?php } else { ?>
                                            <div class="card-title text-center">
                                                <h4>No Records</h4>
                                            </div>
                                        <?php }  ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- /End of Date Modals  -->

        <section style="padding-bottom: 130px;">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <canvas id="myChart"></canvas>
                    </div>
                    <div class="col-sm-6">
                        <canvas id="myChart1"></canvas>
                        <!-- <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Special title treatment</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="room-btn pt-70">
                        <a href="report.php" class="btn view-btn1">Perfect Plate Summery</a>
                    </div>
                </div>
            </div>
        </section>


        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
        <script>
            var ctx = document.getElementById("myChart");
            var data = {

                datasets: [{
                    data: [<?= $order; ?>, <?= $count ?>, <?= $catgry ?>, <?= $chef ?>],
                    backgroundColor: [
                        "#FF6384",
                        "#36A2EB",
                        "#FFCE56",
                        "#38A456"
                    ],
                    hoverBackgroundColor: [
                        "#FF4394",
                        "#36A2EB",
                        "#FFCE56",
                        "#38A456"
                    ]
                }],
                labels: [
                    "Orders",
                    "Customers",
                    "Recipes",
                    "Members",
                ]
            };
            var options = {
                cutoutPercentage: 40,
            };
            var myDoughnutChart = new Chart(ctx, {
                type: 'bar',
                data: data,
                options: options
            });
        </script>
        <script>
            var myChart = document.getElementById("myChart1");
            var data = {

                datasets: [{
                    data: [<?= $tot; ?>, <?= $process ?>, <?= $placed ?>, <?= $delivered ?>],
                    backgroundColor: [
                        "#D4F4EC",
                        "#FFD8C5",
                        "#FEA889",
                        "#70D0C6"
                    ],
                    hoverBackgroundColor: [
                        "#D4F4EC",
                        "#FFD8C5",
                        "#FEA889",
                        "#70D0C6"
                    ]
                }],
                labels: [
                    "Total Orders",
                    "Processing",
                    "Placed",
                    "Delivered",
                ]
            };
            var options = {
                cutoutPercentage: 40,
            };
            var myDoughnutChart = new Chart(myChart, {
                type: 'pie',
                data: data,
                options: options
            });
        </script>
    </main>


    <?php require "include/footer.php" ?>

</body>

</html>