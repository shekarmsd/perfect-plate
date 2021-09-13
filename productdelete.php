<?php
session_start();

$id = $_GET['id'];
try {
    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $delRec = new MongoDB\Driver\BulkWrite;
    $delRec->delete(
        ['_id' => new MongoDB\BSON\ObjectID($id)],
        ['limit' => 1]
    );
    $result = $mng->executeBulkWrite('PerfectPlate.category', $delRec);
    if($result){
        $_SESSION["delete_info"] = "Iteam has been deleted successfully!";
        header("Location:total_items.php");
    } else {
        echo "Error";
    }

} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}
