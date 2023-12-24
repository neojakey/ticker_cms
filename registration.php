<?php  include "includes/db.php"; ?>
<?php  include "includes/header.php"; ?>
<?php
if (isset($_POST["tbUsername"])) {
    /* DECLARE AND SET VARIABLES */
    $username = $_POST["tbUsername"];
    $userEmail = $_POST["tbEmail"];
    $userPassword = $_POST["tbPassword"];

    if (!empty($username) && !empty($userEmail) && !empty($userPassword)) {
        /* SANITIZE INPUT */
        $username = mysqli_real_escape_string($connection, $username);
        $userEmail = mysqli_real_escape_string($connection, $userEmail);
        $userPassword = mysqli_real_escape_string($connection, $userPassword);

        /* SECURE PASSWORD WITH HASH */
        $userPassword = password_hash($userPassword, PASSWORD_BCRYPT, array('cost' => 10));

        /* SAVE USER TO THE DATABASE */
        $query = <<<SQL
            INSERT INTO users(
                username,
                user_email,
                user_password,
                user_role
            ) VALUE(
                '{$username}',
                '{$userEmail}',
                '{$userPassword}',
                'Subscriber'
            )
        SQL;
        $addSubscriber = mysqli_query($connection, $query);
        if (!$addSubscriber) {
            die("Add Subscriber Failed: " . mysqli_error($connection));
        } else {
            $messageSuccess = "You have been successfully registered";
        }
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
                        <h1>Register</h1>
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
                                <label for="username">Username:</label>
                                <input type="text" name="tbUsername" id="username" class="form-control">
                            </div>
                             <div class="form-group">
                                <label for="email">Email Address:</label>
                                <input type="email" name="tbEmail" id="email" class="form-control" placeholder="somebody@example.com">
                            </div>
                             <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" name="tbPassword" id="key" class="form-control">
                            </div>

                            <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Register">
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <hr>
<?php include "includes/footer.php";?>