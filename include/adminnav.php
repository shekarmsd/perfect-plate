<!--? Preloader Start -->
<!-- Preloader Start -->
<header>
    <!-- Header Start -->
    <div class="header-area">
        <div class="main-header header-sticky">
            <div class="container-fluid">
                <div class="menu-wrapper">
                    <!-- Logo -->
                    <div class="logo">
                        <a href="admin.php" style="font-size: x-large;"><span style="color: black; ">Perfect</span>&nbsp;<span style="color: green;">Plate</span></a>
                    </div>
                    <!-- Main-menu -->
                    <!-- Header Right -->
                    <?php if (isset($_SESSION['is_admin'])) { ?>
                        <div class="main-menu d-none d-lg-block">
                            <ul>
                                <li><a href="admin.php">Dashboard</a></li>
                                <li><a href="total_items.php">Recipes</a></li>
                                <li><a href="logout.php">Signout</a></li>
                            </ul>
                        </div>
                    <?php } else { ?>
                        <div class="main-menu d-none d-lg-block">
                            <ul>
                                <li><a href="admin_login.php">Signin</a></li>
                            </ul>
                        </div>
                    <?php } ?>
                </div>

                <!-- Mobile Menu -->
                <div class="col-12">
                    <div class="mobile_menu d-block d-lg-none"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->
</header>