<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>
<?php
if (!isset($_GET["email"]) && !isset($_GET["token"])) {
    redirect("./");
}

$errorMessage = "";
$email = escape($_GET["email"]);
$token = escape($_GET["token"]);

$selectSQL = <<<SQL
    SELECT
        user_id, user_email
    FROM
        users
    WHERE
        token = '{$token}'
SQL;
$response = mysqli_query($connection, $selectSQL);
$rowCount = mysqli_num_rows($response);
if ($rowCount > 0) {
    $results = mysqli_fetch_assoc($response);
    $dbEmail = $results["user_email"];
    $dbUserId = $results["user_id"];
    if ($email !== $dbEmail) {
        redirect("./");
    }

    if (isset($_POST["tbPassword"]) && isset($_POST["tbConfirmPassword"])) {
        if ($_POST["tbPassword"] === $_POST["tbConfirmPassword"]) {
            $password = escape($_POST["tbPassword"]);
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT, array('cost' => 10));

            $query = <<<SQL
                UPDATE users SET
                    user_password = '{$hashedPassword}',
                    token = NULL
                WHERE
                    user_id = $dbUserId
            SQL;
            $editPassword = mysqli_query($connection, $query);
            if (!$editPassword) {
                die("Edit User Failed: " . mysqli_error($connection));
            } else {
                redirect("./login");
            }
        } else {
            $errorMessage = "Password and confirmation password do not match";
        }
    }
    ?>
    <div class="container">
        <div class="form-gap"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="text-center">
                                <h2 class="text-center">Reset Password?</h2>
                                <p>You can reset your password here.</p>
                                <div class="panel-body">
                                    <?php if ($errorMessage !== ""): ?>
                                    <div class="alert alert-danger no-data" role="alert">
                                        <i class="fa fa-ban fa-fw"></i>&nbsp;&nbsp;<?=$errorMessage?>
                                    </div>
                                    <?php endif; ?>
                                    <form id="register-form" role="form" autocomplete="off" class="form" method="post">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-user color-blue"></i></span>
                                                <input id="password" name="tbPassword" placeholder="Enter password" class="form-control" type="password">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-ok color-blue"></i></span>
                                                <input id="confirm-password" name="tbConfirmPassword" placeholder="Confirm password" class="form-control"  type="password">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
                                        </div>
                                        <input type="hidden" class="hide" name="token" id="token" value="">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <?php include "includes/footer.php";?>
    </div>
    <?php
} else {
    redirect("./");
}
?>