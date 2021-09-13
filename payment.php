<?php
session_start();
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: login.php");
    exit;
}

$count = $_SESSION['count'];
$total = $_SESSION['total'];
$first = $_SESSION['first'];
$phone = $_SESSION['phone'];
$customer = $_SESSION['email'];
$pay = "Online Payment";
$id = $_GET['id'];


if (isset($_POST['PayButton'])) {

    $name = $_POST['NameOnCard'];
    $card_no = $_POST['CreditCardNumber'];
    $expdate = $_POST['ExpiryDate'];
    $code = $_POST['SecurityCode'];

    try {

        $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        $bulk = new MongoDB\Driver\BulkWrite;
        $date = new DateTime();

        $doc = array(
            "Customer_Id" => $_SESSION['cus_id'],
            "Card_Holder_Name" => $name,
            "Card_Number" => $card_no,
            "Expiry_Date" => $expdate,
            "CVV" => $code,
            "Payment_Mode" => $pay,
            "Total_Amount" => $total,
            "DateTime" => $date,
        );

        $bulk->insert($doc);
        $mng->executeBulkWrite('PerfectPlate.payment', $bulk);
        if ($mng) {

            try {
                $manager = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017");
                $filter = ['Customer_Id' => new MongoDB\BSON\ObjectId($_SESSION['cus_id'])];
                $query = new MongoDB\Driver\Query($filter);
                $cursor = $manager->executeQuery('PerfectPlate.cart', $query);
                if (!empty($cursor)) {
                    foreach ($cursor as $in) {

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
            $mail->AddAddress($customer);

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
                if ($result) {
                    $_SESSION["order_info"] = "You're order has been placed!";
                    header("Location:index.php");
                } else {
                    echo "Error";
                }
            } catch (MongoDB\Driver\Exception\Exception $e) {
                echo "Exception:", $e->getMessage();
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

    <div class="container" style="padding-top: 70px;">
        <div id="Checkout" class="inline">
            <h1>Pay Invoice</h1>
            <div class="card-row">
                <span class="visa"></span>
                <span class="mastercard"></span>
                <span class="amex"></span>
                <span class="discover"></span>
            </div>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="PaymentAmount">Payable Amount</label>
                    <div class="amount-placeholder">
                        <span>&#8377;</span>
                        <span><?php echo $_SESSION['total']; ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label or="NameOnCard">Name on card</label>
                    <input id="NameOnCard" name="NameOnCard" class="form-control" type="text" maxlength="255" required></input>
                </div>
                <div class="form-group">
                    <label for="CreditCardNumber">Card Number</label>
                    <input id="CreditCardNumber"inputmode="numeric" name="CreditCardNumber" onblur="ValidateCreditCardNumber()" class="null card-image form-control" type="tel" required></input>
                </div>

                <div class="expiry-date-group form-group">
                    <label for="ExpiryDate">Expiry date</label>
                    <input id="ExpiryDate" name="ExpiryDate" class="form-control" type="month" placeholder="MM / YY" min="2020-12" value="2020-12" required></input>
                </div>
                <div class="security-code-group form-group">
                    <label for="SecurityCode">CVV</label>
                    <div class="input-container">
                        <input id="SecurityCode" name="SecurityCode" class="form-control" type="password" required></input>
                        <i id="cvc" class="fa fa-question-circle"></i>
                        <a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" data-placement="left" data-content="CVV number is the last three digits on the back of your card."><i class="fa fa-question-circle"></i></a>
                    </div>
                </div>
                <div class="row" style="padding-top: 20px;">
                    <div class="col">
                        <div>
                            <a href="cart.php" class="genric-btn danger">Cancle</a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group" style="text-align: right;">
                            <button style="color:#fff;" id="PayButton" name="PayButton" class="genric-btn success radious" type="submit">
                                <span class="submit-button-lock"></span>
                                Proceed to Pay
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <script>
                function ValidateCreditCardNumber() {

                    var ccNum = document.getElementById("CreditCardNumber").value;
                    var visaRegEx = /^(?:4[0-9]{12}(?:[0-9]{3})?)$/;
                    var mastercardRegEx = /^(?:5[1-5][0-9]{14})$/;
                    var amexpRegEx = /^(?:3[47][0-9]{13})$/;
                    var discovRegEx = /^(?:6(?:011|5[0-9][0-9])[0-9]{12})$/;
                    var isValid = false;

                    if (visaRegEx.test(ccNum)) {
                        isValid = true;
                    } else if (mastercardRegEx.test(ccNum)) {
                        isValid = true;
                    } else if (amexpRegEx.test(ccNum)) {
                        isValid = true;
                    } else if (discovRegEx.test(ccNum)) {
                        isValid = true;
                    }

                    if (isValid) {
                        return true;
                    } else {
                        alert("Please provide a valid card number!");
                    }
                }
            </script>
        </div>
    </div>

    <script>
        $(function() {
            $('[data-toggle="popover"]').popover();

            $('#cvc').on('click', function() {
                if ($('.cvc-preview-container').hasClass('hide')) {
                    $('.cvc-preview-container').removeClass('hide');
                } else {
                    $('.cvc-preview-container').addClass('hide');
                }
            });

        });
    </script>



</body>

</html>