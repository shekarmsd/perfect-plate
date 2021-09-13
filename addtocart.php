<?php
    session_start();
    if(!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true){
        header("Location: login.php");
        exit;
    }

    $id = $_GET['ord_id'];
    $pk_id = $_GET['id'];
    $cart_id = $_GET['cart_id'];

        if (isset($_SESSION['is_login'])) {
            if($pk_id != $cart_id){
                try{

                    $manager = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017");
                    $pk = new \MongoDB\BSON\ObjectId($id);
                    $filter = [
                        '_id' => $pk
                    ];
                    $options = [];

                    $query = new MongoDB\Driver\Query($filter, $options);
                    $cursor = $manager->executeQuery('PerfectPlate.category', $query);

                    $ans = current($cursor->toArray());
                    
                    if (!empty($ans)) {
                        $product = $ans;
                        $mng = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017");
                        $bulk = new MongoDB\Driver\BulkWrite;
                        $cart = [
                            'Item_Id' => $id,
                            'Customer_Id' => $_SESSION['cus_id'],
                            'Customer' => $_SESSION['name'],
                            'Category' => $product->Category,
                            'Recipe' => $product->Recipe,
                            'Quantity' => '1',
                            'Price' => $product->price,
                            'Recipe_Image' => $product->cover
                        ];
                        $bulk->insert($cart);
                        $result = $mng->executeBulkWrite('PerfectPlate.cart', $bulk);

                        if ($result) {
                            $info = "Recipe added to your cart!";
                            $_SESSION['price'] = $product->price;
                            $_SESSION['item_id'] = $id;
                            $_SESSION['item_img'] = $product->cover;
                            header("location:cart.php");
                        }
                    } else {
                        echo '<script>alert("Dear User,\nSorry something went wrong!\nTry after sometime.")</script>';
                    }
                } catch (MongoDB\Driver\Exception\Exception $e) {
                    echo "Exception:", $e->getMessage();
                }
            } else {
                $_SESSION['info_cart'] = "Recipe has been already added to your cart!";
                header("Location:category.php");
            }
                
        } else {
            header('Location:login.php');
        }

    
?>