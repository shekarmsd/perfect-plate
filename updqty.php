<?php

session_start();


$cartid = $_POST['cart_id'];
$qty = $_POST['qty'];


$bulk = new MongoDB\Driver\BulkWrite;
$pk = new \MongoDB\BSON\ObjectId($cartid);

$bulk->update(
    ['_id' => $pk],
    ['$set' => [
        'Quantity' => $qty
    ]],
    ['multi' => false, 'upsert' => false]
);
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
$result = $manager->executeBulkWrite('PerfectPlate.cart', $bulk, $writeConcern);
