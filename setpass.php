<?php
session_start();

$msg = "";
$email = $_SESSION['forgotpassemail'];

if (isset($_POST['set'])) {
    $password = $_POST['pass'];
    $cpass = $_POST['cpass'];
    if ($password == $cpass) {

        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            ['Email' => $email],
            ['$set' => ['Password' => md5($password)]],
        );
        $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        $result = $manager->executeBulkWrite('PerfectPlate.users', $bulk);
        if ($result) {
            $_SESSION['pass_info'] = "Password has been reset successfully!";
            header("Location:login.php");
        } else {
            echo '<script>alert("Dear User,\nSorry something went wrong!\nTry after sometime.")</script>';
        }
    } else {
        $msg = "Password Dose Not Match!";
    }
}


?>
<!doctype html>
<html class="no-js" lang="en">

<?php require "include/head.php" ?>
<style>
    .form-signin {
        width: 100%;
        max-width: 400px;
        padding: 15px;
        margin: 0 auto;
    }
</style>

<body>

    <?php require "include/forgetnav.php" ?>

    <section class="container">
        <?php if (!empty($_SESSION["otp_vfy"])) { ?>
            <div class="alert alert-info">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Info!</strong> <?php echo $_SESSION["otp_vfy"]; ?>
            </div>
        <?php }
        unset($_SESSION["otp_vfy"]); ?>
    </section>

    <section class="confirmation_part" style="padding-top: 60px; padding-bottom: 210px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8 col-md-10">
                    <div class="section-tittle mb-70 text-center"><br /><br />
                        <h3>Set New Password</h3>
                    </div>
                </div>
            </div>
            <form method="POST" class="form-signin">
                <div class="form-group">
                    <input type="password" id="pass" name="pass" class="form-control" placeholder="New password" required="" autofocus="">
                </div>
                <div class="form-group">
                    <input type="password" id="cpass" name="cpass" class="form-control" placeholder="Confirm password" required="" autofocus="">
                    
                </div>
                <p style="color: red;"><?php echo $msg; ?></p>
                <button class="btn btn-lg btn-primary btn-block" name="set" id="set" type="submit">Save</button>

            </form>
        </div>
    </section>


    <?php require "include/footer.php" ?>


</body>

</html>