<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>
<?php
$usernameError = ""; $emailError = ""; $passwordError = "";
if (isset($_POST["tbUsername"])) {
    /* DECLARE AND SET VARIABLES */
    $username = $_POST["tbUsername"];
    $userEmail = $_POST["tbEmail"];
    $userPassword = $_POST["tbPassword"];

    /* DISPLAY MISSED ENTRIES ERRORS */
    if (empty($username)) {
        $usernameError = "Please enter the username";
    }
    if (empty($userEmail)) {
        $emailError = "Please enter the email address";
    }
    if (empty($userPassword)) {
        $passwordError = "Please enter the password";
    }

    if (!empty($username) && !empty($userEmail) && !empty($userPassword)) {
        if (usernameExists($username)) {
            /* IF USERNAME EXISTS SHOW ERROR */
            $message = "Username already exists, please try another";
        } elseif (userEmailExists($userEmail)) {
            /* IF EMAIL IS ALREADY ASSIGNED TO AN EXISTING USER */
            $message = "User already exists with this email address. <a href=\"./index.php\" style=\"color:#A94442; text-decoration:underline\">Login here</a>";
        } else {
            /* SANITIZE INPUT */
            $username = escape($username);
            $userEmail = escape($userEmail);
            $userPassword = escape($userPassword);

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
                $username = "";
                $userEmail = "";
                $userPassword = "";
            }
        }
    }
} else {
    $message = ""; $messageSuccess = "";
    $username = ""; $userEmail = ""; $userPassword = "";
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
                                <input type="text" name="tbUsername" id="username" autocomplete="off" value="<?=$username?>" class="form-control">
                                <small style="color:red"><?=$usernameError?></small>
                            </div>
                             <div class="form-group">
                                <label for="email">Email Address:</label>
                                <input type="email" name="tbEmail" id="email" autocomplete="off" value="<?=$userEmail?>" class="form-control" placeholder="e.g. somebody@example.com">
                                <small style="color:red"><?=$emailError?></small>
                            </div>
                             <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" name="tbPassword" id="key" autocomplete="new-password" class="form-control">
                                <small style="color:red"><?=$passwordError?></small>
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