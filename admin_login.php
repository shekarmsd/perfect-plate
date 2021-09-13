<?php

if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == true) {
    header("location: admin.php");
    exit;
}

$err = "";

if(isset($_POST['set'])){
    $email = "admin@gmail.com";
    $pass = "123";
    $em = $_POST['email'];
    $pas = $_POST['pass'];
    if($email == $em && $pass == $pas){
        session_start();
        $_SESSION['admin'] = $em;
        $_SESSION['is_admin'] = true;
        header("Location:admin.php");
    }
    else {
        $err = "Invalid email address or Password";
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

    <?php $page = "index"; require "include/adminnav.php" ?>
    <main>
    <section class="confirmation_part" style="padding-top: 100px; padding-bottom: 245px; background-image: url(https://images.pexels.com/photos/1029612/pexels-photo-1029612.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940);  background-repeat: no-repeat, repeat;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8">
                    <div class="section-tittle text-center"><br /><br />
                        <h3>Admin Signin</h3>
                    </div>
                </div>
            </div>
            <form method="POST" class="form-signin">
                <div class="form-group">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email Address" required="" autofocus="">
                </div>
                <div class="form-group">
                    <input type="password" id="pass" name="pass" class="form-control" placeholder="Password" required="" autofocus="">&nbsp;
                    <p style="color: red;"><?php echo $err; ?></p>
                </div>
                <button class="btn btn-lg btn-primary btn-block" name="set" id="set" type="submit">Login</button>
            </form>
        </div>
    </section>
    </main>

</body>

</html>