<?php
session_start();
$id = $_GET['id'];
try {
    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $delRec = new MongoDB\Driver\BulkWrite;
    $delRec->delete(
        ['_id' => new MongoDB\BSON\ObjectId($id)],
        ['limit' => 1]
    );
    $result = $mng->executeBulkWrite('PerfectPlate.users', $delRec);
    if($result){
        $_SESSION["delete_info"] = "User has been deleted successfully!";
        header("Location:admin.php");
    } else {
        echo "Error";
    }

} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}
