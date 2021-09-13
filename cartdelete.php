<?php
session_start();
if(!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true){
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        $delRec = new MongoDB\Driver\BulkWrite;
        $delRec->delete(
            ['_id' => new MongoDB\BSON\ObjectID($id)],
            ['limit' => 1]
        );
        $result = $mng->executeBulkWrite('PerfectPlate.cart', $delRec);
        if ($result) {
            $_SESSION['clr'] = "You removed a recipe from your cart!";
            header("Location:cart.php");
        } else {
            echo "Error";
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {   
        echo "Exception:", $e->getMessage();
    }
}
