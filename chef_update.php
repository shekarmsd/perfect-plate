<?php 
session_start();
if (!isset($_SESSION["is_cheflogin"]) || $_SESSION["is_cheflogin"] !== true) {
    header("location: cooklogin.php");
    exit;
}

$tot = $_SESSION['erns'];


try {
    if (isset($_POST['update_item'])) {

        $status = $_POST['update'];
        $item_id = $_POST['item_id'];
        $erns = $_POST['earnings'];
        $total = $tot + $erns;

        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk1 = new MongoDB\Driver\BulkWrite;

        $bulk1->update(
            ['_id' => new MongoDB\BSON\ObjectId($_SESSION['chef_id'])],
            ['$set' => [
                'Earnings' => $total,
            ]],
            ['multi' => false, 'upsert' => false]
        );
        $bulk->update(
            ['Item_Id' => new MongoDB\BSON\ObjectId($item_id)],
            ['$set' => [
                'Status' => $status,
            ]],
            ['multi' => false, 'upsert' => false]
        );
        $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $writeConcern1 = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $result = $manager->executeBulkWrite('PerfectPlate.Customer_Orders', $bulk, $writeConcern);
        $result1 = $manager->executeBulkWrite('PerfectPlate.chef', $bulk1, $writeConcern1);
        if ($result) {
            try {
                $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                $delRec = new MongoDB\Driver\BulkWrite;
                $delRec->delete(
                    ['Item_Id' => new MongoDB\BSON\ObjectId($item_id)]
                );
                $result = $mng->executeBulkWrite('PerfectPlate.chef_orders', $delRec);
                if($result){
                    $_SESSION['success'] = 'Congratulations!' .$_SESSION['name']. ' , You earned' .$total. '.👏 Keep rocking.';
                    header("Location:cookdboard.php");
                } else {
                    echo "Error";
                }

            } catch (MongoDB\Driver\Exception\Exception $e) {
                echo "Exception:", $e->getMessage();
            }
            
        } else {
            echo "Error";
        }
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}


?>