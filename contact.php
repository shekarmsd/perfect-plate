<?php

session_start();
$info_err = $info_msg = "";

if (isset($_POST['contact'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    try {
        $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        $bulk = new MongoDB\Driver\BulkWrite;

        $date = new DateTime();

        $doc = array(
            "Name" => $name,
            "Email" => $email,
            "Subject" => $subject,
            "Message" => $message,
            "DateTime" => $date,
        );

        $bulk->insert($doc);
        $mng->executeBulkWrite('PerfectPlate.contact', $bulk);

        if ($mng) {

            $info_msg = "Thank you for reaching us! We'll get back to you soon.";
            //header("Location: admin.php");
        } else {

?>

            <script>
                window.alert("Something went wrong! Please try again later.")
            </script>

<?php

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

    <?php $page = 'contact'; require "include/navbar.php" ?>

    <main>

        <section class="contact-section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="contact-title">Get in Touch</h2>
                    </div>
                    <div class="col-lg-8">
                        <form class="form-contact contact_form" action="#" method="post">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control valid" name="name" id="name" type="text" placeholder="Enter your name" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control valid" name="email" id="email" type="email" placeholder="Email" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <input class="form-control" name="subject" id="subject" type="text" placeholder="Enter Subject" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <textarea class="form-control w-100" name="message" id="message" cols="30" rows="9" placeholder=" Enter Message" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <span style="color: green;"><?php echo $info_msg; ?></span>
                            <div class="form-group mt-3">
                                <button type="submit" name="contact" id="contact" class="button button-contactForm boxed-btn">Send</button>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-3 offset-lg-1">
                        <div class="media contact-info">
                            <span class="contact-info__icon"><i class="ti-home"></i></span>
                            <div class="media-body">
                                <h3>Kristu Jayanti College</h3>
                                <p>Bengalore - 560077</p>
                            </div>
                        </div>
                        <div class="media contact-info">
                            <span class="contact-info__icon"><i class="ti-tablet"></i></span>
                            <div class="media-body">
                                <h3>+91 9867453278</h3>
                                <p>24 / 7 - Service.</p>
                            </div>
                        </div>
                        <div class="media contact-info">
                            <span class="contact-info__icon"><i class="ti-email"></i></span>
                            <div class="media-body">
                                <h3>support@gmail.com</h3>
                                <p>Send us your query anytime!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>


    <?php require "include/footer.php" ?>

</body>

</html>