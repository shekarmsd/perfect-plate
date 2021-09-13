<?php

$error_msg = "";


if (isset($_POST["submit"])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];


    try {

        $mng = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017");

        $date = new DateTime();
        $filter = ['Email' => $email];
        $query = new MongoDB\Driver\Query($filter);
        $user = $mng->executeQuery('PerfectPlate.users', $query);
        $out = current($user->toArray());

        if (!empty($out)) {

            $error_msg = 'Email is already exists! Try different one.';
        } else {

            $bulk = new MongoDB\Driver\BulkWrite;
            $users = ['Name' => $name, 'Email' => $email, 'Password' => md5($password), 'DateTime' => $date, 'Phone' => '', 'Profile_Pic' => ''];
            $bulk->insert($users);
            $mng->executeBulkWrite('PerfectPlate.users', $bulk);
            $query1 = new MongoDB\Driver\Query($filter);
            $user1 = $mng->executeQuery('PerfectPlate.users', $query1);
            $out1 = current($user1->toArray());

            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $name;
            $_SESSION['cus_id'] = $out1->_id;
            $_SESSION['is_login'] = true;

            header("Location: mail.php");
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
    <?php $page = 'join';
    require "include/navbar.php" ?>

    <main>
        <!--================login_part Area =================-->
        <section class="login_part section ">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6">
                        <div class="login_part_text text-center" style="background-image: url('assets/img/tomoto.jpg'); border-radius: 27px;">
                            <div class="login_part_text_iner">
                                <p>“At home I serve the kind of food <br />I know the story behind”<br /> – Michael Pollan</p>
                                <h2>Alredy have an account?</h2>
                                <a href="login.php" class="btn_3">Login</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="login_part_form">
                            <div class="login_part_form_iner">
                                <h4></h4>
                                <h3>Welcome to <span>Perfect </span><span style="color: green;">Plate</span> <br>Register Now</h3>
                                <form name="myForm" class="row contact_form" action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST" onsubmit="return validateForm()" enctype="multipart/form-data">
                                    <div class="col-md-12 form-group p_star">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Username" required>
                                    </div>
                                    <div class="col-md-12 form-group p_star">
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Email Address" required>
                                    </div>
                                    <div class="col-md-12 form-group p_star">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
                                    </div>
                                    <div class="col-md-12 form-group p_star">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                                    </div>
                                    <div  class="col-md-12 form-group p_star">
                                        <p><span style="color: red;"><?php echo ($error_msg); ?></span></p>
                                        <h6>Already have an account? <a href="login.php" style="color: green;">Login</a></h6>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <button type="submit" name="submit" value="submit" class="btn_3">
                                            Sign Up
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
