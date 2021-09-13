<?php
session_start();

//Iteam view open//

$id = $_GET['id'];
try {
    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $pk = new \MongoDB\BSON\ObjectId($id);
    $filter = [
        '_id' => $pk
    ];
    $options = [];
    $query = new MongoDB\Driver\Query($filter);
    $rows = $mng->executeQuery("PerfectPlate.category", $query);
    $dis = current($rows->toArray());
    if (!empty($dis)) {
        $name = $dis->Recipe;
        $price = $dis->price;
        $category = $dis->Category;
        $img = $dis->cover;
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}

//Iteam view close//


//Iteam Update open///

try {
    if (isset($_POST['upload'])) {

        $iname = $_POST['recipe'];
        $iprice = $_POST['price'];
        $icatg = $_POST['category'];

        $bulk = new MongoDB\Driver\BulkWrite;
        $pk = new \MongoDB\BSON\ObjectId($id);

        $bulk->update(
            ['_id' => $pk],
            ['$set' => [
                'Recipe' => $iname,
                'price' => $iprice,
                'Category' => $icatg
            ]],
            ['multi' => false, 'upsert' => false]

        );
        $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $result = $manager->executeBulkWrite('PerfectPlate.category', $bulk, $writeConcern);
        if ($result) {
            $_SESSION["update_info"] = "Recipe has been updated successfully!";
            header("Location:total_items.php");
        } else {
        }
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}

//Iteam Update close///

?>

<!doctype html>
<html lang="en">

<?php require "include/head.php" ?>

<body>

    <?php $page = 'index';
    require "include/adminnav.php" ?>

    <section class="new-product-area" style="padding-top: 60px; padding-bottom: 170px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8 col-md-10">
                    <div class="section-tittle mb-70 text-center">
                        <h2>Update Item</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-5">
                    <div class="card" style="width: 18rem;">
                        <img class="card-img-top" src="data:jpeg;base64,<?= base64_encode($img->cover->getData()) ?>" alt="Card image cap">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8" style="text-align: left;">
                                    <h6><?php echo $category ?></h6>
                                    <h6><?php echo $name; ?></h6>
                                </div>
                                <div class="col">
                                    <h6>&#x20B9; <?php echo $price; ?>/-</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-7">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col">
                                <label for="recipe" class="col-form-label">Recipe Name</label>
                                <div class="mt-10">
                                    <input type="text" name="recipe" id="recipe" value="<?=$name ?>" class="single-input">
                                </div>
                            </div>
                            <div class="col">
                                <label for="filter" class="col-form-label">Price</label>
                                <div class="mt-10">
                                    <input type="text" name="price" id="price" value="<?=$price ?>" class="single-input">
                                </div>
                            </div>

                        </div>&nbsp;
                        <div class="form-group">
                            <label for="category" class="col-form-label">Category</label>
                            <div class="default-select" id="default-select">
                                <select id="category" name="category">
                                    <option value="South Indian">South Indian</option>
                                    <option value="North Indian">North Indian</option>
                                    <option value="West Indian">West Indian</option>
                                </select>
                            </div>
                        </div><br />
                        <div class="form-group">
                            <button type="submit" id="upload" name="upload" class="genric-btn success radius">Update Item</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php require "include/footer.php" ?>

</body>

</html>