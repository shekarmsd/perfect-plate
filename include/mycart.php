<main>
    <?php if (isset($_SESSION['is_login'])) { ?>
        <!--================Cart Area =================-->
        <?php if (!empty($count)) { ?>
            <section class="cart_area" style="padding-top: 60px; padding-bottom: 250px;">
                <div class="container">
                    <?php if (!empty($_SESSION["clr"])) { ?>
                        <div class="alert alert-info">
                            <a href="admin.php" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Hey!</strong> <?php echo $_SESSION["clr"]; ?>
                        </div>
                    <?php }
                    unset($_SESSION["clr"]); ?>
                    <div class="row">
                        <div class="col col-9">
                            <div class="cart_inner">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Recipe</th>
                                                <th scope="col">Category</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Price</th>
                                                <th scope="col"><a href="category.php" style="color: green;"><span class="fas fa-plus-circle"></span> Add More</a></th>
                                            </tr>
                                        </thead>
                                        <tr>

                                            <?php
                                            $amt = 0;
                                            try {
                                                $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                                                $filter = [
                                                    'Customer_Id' => $_SESSION['cus_id']
                                                ];
                                                $query = new MongoDB\Driver\Query($filter);
                                                $rows = $mng->executeQuery("PerfectPlate.cart", $query);

                                                foreach ($rows as $row) {
                                                    $oneim = $row->Recipe_Image;
                                                    $amt = $amt + ($row->Price);
                                                    $id = $row->_id;


                                            ?>
                                                    <td>
                                                        <div class="media">
                                                            <div class="d-flex">
                                                                <img src="data:jpeg;base64,<?= base64_encode($oneim->cover->getData()) ?>" alt="" />
                                                            </div>
                                                            <div class="media-body">
                                                                <h5><?php echo $row->Recipe; ?></h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h5><?php echo $row->Category; ?></h5>
                                                    </td>

                                                    <td>
                                                        <form id="frm<?php echo $id; ?>" method="POST">
                                                            <input type="hidden" name="cart_id" value="<?php echo $id; ?>">
                                                            <input type="number" class="form-control" name="qty" value="<?php echo $row->Quantity; ?>" onchange="updcart(<?php echo $id; ?>)" onkeyup="updcart(<?php $id; ?>)" min="1" max="4" style="width: 60px;">
                                                        </form>
                                                        <h5></h5>
                                                    </td>
                                                    <td>
                                                        <h5>&#8377;
                                                            <?php
                                                            $Price = $row->Price;
                                                            echo $Price;
                                                            ?>
                                                        </h5>
                                                    </td>
                                                    <td>
                                                        <a href="cartdelete.php?id=<?php echo $row->_id; ?>" class="genric-btn info radius">Clear</a>
                                                    </td>
                                                    </tbody>

                                            <?php
                                                }
                                                echo '</table>';
                                            } catch (MongoDB\Driver\Exception\Exception $e) {
                                                echo "Exception:", $e->getMessage();
                                            }
                                            ?>
                                        </tr>
                                </div>
                            </div>
                        </div>
                        <?php

                        $crgs = 140;
                        $_SESSION['amt'] = $amt;
                        $_SESSION['total'] = $amt + $crgs;
                        ?>

                        <div class="col col-3">
                            <div class="card text-center" style="background-color: white;">
                                <div class="card-body">
                                    <div style="border-bottom: 0.5px solid lightgray;">
                                        <h4>Price Details</h4>
                                        <h5>Iteams - <strong style="color: red;"><?php echo $count; ?></strong></h5>
                                    </div><br />
                                    <div class="row">
                                        <div class="col" style="text-align: left; border-bottom: 0.5px solid lightgray;">
                                            <h6>Total MRP</h6>
                                            <h6>Discount</h6>
                                            <h6>GST</h6>
                                            <h6>Delivery Charges</h6>
                                        </div>
                                        <div class="col" style="text-align: right; border-bottom: 0.5px solid lightgray;">
                                            <h6>&#8377; <?php echo $amt; ?></h6>
                                            <h6>&#8377; 0.00</h6>
                                            <h6>&#8377; 0.00</h6>
                                            <h6>&#8377; 140</h6>
                                        </div>
                                    </div>&nbsp;
                                    <div>
                                        <div class="row">
                                            <div class="col" style="text-align: left;">
                                                <h6>Total Amount</h6>
                                            </div>
                                            <div class="col" style="text-align: right;">
                                                <h6>&#8377; <?php echo $_SESSION['total']; ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="border-top: 0.5px solid lightgray;"><br />
                                        <a href="place_order.php?id=<?php echo $_SESSION['item_id']; ?>" class="genric-btn success radius">PROCEED TO CHECKOUT</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php } else { ?>

            <section class="subscribe_part section_padding">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="subscribe_part_content">
                                <p><i class="fas fa-shopping-cart fa-7x"></i></p>
                                <h2>Hey, it feels so light!</h2>
                                <p>There is nothing in your cart, Let's add some recipes.</p>
                                &nbsp;&nbsp;&nbsp;<a href="category.php"><button type="button" class="btn btn-secondary btn-lg">Continue Shopping</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>



        <?php } ?>
    <?php } else {
        header("Location: login.html");
    } ?>
    <!--================End Cart Area =================-->


    <!--================ Place Order Open =================-->






</main>