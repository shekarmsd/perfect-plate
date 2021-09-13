<main>

    <section class="container">
        <?php if (!empty($_SESSION["info_cart"])) { ?>
            <div class="alert alert-info">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Hey!</strong> <?php echo $_SESSION["info_cart"]; ?>
            </div>
        <?php }
        unset($_SESSION["info_cart"]); ?>
    </section>

    <!-- Latest Products Start -->
    <section class="popular-items latest-padding" style="padding: 50px 0;">
        <div class="container">
            <div class="row product-btn justify-content-between mb-40">
                <div class="properties__button">
                    <!--Nav Button  -->
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">South Indian</a>
                            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">North Indian</a>
                            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">West Indian</a>
                        </div>
                    </nav>
                    <!--End Nav Button  -->
                </div>
                <!-- Grid and List view -->
                <div class="grid-list-view">
                </div>
                <!-- Select items -->

            </div>
            <!-- Nav Card -->
            <div class="tab-content" id="nav-tabContent">
                <!-- card one -->
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="row">
                        <?php
                        try {

                            $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                            $filter = [
                                'Category' => "South Indian"
                            ];
                            $query = new MongoDB\Driver\Query($filter);
                            $rows = $mng->executeQuery("PerfectPlate.category", $query);

                            foreach ($rows as $row) {
                                $oneim = $row->cover;
                        ?>

                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                                    <div class="single-popular-items mb-50 text-center">
                                        <div class="popular-img">

                                            <img src="data:jpeg;base64,<?= base64_encode($oneim->cover->getData()) ?>" alt="">

                                            <div class="img-cap">
                                                
                                                <a href="addtocart.php?id=<?php echo $row->Recipe; ?>&cart_id=<?php if (!empty($_SESSION['count'])) {
                                                                                                                echo $cart_id;
                                                                                                            } else {
                                                                                                                echo '';
                                                                                                            } ?>&ord_id=<?php echo $row->_id; ?>"><span>Add to cart</span></a>
                                            </div>
                                            
                                            <div class="favorit-items">
                                                <span class="flaticon-heart"></span>
                                            </div>
                                        </div>
                                        <div class="popular-caption">
                                            <h3><?php echo $row->Recipe ?></h3>
                                            <span>&#x20B9; <?php echo $row->price ?></span>
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
                <!-- Card two -->
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div class="row">
                        <?php
                        try {

                            $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");

                            $filter = ['Category' => "North Indian"];

                            $query = new MongoDB\Driver\Query($filter);
                            $rows = $mng->executeQuery("PerfectPlate.category", $query);
                            foreach ($rows as $row) {

                                $oneim = $row->cover;
                        ?>

                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                                    <div class="single-popular-items mb-50 text-center">
                                        <div class="popular-img">


                                            <img src="data:jpeg;base64,<?= base64_encode($oneim->cover->getData()) ?>" alt="">

                                            <div class="img-cap">
                                                <a href="addtocart.php?id=<?php echo $row->Recipe; ?>&cart_id=<?php if (!empty($_SESSION['count'])) {
                                                                                                                echo $cart_id;
                                                                                                            } else {
                                                                                                                echo '';
                                                                                                            } ?>&ord_id=<?php echo $row->_id; ?>"><span>Add to cart</span></a>
                                            </div>
                                            <div class="favorit-items">
                                                <span class="flaticon-heart"></span>
                                            </div>
                                        </div>
                                        <div class="popular-caption">
                                            <h3><?php echo $row->Recipe ?></h3>
                                            <span>&#x20B9; <?php echo $row->price ?></span>
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
                <!-- Card three -->
                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <div class="row">
                        <?php
                        try {

                            $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");

                            $filter = ['Category' => "West Indian"];

                            $query = new MongoDB\Driver\Query($filter);
                            $rows = $mng->executeQuery("PerfectPlate.category", $query);
                            if (!empty($rows)) {
                                foreach ($rows as $row) {

                                    $oneim = $row->cover;
                        ?>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                                        <div class="single-popular-items mb-50 text-center">
                                            <div class="popular-img">


                                                <img src="data:jpeg;base64,<?= base64_encode($oneim->cover->getData()) ?>" alt="">

                                                <div class="img-cap">
                                                <a href="addtocart.php?id=<?php echo $row->Recipe; ?>&cart_id=<?php if (!empty($_SESSION['count'])) {
                                                                                                                echo $cart_id;
                                                                                                            } else {
                                                                                                                echo '';
                                                                                                            } ?>&ord_id=<?php echo $row->_id; ?>"><span>Add to cart</span></a>
                                            </div>
                                                <div class="favorit-items">
                                                    <span class="flaticon-heart"></span>
                                                </div>
                                            </div>
                                            <div class="popular-caption">
                                                <h3><?php echo $row->Recipe ?></h3>
                                                <span>&#x20B9; <?php echo $row->price ?></span>
                                            </div>
                                        </div>
                                    </div>

                        <?php

                                }
                            } else {

                                echo 'No iteams';
                            }
                        } catch (MongoDB\Driver\Exception\Exception $e) {
                            echo "Exception:", $e->getMessage();
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- End Nav Card -->
        </div>
    </section>
    <!-- Latest Products End -->
    <!--? Shop Method Start-->
    <div class="shop-method-area">
            <div class="container">
                <div class="method-wrapper">
                    <div class="row d-flex justify-content-between">
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="single-method mb-40">
                                <i class="ti-package"></i>
                                <h6>Better Shipping Method</h6>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="single-method mb-40">
                                <i class="ti-unlock"></i>
                                <h6>Secure Payment System</h6>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="single-method mb-40">
                                <i class="ti-reload"></i>
                                <h6>Healthy Home Made Food</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Shop Method End-->
</main>