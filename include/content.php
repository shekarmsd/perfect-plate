<main>
    <?php if (isset($_SESSION['is_login'])) { ?>
        <!--? Watch Choice  Start-->
        <div class="watch-area"><br />
            <div class="container">
                <div class="row align-items-center justify-content-between padding-130">
                    <div class="col-lg-5 col-md-6">
                        <div class="watch-details mb-40">
                            <h2>Food of Choice</h2>
                            <p>When I give food to the poor, they call me a saint. When I ask why the poor have no food, they call me a communist.<br><strong>― Dom Helder Camara</strong></p>
                            <a href="category.php" class="btn">Order Now</a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-10">
                        <div class="choice-watch-img mb-40">
                            <img src="assets/img/hero/img2.jpg" alt="" style="border-radius: 20px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Watch Choice  End-->


    <?php } else { ?>



        <div class="slider-area ">
            <div class="slider-active">
                <!-- Single Slider -->
                <div class="single-slider slider-height d-flex align-items-center slide-bg">
                    <div class="container">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
                                <div class="hero__caption">
                                    <h1 data-animation="fadeInLeft" data-delay=".4s" data-duration="2000ms">Order Your Perfect Plate</h1>
                                    <p data-animation="fadeInLeft" data-delay=".7s" data-duration="2000ms">Home cooked food is not that arrived from the window of your car!!</p>
                                    <!-- Hero-btn -->
                                    <div class="hero__btn" data-animation="fadeInLeft" data-delay=".8s" data-duration="2000ms">

                                        <a href="category.php" class="btn hero-btn">Order Now</a>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 d-none d-sm-block">
                                <div class="hero__img" data-animation="bounceIn" data-delay=".4s">
                                    <img src="assets/img/hero/img1.jpg" alt="" class=" heartbeat">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Single Slider -->
            </div>
        </div>

    <?php } ?>


    <!-- ? New Product Start -->
    <section class="new-product-area section-padding30">
        <div class="container">
            <!-- Section tittle -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="section-tittle mb-70">
                        <h2>Popular Recipes</h2>
                    </div>
                </div>
            </div>
            <div class="row">

                <?php
                try {
                    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                    $filter = [];
                    $options = ['sort' => ['_id' => -1], 'limit' => 6];
                    $query = new MongoDB\Driver\Query($filter, $options);
                    $cart = new MongoDB\Driver\Query([]);
                    $rows = $mng->executeQuery("PerfectPlate.category", $query);
                    $cart_out = $mng->executeQuery("PerfectPlate.cart", $cart);
                    $cout = current($cart_out->toArray());
                    // $items = count($rows->toArray());

                    // if (!empty($items)) {

                    foreach ($rows as $row) {

                        $oneim = $row->cover;

                ?>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                            <div class="single-new-pro mb-30 text-center">
                                <div class="product-img">
                                    <img src="data:jpeg;base64,<?= base64_encode($oneim->cover->getData()) ?>" alt="">
                                </div>
                                <div class="product-caption">
                                    <h3><?php echo $row->Recipe; ?></h3>
                                    <h4><span>&#x20B9; <?php echo $row->price; ?>/-</span></h4>

                                    <div class="hero__btn" data-animation="fadeInLeft" data-delay=".8s" data-duration="2000ms">
                                        <a href="addtocart.php?id=<?php echo $row->Recipe; ?>&cart_id=<?php if (!empty($_SESSION['count'])) {
                                                                                                                echo $cart_id;
                                                                                                            } else {
                                                                                                                echo '';
                                                                                                            } ?>&ord_id=<?php echo $row->_id; ?>" class="btn hero-btn">Add to Cart</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                <?php
                    }
                    // } else {

                    //     echo 'No Records';
                    // }
                } catch (MongoDB\Driver\Exception\Exception $e) {
                    echo "Exception:", $e->getMessage();
                }
                ?>
            </div>
            <div class="row justify-content-center">
                <div class="room-btn pt-70">
                    <a href="category.php" class="btn view-btn1">View More iteams</a>

                </div>
            </div>
        </div>

        </div>


    </section>

    <div class="gallery-area">
        <div class="container-fluid p-0 fix">
            <div class="row">
                <div class="col-xl-6 col-lg-4 col-md-6 col-sm-6">
                    <div class="single-gallery mb-30">
                        <div class="gallery-img big-img" style="background-image: url(assets/img/gallery/img1.jpg);"></div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                    <div class="single-gallery mb-30">
                        <div class="gallery-img big-img" style="background-image: url(assets/img/gallery/img2.jpg);"></div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-12">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-6 col-sm-6">
                            <div class="single-gallery mb-30">
                                <div class="gallery-img small-img" style="background-image: url(assets/img/gallery/img3.jpg);"></div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12  col-md-6 col-sm-6">
                            <div class="single-gallery mb-30">
                                <div class="gallery-img small-img" style="background-image: url(assets/img/gallery/img4.jpg);"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['is_login'])) {
    } else { ?>
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
    <?php } ?>


</main><br />