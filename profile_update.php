<?php
session_start();
session_start();
    if(!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true){
        header("Location: login.php");
        exit;
    }
$id = $_SESSION['pid'];
$pic;

try {
    if (isset($_POST['update'])) {


        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $img = $_FILES['customFile'];

        $bulk = new MongoDB\Driver\BulkWrite;
        $pk = new \MongoDB\BSON\ObjectId($id);
        $pic =  new MongoDB\BSON\Binary(file_get_contents($img["tmp_name"]), MongoDB\BSON\Binary::TYPE_GENERIC);
        $bulk->update(
            ['_id' => $pk],
            ['$set' => [
                'Name' => $name,
                'Email' => $email,
                'Phone' => $phone,
                'Profile_Pic' => $pic
            ]],
            ['multi' => false, 'upsert' => false]

        );
        $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $result = $manager->executeBulkWrite('PerfectPlate.users', $bulk, $writeConcern);
        if ($result) {
            unset($_SESSION['name']);
            unset($_SESSION['email']);
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION["pro_info"] = "Your profile has been updated!";
            header("Location:profile.php");
        } else {
            echo "error";
        }
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}
