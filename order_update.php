<?php 
session_start();


try {
    if (isset($_POST['update_status'])) {

        $status = $_POST['status'];
        $id = $_POST['order_id'];

        $bulk = new MongoDB\Driver\BulkWrite;

        $bulk->update(
            ['Item_Id' => new MongoDB\BSON\ObjectId($id)],
            ['$set' => [
                'Status' => $status,
            ]],
            ['multi' => false, 'upsert' => false]
        );
        $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $result = $manager->executeBulkWrite('PerfectPlate.Customer_Orders', $bulk, $writeConcern);
        if ($result) {
            $_SESSION["status_info"] = "Order status has been updated!";
            header("Location:order_details.php");
        } else {
            echo "Error";
        }
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}


?>