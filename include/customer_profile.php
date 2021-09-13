<?php

try {
    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $filter = [
        'Email' => $_SESSION['email']
    ];
    $query = new MongoDB\Driver\Query($filter);
    $rows = $mng->executeQuery("PerfectPlate.users", $query);
    foreach ($rows as $row) {
        $name = $row->Name;
        $email = $row->Email;
        $phone = $row->Phone;
        $profile = $row->Profile_Pic;
        $_SESSION['pid'] = $row->_id;
    }
    $pic = current($profile);

} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}

?>

<section class="contact-session">
    <div class="container" style="padding-top: 60px; padding-bottom: 240px;">
        <section class="container">
            <?php if (!empty($_SESSION["pro_info"])) { ?>
                <div class="alert alert-info">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Info!</strong> <?php echo $_SESSION["pro_info"]; ?>
                </div>
            <?php }
            unset($_SESSION["pro_info"]); ?>
        </section>
        <div style="text-align: center;">
            <h4>Profile Details</span></h4>
        </div><br /><br />
        <div class="row">
            <div class="col">
                <div class="card" style="width: 18rem; float: right;">
                    <?php if(!empty($pic)) { ?>
                        <img class="card-img-top" src="data:jpeg;base64,<?= base64_encode($profile->getData()) ?>" alt="Card image cap" width="300" height="300">
                   <?php } else { ?>
                        <img class="card-img-top" src="assets/img/profile.png" alt="Card image cap" width="283" height="283">
                   <?php } ?>
                    <div class="card-body">
                        <h5 class="card-text" style="text-align: center;"><?php echo $_SESSION['name']; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-7">
                <form method="POST" action="profile_update.php" enctype="multipart/form-data">
                    <div class="form-group col-md-8">
                        <label for="name">Name</label>
                        <input type="text" value="<?php echo $name; ?>" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group col-md-8">
                        <label for="email">Email</label>
                        <input type="email" value="<?php echo $email; ?>" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group col-md-8">
                        <label for="phone">Phone Number</label>
                        <input type="text" value="<?php echo $phone; ?>" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="form-group col-md-8">
                        <label for="customFile">Profile Picture</label><br />
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="customFile" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>
                    <div class="form-group col-md-8" style="text-align: center;">
                        <button type="submit" id="update" name="update" style="padding: 19px 20px; border-radius: 4px; font-size: 12px; background: green;" class="btn btn-primary">Save Changes</button>
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
        </div>
    </div>
</section>