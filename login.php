<?php

session_start();

if (isset($_SESSION["is_login"]) && $_SESSION["is_login"] == true) {
    header("location: index.php");
    exit;
}

$error_msg = "";

if (isset($_POST["submit"])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $mng = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017");

        $filter = ['Email' => $email];

        $query = new MongoDB\Driver\Query($filter);
        $cur = $mng->executeQuery('PerfectPlate.users', $query);
        $ans = current($cur->toArray());
        if (!empty($ans)) {
            $id = $ans->_id;
            $name = $ans->Name;
            $remail = $ans->Email;
            $rpassword = $ans->Password;
            $pass = md5($password);

            if ($email == $email && $rpassword == $pass) {
                session_start();
                $_SESSION["cus_id"] = $id;
                $_SESSION["name"] = $name;
                $_SESSION["email"] = $email;
                $_SESSION["is_login"] = true;
                header("Location: index.php");
            } else {
                $error_msg = "Incorrect password. Try again!";
                header("Referesh: login.php");
            }
        } else {
            $error_msg = "Invalid email address or password. Try again!";
        }
    } catch (MongoConnectionException $e) {
        die('Error connecting to MongoDB server');
    } catch (MongoException $e) {
        die('Error: ' . $e->getMessage());
    }
}


if (isset($_POST['find'])) {
    $emailfor = $_POST["emailfor"];
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $filter = [
        'Email' => $emailfor
    ];
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $manager->executeQuery('PerfectPlate.users', $query);

    $ans = current($cursor->toArray());
    if (!empty($ans)) {
        $_SESSION['forgotpassemail'] = $emailfor;
        $otp = verify($emailfor);
        $_SESSION["otp"] = $otp;
        header('Location:forget.php');

    } else {
        $msg="Sorry User doesnot exist! ";  
        header('Location:join.php');
    }
}
function verify($emailfor)
{

    function generateNumericOTP($n)
    {
        $generator = "1357902468";
        $result = "";
        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator))), 1);
        }
        return $result;
    }
    require_once('PHPMailer/PHPMailerAutoload.php');
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = '465';
    $mail->isHTML();
    $mail->Username = 'thepictureperf@gmail.com';
    $mail->Password = 'PicturePerf7';

    $mail->FromName = "Forget Password";
    $mail->addReplyTo("thepictureperf@gmail.com", "Reply");
    //Send HTML or Plain Text email
    $mail->isHTML(true);

    $mail->Subject = "OTP Verification";
    $otp=generateNumericOTP(4);
    $mail->Body="Dear User,\n\n\n\nYour verfication code [$otp]\n\n\n\nPlease do not share your OTP with anyone.\n\n\n\nThank you!";
    $mail->AddAddress($emailfor);

    if(!$mail->Send()){
        echo '<script>alert("Dear User,\nError occured while processing.Please try after sometime!")</script>'; 
    }
    else{
        echo '<script>alert("Dear User,\nPlease check your mail for the verification code!")</script>'; 
        return $otp;
    }
}

?>


<!doctype html>
<html lang="en">

<?php require "include/head.php" ?>

<body>

    <?php $page = 'login';
    require "include/navbar.php" ?>

    <br />
    <main>

    <section class="container">
        <?php if (!empty($_SESSION["pass_info"])) { ?>
            <div class="alert alert-info">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Info!</strong> <?php echo $_SESSION["pass_info"]; ?>
            </div>
        <?php }
        unset($_SESSION["pass_info"]); ?>
    </section>
        <!--================login_part Area =================-->
        <section class="login_part section ">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6">
                        <div class="login_part_text text-center" style="background-image: url('assets/img/img12.jpg'); border-radius: 27px;">
                            <div class="login_part_text_iner">
                                <p>???Ask not what you can do for your country.<br /> Ask what???s for lunch.???<br />??? Orson Welles</p>
                                <h2>Don't have an account?</h2>
                                <a href="join.php" class="btn_3">Create an Account</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="login_part_form">
                            <div class="login_part_form_iner">&nbsp;

                                <h5>Are you a chef? <a href="cooklogin.php"><span style="color: green;">Login</span></a> here.</h5>&nbsp;

                                <h3>Welcome Back ! <br> Please Sign In now</h3>
                                <form class="row contact_form" action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST">
                                    <div class="col-md-12 form-group p_star">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required >
                                    </div>
                                    <div class="col-md-12 form-group p_star">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                    </div>
                                    <div  class="col-md-12 form-group p_star">
                                        <p><span style="color: red;"><?php echo ($error_msg); ?></span></p>
                                        <h6>Don't have an account? <a href="join.php" style="color: green;">Create An Account</a></h6>
                                        <div data-toggle="modal" data-target="#find">
                                            <h6><a href="#find" style="color: #0b1c39;">Forgot Password?</a></h6>
                                        </div>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <button type="submit" value="submit" name="submit" class="btn_3">
                                            login
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

        <section>
            <div class="modal fade" id="find" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Find your accound</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="" enctype="multipart/form-data">
                                <p>Enter your email address linked to your account.</p>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="emailfor" id="emailfor" placeholder="Email Address" required>
                                </div>
                                <div class="button-group-area mt-10" style="float: right;">
                                    <button type="submit" id="find" name="find" class="genric-btn success-border radius">Next</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </main>

    <br />

    <?php require "include/footer.php" ?>

</body>

</html>