<?php
session_start();

if (isset($_POST['upload'])) {

    $rname = $_POST['recipe'];
    $price = $_POST['price'];
    $catg = $_POST['category'];
    $picture = $_FILES['customFile'];

    try {
        $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        $bulk = new MongoDB\Driver\BulkWrite;

        $pic = array(
            "cover" => new MongoDB\BSON\Binary(file_get_contents($picture["tmp_name"]), MongoDB\BSON\Binary::TYPE_GENERIC),
        );
        $doc = array(
            "Recipe" => $rname,
            "price" => $price,
            "Category" => $catg,
            "cover" => $pic,
        );

        $bulk->insert($doc);
        $mng->executeBulkWrite('PerfectPlate.category', $bulk);
        if ($mng) {
            $_SESSION["info_msg"] = "New Recipe Added Successfully!";
        } else {
            $_SESSION["info_err"] =  "Something went wrong! Please try again later.";
        }
    } catch (MongoConnectionException $e) {
        die('Error connecting to MongoDB server');
    } catch (MongoException $e) {
        die('Error: ' . $e->getMessage());
    }
}


?>

<!doctype html>
<html lang="en">

<?php require "include/head.php" ?>

<body>

    <?php $page = 'recipes';
    require "include/adminnav.php" ?>


    
    <section class="container">
        <?php if (!empty($_SESSION["info_msg"])) { ?>
            <div class="alert alert-success">
                <a href="admin.php" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Success!</strong> <?php echo $_SESSION["info_msg"]; ?>
            </div>
        <?php }
        unset($_SESSION["info_msg"]); ?>
        <?php if (!empty($_SESSION["update_info"])) { ?>
            <div class="alert alert-info">
                <a href="total_items.php" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Success!</strong> <?php echo $_SESSION["update_info"]; ?>
            </div>
        <?php }
        unset($_SESSION["update_info"]); ?>
        <?php if (!empty($_SESSION["delete_info"])) { ?>
            <div class="alert alert-danger">
                <a href="total_items.php" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Info!</strong> <?php echo $_SESSION["delete_info"]; ?>
            </div>
        <?php }
        unset($_SESSION["delete_info"]); ?>

    </section>

    <section class="new-product-area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8 col-md-10">
                    <div class="section-tittle mb-70 text-center">
                        <h2>Total Recipes</h2>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <form method="POST" action="filter_item.php">
                    <div class="form-row">
                        <div class="col-2">
                            <div class="default-select" id="default-select">
                                <select id="filter" name="filter" required>
                                    <option value="All Recipes">All Recipes</option>
                                    <option value="South Indian">South Indian</option>
                                    <option value="North Indian">North Indian</option>
                                    <option value="West Indian">West Indian</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <button type="submit" id="search" name="search" style="padding: 19px 13px; border-radius: 4px; font-size: 10px; background: gray;" class="btn btn-primary">Search</button>
                        </div>
                </form>
                <div class="col-6" style="text-align: right;">
                    <div data-toggle="modal" data-target="#upload">
                        <a href="#upload" class="genric-btn info-border radius">Add Recipe</a>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section><br />


    <section>
        <div class="modal fade" id="upload" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Recipe</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="recipe" class="col-form-label">Recipe Name</label>
                                <input type="text" class="form-control" name="recipe" id="recipe" required>
                            </div>
                            <div class="form-group">
                                <label for="price" class="col-form-label">Price</label>
                                <input type="text" class="form-control" name="price" id="price" required>
                            </div>
                            <label for="customFile" class="col-form-label">Recipe Image</label>
                            <div class="custom-file">

                                <input type="file" class="custom-file-input" name="customFile" id="customFile" required>
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>

                            <div>
                                <br />
                                <label for="category" class="col-form-label">Category</label><br />
                                <select class="form-control" name="category" id="category" required>
                                    <option value="South Indian">South Indian</option>
                                    <option value="North Indian">North Indian</option>
                                    <option value="West Indian">West Indian</option>
                                </select>
                            </div><br /><br />
                            <div class="button-group-area mt-10" style="float: right;">
                                <button type="submit" id="upload" name="upload" class="genric-btn success-border radius">Upload</button>
                                <a href="#" class="genric-btn info-border radius" data-dismiss="modal">Close</a>
                            </div>

                        </form>
                        <script>
                            // Add the following code if you want the name of the file appear on select
                            $(".custom-file-input").on("change", function() {
                                var fileName = $(this).val().split("\\").pop();
                                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                            });
                        </script>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>

    </section>



    <section class="new-product-area">
        <div class="container">
            <div class="row">
                <?php
                try {
                    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                    $cart = new MongoDB\Driver\Query([]);
                    $rows = $mng->executeQuery("PerfectPlate.category", $cart);
                    foreach ($rows as $row) {
                        $oneim = $row->cover;
                ?>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                            <div class="single-new-pro mb-30 text-center">
                                <div class="product-img">
                                    <img src="data:jpeg;base64,<?= base64_encode($oneim->cover->getData()) ?>" alt="">
                                </div>
                                <div class="product-caption">
                                    <div class="row">
                                        <div class="col" style="text-align: left;">
                                            <h6><span>Category</span></h6>
                                            <h6><?php echo $row->Recipe; ?></h6>
                                            <a href="updateitem.php?id=<?php echo $row->_id; ?>" class="genric-btn info radius">Update</a>
                                        </div>
                                        <div class="col">
                                            <h5><?php echo $row->Category; ?></h5>
                                            <h6>&#x20B9; <?php echo $row->price; ?>/-</h6>
                                            <a href="productdelete.php?id=<?php echo $row->_id; ?>" class="genric-btn danger radius">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div><br />
                        </div>
                <?php
                    }
                } catch (MongoDB\Driver\Exception\Exception $e) {
                    echo "Exception:", $e->getMessage();
                }
                ?>
            </div>
        </div>
    </section>

    <?php require "include/footer.php" ?>

</body>

</html>