<?php
session_start();

$msg = "";

if(isset($_POST['verify'])){
    $correctotp=$_SESSION["otp"];
    $enteredotp=$_POST['otp'];
    if($correctotp==$enteredotp){
        $_SESSION['otp_vfy'] = "Your OTP has been verified!";
        header('Location:setchef.php');
    }
    else{
        $msg="Invalid OTP!";  
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

    <section class="confirmation_part" style="padding-top: 60px; padding-bottom: 210px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8 col-md-10">
                    <div class="section-tittle mb-70 text-center"><br /><br />
                        <h3>Verify Your OTP</h3>
                        <p>OTP sent to your registred email address.</p>
                    </div>
                </div>
            </div>
            <form method="POST" class="form-signin">
                <div class="form-group">
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $_SESSION['forgotpassemail']; ?>" placeholder="Email address" required="" disabled autofocus="">
                </div>
                <div class="form-group">
                    <input type="password" id="otp" name="otp" class="form-control" placeholder="OTP" required="">
                    <p style="color: red;"><?php echo $msg; ?></p>
                </div>
                <button class="btn btn-lg btn-primary btn-block" name="verify" id="verify" type="submit">Vefify</button>
               
            </form>
        </div>
    </section>


    <?php require "include/footer.php" ?>


</body>

</html>