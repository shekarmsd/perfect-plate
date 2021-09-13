<?php
session_start();
$id = $_GET['id'];
$id1 = $_SESSION['cus_id'];
try {
    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $delRec = new MongoDB\Driver\BulkWrite;
    $delRec1 = new MongoDB\Driver\BulkWrite;
    $delRec1->delete(
        ['Customer_Id' => new MongoDB\BSON\ObjectId($id1)],
        ['limit' => 1]
    );
    $delRec->delete(
        ['_id' => new MongoDB\BSON\ObjectId($id)]
    );
    $result = $mng->executeBulkWrite('PerfectPlate.orders', $delRec);
    $result1 = $mng->executeBulkWrite('PerfectPlate.Customer_Orders', $delRec1);
    if ($result) {
        $_SESSION["order_info"] = "You're order has been canclled!";
        header("Location:myorders.php");
    } else {
        echo "Error";
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}
