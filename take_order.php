<?php
session_start();

$id = $_GET['id'];

try {

    $manager = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017");
    $pk = new \MongoDB\BSON\ObjectId($id);
    $filter = [
        'Item_Id' => $pk
    ];
    $options = [];

    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery('PerfectPlate.items', $query);

    $ans = current($cursor->toArray());

    if (!empty($ans)) {
        $product = $ans;
        $mng = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017");
        $bulk = new MongoDB\Driver\BulkWrite;
        $cart = [
            'Item_Id' => $product->Item_Id,
            'Chef_Id' => $_SESSION['chef_id'],
            'Customer_Id' => $product->Customer_Id,
            'Customer' => $product->Customer,
            'Category' => $product->Category,
            'Recipe' => $product->Recipe,
            'Total_Amount' => $product->Total_Amount,
            'Phone' => $product->Phone,
            'Status' => $product->Status,
            'DateTime' => $product->DateTime,
            'Recipe_Image' => $product->Recipe_Image
        ];
        $bulk->insert($cart);
        $result = $mng->executeBulkWrite('PerfectPlate.chef_orders', $bulk);

        if ($result) {
            try {
                $bulk = new MongoDB\Driver\BulkWrite;
                $bulk->update(
                    ['Item_Id' => new MongoDB\BSON\ObjectId($id)],
                    ['$set' => [
                        'Status' => 'Processing',
                    ]],
                    ['multi' => false, 'upsert' => false]
                );
                $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
                $result = $manager->executeBulkWrite('PerfectPlate.Customer_Orders', $bulk, $writeConcern);
                if ($result) {
                    
                    $_SESSION['item_id'] = $id;
                    header("location:chef_orders.php");
                } else {
                    echo "Error";
                }
            } catch (MongoDB\Driver\Exception\Exception $e) {
                echo "Exception:", $e->getMessage();
            }
            
        }
    } else {
        echo '<script>alert("Dear User,\nSorry something went wrong!\nTry after sometime.")</script>';
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}
