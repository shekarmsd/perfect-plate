<?php
session_start();
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: login.php");
    exit;
}

$items_ids = array();

$count = $_SESSION['count'];
$total = $_SESSION['total'];
$id = $_GET['id'];

if (isset($_POST['order'])) {

    $first = $_POST['first'];
    $_SESSION['first'] = $first;
    $last = $_POST['last'];
    $email = $_POST['email'];
    $phone = $_POST['number'];
    $_SESSION['phone'] = $phone;
    $add1 = $_POST['add1'];
    $add2 = $_POST['add2'];
    $country = $_POST['country'];
    $district = $_POST['district'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];
    $pay = $_POST['paymentMethod'];

    try {

        $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        $filter = [
            'Customer_Id' => $_SESSION['cus_id']
        ];
        $query = new MongoDB\Driver\Query($filter);
        $cursor = $manager->executeQuery('PerfectPlate.cart', $query);
        foreach ($cursor as $question) {
            array_push($item, $question->Item_Id);
        }
        
        $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        $bulk = new MongoDB\Driver\BulkWrite;
        $date = new DateTime();

        $doc = array(
            "Customer_Id" => $_SESSION['cus_id'],
            "First_Name" => $first,
            "Last_Name" => $last,
            "Email" => $email,
            "Phone" => $phone,
            "Address_Line1" => $add1,
            "Address_Line2" => $add2,
            "Country" => $country,
            "District" => $district,
            "City" => $city,
            "Pincode" => $zip,
            "Payment_Mode" => $pay,
            "Total_Amount" => $total, 
            "Total Items" => $count,
            "DateTime" => $date,
            "Status" => "Order Placed",
        );

        $bulk->insert($doc);
        $mng->executeBulkWrite('PerfectPlate.orders', $bulk);
        if ($mng) {

            if($pay != "Cash On Dilivery"){
                header("location:payment.php?id=".$id);
            } else {

                        try {
                            
                            $manager = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017");
                            $filter = ['Customer_Id' => new MongoDB\BSON\ObjectId($_SESSION['cus_id'])];
                            $query = new MongoDB\Driver\Query($filter);
                            $cursor = $manager->executeQuery('PerfectPlate.cart', $query);
                            if (!empty($cursor)) {
                                    foreach($cursor as $in){

                                    $mng = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017");
                                    $bulk = new MongoDB\Driver\BulkWrite;
                                    $bulk1 = new MongoDB\Driver\BulkWrite;
                                    $cart1 = [
                                        'Item_Id' => $in->_id,
                                        'Customer_Id' => $_SESSION['cus_id'],
                                        'Customer' => $first,
                                        'Category' => $in->Category,
                                        'Recipe' => $in->Recipe,
                                        'Quantity' => $in->Quantity,
                                        'Total_Amount' => $total,
                                        "Phone" => $phone,
                                        "DateTime" => $date,
                                        "Status" => "Order Placed",
                                        'Recipe_Image' => $in->Recipe_Image
                                    ];
                                    $cart = [
                                        'Item_Id' => $in->_id,
                                        'Customer_Id' => $_SESSION['cus_id'],
                                        'Customer' => $first,
                                        'Category' => $in->Category,
                                        'Recipe' => $in->Recipe,
                                        'Quantity' => $in->Quantity,
                                        'Total_Amount' => $total,
                                        "Phone" => $phone,
                                        "DateTime" => $date,
                                        "Status" => "Order Placed",
                                        'Recipe_Image' => $in->Recipe_Image
                                    ];
                                    $bulk->insert($cart);
                                    $bulk1->insert($cart1);
                                    $result = $mng->executeBulkWrite('PerfectPlate.items', $bulk);
                                    $result1 = $mng->executeBulkWrite('PerfectPlate.Customer_Orders', $bulk1);
                                }
                            }

                        } catch (MongoDB\Driver\Exception\Exception $e) {
                            echo "Exception:", $e->getMessage();
                        }

                        require_once('PHPMailer/PHPMailerAutoload.php');
                        $mail = new PHPMailer(true);
                        $mail->isSMTP();
                        $mail->SMTPAuth = true;
                        $mail->SMTPSecure = 'ssl';
                        $mail->Host = 'smtp.gmail.com';
                        $mail->Port = '465';
                        $mail->isHTML();
                        $mail->Username = 'thepictureperf@gmail.com';
                        $mail->Password = 'PicturePerf7';

                        $mail->FromName = "Perfect Plate";
                        $mail->addReplyTo("thepictureperf@gmail.com", "Reply");
                        //Send HTML or Plain Text email
                        $mail->isHTML(true);

                        $mail->Subject = "Order Confirmation";
                        $mail->Body = "<p>Thank you!\n You're order has been placed!\n We will get back to soon.</p>";
                        $mail->AddAddress($email);

                        try {
                            $mail->send();
                            header("Location: index.php");
                        } catch (Exception $e) {
                            echo "Mailer Error: " . $mail->ErrorInfo;
                        }

                        try {
                            $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                            $delRec = new MongoDB\Driver\BulkWrite;
                            $delRec->delete(
                                ['Customer_Id' => $_SESSION['cus_id']]
                            );
                            $result = $mng->executeBulkWrite('PerfectPlate.cart', $delRec);
                            if($result){
                                $_SESSION["order_info"] = "You're order has been placed!";
                                header("Location:index.php");
                            } else {
                                echo "Error";
                            }

                        } catch (MongoDB\Driver\Exception\Exception $e) {
                            echo "Exception:", $e->getMessage();
                        }
                    }
        } else {
            echo "Error";
        }
    } catch (MongoConnectionException $e) {
        die('Error connecting to MongoDB server');
    } catch (MongoException $e) {
        die('Error: ' . $e->getMessage());
    }
}

?>

<!doctype html>
<html class="no-js" lang="en">

<?php require "include/head.php" ?>

<body>

    <?php $page = 'cart';
    require "include/navbar.php" ?>
    <main>
        <section class="checkout_area" style="padding-top: 60px; padding-bottom: 40px; background-color: #F8F9F9;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-8 col-md-10">
                        <div class="section-tittle mb-70 text-center">
                            <h3>Order Detailes</h3>
                        </div>
                    </div>
                </div>
                <div class="billing_details">
                    <div class="row">
                        <div class="col-lg-8">
                            <h3>Shipping Details</h3>
                            <form class="row contact_form" action="" method="POST">
                                <div class="col-md-6 form-group p_star">
                                    <input type="text" class="form-control" id="first" value="<?php echo $_SESSION['name']; ?>" name="first" placeholder="First Name" required pattern="[Aa-Zz]"
        title="Username should only contain lowercase letters. e.g. john"/>
                                </div>
                                <div class="col-md-6 form-group p_star">
                                    <input type="text" class="form-control" id="last" name="last" placeholder="Last Name" required pattern="[Aa-Zz]"
        title="Username should only contain lowercase letters. e.g. john"/>
                                </div>
                                <div class="col-md-6 form-group p_star">
                                    <input type="tel" class="form-control" id="number" name="number" placeholder="Phone Number" required pattern="[7-9]{1}[0-9]{9}" 
       title="Phone number with 7-9 and remaing 9 digit with 0-9"/>
                                </div>
                                <div class="col-md-6 form-group p_star">
                                    <input type="email" class="form-control" id="email" value="<?php echo $_SESSION['email']; ?>" name="email" placeholder="Email Address" required />
                                </div>
                                <div class="col-md-12 form-group p_star">
                                    <input type="text" class="form-control" id="add1" name="add1" placeholder="Address Line 01" required />
                                </div>
                                <div class="col-md-12 form-group p_star">
                                    <input type="text" class="form-control" id="add2" name="add2" placeholder="Address Line 02" required />
                                </div>
                                <div class="col-md-6 form-group p_star">
                                    <select class="country_select" id="country" name="country" required>
                                        <option value="India">India</option>
                                        <option value="USA">USA</option>
                                        <option value="Canada">Canada</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group p_star">
                                    <input type="text" class="form-control" id="city" name="city" placeholder="City" required pattern="[Aa-Zz]" title="City name must contain letters only. e.g. Bengalore"/>
                                </div>
                                <div class="col-md-6 form-group p_star">
                                    <input type="text" class="form-control" id="district" name="district" placeholder="District" required pattern="[Aa-Zz]"
        title="Username should only contain lowercase letters. e.g. john"/>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="text" class="form-control" id="zip" name="zip" placeholder="Pincode/ZIP" required pattern="[0-9]{6}" title="Pincode must be valid. e.g. 635105" />
                                </div>
                        </div>
                        <div class="col col-4">
                            <h3>Order Summery</h3>
                            <div class="card text-center" style="background-color: white;">
                                <div class="card-body">
                                    <div style="border-bottom: 0.5px solid lightgray;">
                                        <h4>Total Iteams - <strong style="color: red;"><?php echo $count; ?></strong></h4>
                                    </div><br />
                                    <div class="row">
                                        <div class="col" style="text-align: left; border-bottom: 0.5px solid lightgray;">
                                            <h6>Total MRP</h6>
                                            <h6>Discount</h6>
                                            <h6>GST</h6>
                                            <h6>Delivery Charges</h6>
                                        </div>
                                        <div class="col" style="text-align: right; border-bottom: 0.5px solid lightgray;">
                                            <h6 style="color: red;">&#8377; <?php echo $_SESSION['amt']; ?>.00</h6>
                                            <h6>&#8377; 0.00</h6>
                                            <h6>&#8377; 0.00</h6>
                                            <h6 style="color: red;">&#8377; 50.00</h6>
                                        </div>
                                    </div>&nbsp;
                                    <div class="row">
                                        <div class="col" style="text-align: left;">
                                            <h4>Total</h4><br />
                                            <h6><strong>Payment Mode:</strong></h6><br/>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="paymentMethod" id="paymentMethod" value="Online Payment" checked>
                                                <label class="form-check-label" for="paymentMethod">
                                                    Online Payment
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col" style="text-align: right;">
                                            <h4 style="color: red;">&#8377; <?php echo $_SESSION['total']; ?><br/>
                                            <h6><br /></h6><br/><br/>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="paymentMethod" id="paymentMethod" value="Cash On Dilivery">
                                                <label class="form-check-label" for="paymentMethod">
                                                    Cash On Dilivery
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <br />
                                    <button type="submit" id="order" name="order" class="genric-btn info-border radius">Place Order</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section><br/>
    </main>

    <?php require "include/footer.php" ?>


</body>

</html>