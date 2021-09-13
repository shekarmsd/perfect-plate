<!--? Preloader Start -->
<div id="preloader-active">
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
            <div class="preloader-circle"></div>
            <div class="preloader-img pere-text">
                <span>Perfect</span>&nbsp;<span style="color: green;">Plate</span>
            </div>
        </div>
    </div>
</div>
<!-- Preloader Start -->
<header>
    <!-- Header Start -->
    <div class="header-area">
        <div class="main-header header-sticky">
            <div class="container-fluid">
                <div class="menu-wrapper">
                    <!-- Logo -->
                    <div class="logo">
                        <a href="index.php" style="font-size: x-large;"><span style="color: black; ">Perfect</span>&nbsp;<span style="color: green;">Plate</span></a>
                    </div>
                    <!-- Main-menu -->
                    <?php if (isset($_SESSION['is_login'])) { ?>
                        <?php
                        try {
                            $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                            $filter = [
                                'Customer_Id' => $_SESSION['cus_id']
                            ];
                            $query = new MongoDB\Driver\Query([]);
                            $query1 = new MongoDB\Driver\Query($filter);
                        
                            $rows = $mng->executeQuery("PerfectPlate.users", $query);
                            $chefs = $mng->executeQuery("PerfectPlate.chef", $query);
                            $orders = $mng->executeQuery("PerfectPlate.orders", $query1);
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
                        
                            $ans2 = current($orders->toArray());
                            if (!empty($ans2)) {
                                $order = $ans2;
                                $_SESSION['orders'] = $order;
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
                        $err_msg = "";
                        $count = "";

                        //connection
                        try {
                            $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                            $filter = [
                                'Customer_Id' => $_SESSION['cus_id']
                            ];
                            $query = new MongoDB\Driver\Query($filter);
                            $rows = $mng->executeQuery("PerfectPlate.cart", $query);
                            $ans = count($rows->toArray());
                            $_SESSION['count'] = $ans;
                            if (!empty($ans)) {
                                $count = $ans;
                            } else {
                                $err_msg = "0";
                            }
                        } catch (MongoConnectionException $e) {
                            die('Error connecting to MongoDB server');
                        } catch (MongoException $e) {
                            die('Error: ' . $e->getMessage());
                        }

                        ?>

                        <div class="main-menu d-none d-lg-block">
                            <nav>
                                <ul id="navigation">
                                    <li><a href="index.php" <?php if ($page == 'index') {
                                                                echo "style='color: red;'";
                                                            } ?>>Home</a></li>
                                    <li><a href="category.php" <?php if ($page == 'category') {
                                                                    echo "style='color: red;'";
                                                                } ?>>Menu</a></li>
                                    <li><a href="#" <?php if ($page == 'about') {
                                                                echo "style='color: red;'";
                                                            } ?>>about</a></li>
                                    <li><a href="contact.php" <?php if ($page == 'contact') {
                                                                    echo "style='color: red;'";
                                                                } ?>>Contact</a></li>
                                </ul>
                            </nav>
                        </div>
                        <!-- Header Right -->
                        <div class="main-menu d-none d-lg-block">
                            <ul>
                                <li>
                                    <a href="cart.php" title="My Cart" <?php if ($page == 'cart') {
                                                                echo "style='color: red;'";
                                                            } ?>>
                                        <span class="flaticon-shopping-cart"></span>
                                        <span class="badge badge-light" style="font-size: large; background-color: #fff; color: #FF2020;">
                                            <?php echo $count;
                                            echo $err_msg; ?>
                                        </span>
                                    </a>
                                </li>
                                <li> <a href="profile.php" title="View Profile" <?php if ($page == 'profile') {
                                                                                    echo "style='color: red;'";
                                                                                } ?>><span class="flaticon-user">&nbsp; <?php echo ($_SESSION['name']); ?></span></a>
                                    <ul class="submenu">
                                        <li><a href="myorders.php" title="Signout">My Order</a></li>
                                        <li><a href="logout.php" title="Signout">Signout</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                    <?php } else { ?>

                        <div class="main-menu d-none d-lg-block">
                            <nav>
                                <ul id="navigation">
                                    <li><a href="index.php" <?php if ($page == 'index') {
                                                                echo "style='color: red;'";
                                                            } ?>>Home</a></li>
                                    <li><a href="category.php" <?php if ($page == 'category') {
                                                                    echo "style='color: red;'";
                                                                } ?>>Menu</a></li>
                                    <li><a href="#" <?php if ($page == 'about') {
                                                                echo "style='color: red;'";
                                                            } ?>>about</a></li>
                                    <li><a href="contact.php" <?php if ($page == 'contact') {
                                                                    echo "style='color: red;'";
                                                                } ?>>Contact</a></li>
                                </ul>
                            </nav>
                        </div>
                        <!-- Header Right -->
                        <div class="main-menu d-none d-lg-block">
                            <ul>
                                <li>
                                    <a href="cart.php" title="Cart" <?php if ($page == 'cart') {
                                                                echo "style='color: red;'";
                                                            } ?>>
                                        <span class="flaticon-shopping-cart"></span>
                                        <span class="badge badge-light" style="font-size: large; background-color: #fff; color: #FF2020;"></span>
                                    </a>
                                </li>
                                <li> <a href="profile.php" title="View Profile"><span class="flaticon-user"></span></a>
                                    <ul class="submenu">
                                        <li><a href="login.php" title="Signout">Signin</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                </div>

            <?php } ?>

            <!-- Mobile Menu -->
            <div class="col-12">
                <div class="mobile_menu d-block d-lg-none"></div>
            </div>
            </div>
        </div>
    </div>
    <!-- Header End -->
</header>