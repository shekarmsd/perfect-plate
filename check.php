<?php
session_start();
try {

    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $filter = [
        'Customer_Id' => $_SESSION['cus_id']
    ];
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $manager->executeQuery('PerfectPlate.orders', $query);

    foreach ($cursor as $question) {
        $orderids = $question->Order_Id;
    }
    foreach ($orderids as $pid) {
        $filter1 = [
            '_id' => new MongoDB\BSON\ObjectId($pid)
        ];
        $query1 = new MongoDB\Driver\Query($filter1);
        $cursor1 = $manager->executeQuery('PerfectPlate.category', $query1);
        $ans = current($cursor1->toArray());
        $oneim = $ans->cover;

?>
        <div class="media">
            <div class="d-flex">
                <img src="data:jpeg;base64,<?= base64_encode($oneim->cover->getData()) ?>" alt="" />
            </div>
            <div class="media-body">
                <h5><?php echo $ans->Recipe; ?></h5>
            </div>
        </div>

<?php
    }
} catch (MongoConnectionException $e) {
    die('Error connecting to MongoDB server');
} catch (MongoException $e) {
    die('Error: ' . $e->getMessage());
}


?>