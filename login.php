<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>
<?php
if (isLoggedIn()) {
    redirect("admin/");
}
?>
<?php include "includes/navigation.php" ?>
<?php
$username = ""; $message = ""; $usernameError = ""; $passwordError = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /* DECLARE AND SET VARIABLES */
    $username = $_POST["tbUsername"];
    $password = $_POST["tbPassword"];

    /* DISPLAY MISSED ENTRIES ERRORS */
    if (empty($username)) {
        $usernameError = "Please enter your username";
    }
    if (empty($password)) {
        $passwordError = "Please enter your password";
    }

    if (!empty($username) && !empty($password)) {
        /* SANITIZE INPUT */
        $username = escape($username);
        $password = escape($password);

        $query = "SELECT * FROM users WHERE username = '{$username}'";
        $response = mysqli_query($connection, $query);
        $numResult = mysqli_num_rows($response);
        if (!$response) {
            die("User login failed: " . mysqli_error($connection));
        } else {
            if ($numResult !== 0) {
                $userRS = mysqli_fetch_assoc($response);
                $userLoggedId = $userRS["user_id"];
                $userLoggedPassword = $userRS["user_password"];
                $userLoggedFirstname = $userRS["user_firstname"];
                $userLoggedLastname = $userRS["user_lastname"];
                $userLoggedRole = $userRS["user_role"];

                if (password_verify($password, $userLoggedPassword)) {
                    /* SET SESSION VARIABLES */
                    $_SESSION["loggedUsername"] = $username;
                    $_SESSION["loggedUserId"] = $userLoggedId;
                    $_SESSION["loggedFirstname"] = $userLoggedFirstname;
                    $_SESSION["loggedLastname"] = $userLoggedLastname;
                    $_SESSION["loggedRole"] = $userLoggedRole;
                    $_SESSION["loggedFullname"] = $userLoggedFirstname . " " . $userLoggedLastname;

                    $_SESSION["loggedIsAdmin"] = false;
                    if ($userLoggedRole === "Admin") {
                        $_SESSION["loggedIsAdmin"] = true;
                    }

                    redirect("admin/");
                } else {
                    $message = "Username or password incorrect";
                    //redirect("");
                }
            } else {
                $message = "User not found";
                //redirect("");
            }
        }
    }
}
?>
<!-- Page Content -->
<div class="container">
    <div class="form-gap"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">
                            <h3><i class="fa fa-user fa-4x"></i></h3>
                            <h2 class="text-center">Login</h2>
                            <div class="panel-body">
                                <?php if (!empty($message)): ?>
                                <div class="alert alert-danger no-data" role="alert">
                                    <i class="fa fa-ban fa-fw"></i>&nbsp;&nbsp;<?=$message?>
                                </div>
                                <?php endif; ?>
                                <form id="login-form" role="form" autocomplete="off" class="form" method="post">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-user color-blue"></i></span>
                                            <input type="text" name="tbUsername" autocomplete="off" value="<?=$username?>" class="form-control" placeholder="Enter username">
                                        </div>
                                        <small style="color:red"><?=$usernameError?></small>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                                            <input type="password" name="tbPassword" autocomplete="new-password" class="form-control" placeholder="Enter password">
                                        </div>
                                        <small style="color:red"><?=$passwordError?></small>
                                    </div>
                                    <div class="form-group">
                                        <input name="login" class="btn btn-lg btn-primary btn-block" value="Login" type="submit">
                                    </div>
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