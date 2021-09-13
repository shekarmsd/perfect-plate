<?php

$error_msg = "";

if (isset($_POST["submit"])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];


    try {

        $mng = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017");

        $filter = ['Email' => $email];
        $query = new MongoDB\Driver\Query($filter);
        $user = $mng->executeQuery('PerfectPlate.chef', $query);
        $out = current($user->toArray());

        if (!empty($out)) {

            $error_msg = 'Email is already exists! Try different one.';
        } else {

            $bulk = new MongoDB\Driver\BulkWrite;

            $users = ['Name' => $name, 'Phone_Number' => $phone, 'Email' => $email, 'Earnings' => '0', 'Password' => md5($password)];
            $bulk->insert($users);
            $mng->executeBulkWrite('PerfectPlate.chef', $bulk);

            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['chef_id'] = $out->_id;
            $_SESSION['name'] = $name;
            $_SESSION['is_cheflogin'] = true;

            header("Location: cookdboard.php");
        }
    } catch (MongoConnectionException $e) {
        die('Error connecting to MongoDB server');
    } catch (MongoException $e) {
        die('Error: ' . $e->getMessage());
    }
}


?>


<!doctype html>
<html lang="en">

<?php require "include/head.php" ?>

<body>
    <?php require "include/chef_navbar.php" ?>

    <main>
        <!--================login_part Area =================-->
        <section class="login_part section ">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6">
                        <div class="login_part_text text-center" style="background-image: url('assets/img/chef1.jpg'); border-radius: 27px;">
                            <div class="login_part_text_iner">
                                <br /><br /><br />
                                <h2>Alredy have an account?</h2>
                                <a href="cooklogin.php" class="btn_3">Sign In</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="login_part_form">
                            <div class="login_part_form_iner">
                                <h4></h4>
                                <h3>Welcome to <span>Perfect </span><span style="color: green;">Chef</span> <br>Join Now</h3>
                                <form name="myForm" class="row contact_form" action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST" onsubmit="return validateForm()">
                                    <div class="col-md-12 form-group p_star">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                                    </div>
                                    <div class="col-md-12 form-group p_star">
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Email Address">
                                    </div>
                                    <div class="col-md-12 form-group p_star">
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number">
                                    </div>
                                    <div class="col-md-12 form-group p_star">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                    </div>
                                    <div class="col-md-12 form-group p_star">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                                    </div>

                                    <div  class="col-md-12 form-group p_star">
                                        <p><span style="color: red;"><?php echo ($error_msg); ?></span></p>
                                        <h6>Already a memeber? <a href="cooklogin.php" style="color: green;">Login</a></h6>
                                    </div>

                                    <div class="col-md-12 form-group">
                                        <button type="submit" name="submit" value="submit" class="btn_3">
                                            Join Now
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--================login_part end =================-->
    </main>

    <br />

    <?php require "include/footer.php" ?>

</body>

</html>