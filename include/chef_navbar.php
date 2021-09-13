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
                      <?php if (isset($_SESSION['is_cheflogin'])) { ?>
                          <?php
                            $count = 0;
                            //connection
                            try {
                                $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                                $filter = [
                                    '_id' => $_SESSION['chef_id']
                                ];
                                $query = new MongoDB\Driver\Query($filter);
                                $rows = $mng->executeQuery("PerfectPlate.chef", $query);
                                foreach ($rows as $row) {
                                    $count = $count + ($row->Earnings);
                                }
                                $_SESSION['erns'] = $count;
                            } catch (MongoConnectionException $e) {
                                die('Error connecting to MongoDB server');
                            } catch (MongoException $e) {
                                die('Error: ' . $e->getMessage());
                            }

                            ?>
                          <div class="main-menu d-none d-lg-block">
                              <nav>
                                  <ul id="navigation">
                                      <li><a><span></span></a></li>
                                  </ul>
                              </nav>
                          </div>
                          <!-- Header Right -->
                          <div class="main-menu d-none d-lg-block">
                              <nav>
                                  <ul>
                                    <li><a href="cookdboard.php"><span>Home</span></a></li>
                                      <li><a href="#"><span>Earnings</span>
                                              <span class="badge badge-light" style="font-size: large; background-color: #fff; color: #FF2020;">
                                                  ₹ <?php echo $count; ?>
                                              </span></a></li>
                                      <li><a href="chef_orders.php"><span>|</span></a></li>
                                      <li><a href="chef_orders.php"><span>My Orders</span></a></li>
                                      <li> <a href="#" title="View Profile"><span class="flaticon-user"> <?= $_SESSION['name'] ?></span></a></li>
                                      <li><a href="logout.php"><span>Logout</span></a></li>
                              </nav>
                          </div>
                      <?php } else { ?>
                        <div class="main-menu d-none d-lg-block">
                              <nav>
                                  <ul id="navigation">
                                      <li><a><span></span></a></li>
                                  </ul>
                              </nav>
                          </div>
                          <!-- Header Right -->
                          <div class="main-menu d-none d-lg-block">
                              <nav>
                                  <ul>
                                      <li><a href="cooklogin.php"><span>Login</span></a></li>
                                  </ul>
                              </nav>
                          </div>
                      <?php } ?>
                  </div>
              </div>

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