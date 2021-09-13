<?php
session_start();
    if(!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true){
        header("Location: login.php");
        exit;
    }
?>

<!doctype html>
<html class="no-js" lang="en">

<?php require "include/head.php" ?>

<body>


    <?php $page = 'myorders'; require "include/navbar.php" ?>

    <?php require "include/myorder.php" ?>
    

    <?php require "include/footer.php" ?>


</body>

</html>