<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>
<?php
if (isset($_POST["tbEmail"])) {
    /* DECLARE AND SET VARIABLES */
    $messageTo = "neojakey@gmail.com";
    $messageEmail = "From: " . escape($_POST["tbEmail"]);
    $messageSubject = escape($_POST["tbSubject"]);
    $messageBody = escape($_POST["tbPassword"]);

    if (!empty($messageEmail) && !empty($messageSubject) && !empty($messageBody)) {
        mail($messageTo, $messageSubject, $messageBody, $messageEmail);
        $messageSuccess = "You have been successfully registered";
    } else {
        $message = "Please fill in all fields before submitting";
    }
} else {
    $message = "";
    $messageSuccess = "";
}
?>
<style type="text/css">
    .no-data:empty {
        display: none;
    }
</style>
<?php include "includes/navigation.php"; ?>
<div class="container">
    <section id="login">
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-xs-offset-3">
                    <div class="form-wrap">
                        <h1>Contact Us</h1>
                        <form role="form" action="registration.php" method="post" id="login-form" autocomplete="off">
                        <?php if (!empty($message)) { ?>
                            <div class="alert alert-danger no-data" role="alert">
                                <i class="fa fa-ban fa-fw"></i>&nbsp;&nbsp;<?=$message?>
                            </div>
                            <?php } ?>
                            <?php if (!empty($messageSuccess)) { ?>
                            <div class="alert alert-success no-data" role="alert">
                                <i class="fa fa-check fa-fw"></i>&nbsp;&nbsp;<?=$messageSuccess?>
                            </div>
                            <?php } ?>
                            <div class="form-group">
                                <label for="email">Email Address:</label>
                                <input type="email" name="tbEmail" id="email" class="form-control" placeholder="somebody@example.com">
                            </div>
                            <div class="form-group">
                                <label for="subject">Subject:</label>
                                <input type="text" name="tbSubject" id="subject" class="form-control">
                            </div>
                             <div class="form-group">
                                <label for="body">Message:</label>
                                <textarea name="tbBody" id="body" rows="6" class="form-control"></textarea>
                            </div>
                            <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Submit">
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <hr>
<?php include "includes/footer.php";?>