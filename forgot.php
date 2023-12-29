<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
require 'classes/config.php';
?>
<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>
<?php
if (isLoggedIn()) {
    redirect("admin/");
} else {
    if (!isset($_GET["forgot"])) {
        redirect("");
    }

    if (isset($_POST["tbEmail"])) {
        $emailAddress = escape($_POST["tbEmail"]);
        $token = bin2hex(openssl_random_pseudo_bytes(50));
        $resetUrl = "http://localhost:8080/ticker_cms/reset.php?email=" . $emailAddress . "&token=" . $token;
        if (userEmailExists($emailAddress)) {
            $updateSQL = "UPDATE users SET token = '{$token}' WHERE user_email = ?";
            if ($stmt = mysqli_prepare($connection, $updateSQL)) {;
                mysqli_stmt_bind_param($stmt, "s", $emailAddress);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                /* CONFIGURE PHPMAILER */
                $mail = new PHPMailer(true);

                try {
                    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    $mail->isSMTP();
                    $mail->Host     = config::SMTP_HOST;
                    $mail->SMTPAuth = config::SMTP_AUTH;
                    $mail->Username = config::SMTP_USER;
                    $mail->Password = config::SMTP_PASSWORD;
                    $mail->Port     = config::SMTP_PORT;

                    $mail->setFrom('paul.d.jacobs@outlook.com', 'Paul Jacobs');
                    $mail->addAddress($emailAddress);

                    $mail->isHTML(true);
                    $mail->CharSet = "UTF-8";
                    $mail->Subject = "Scoping Tech - CMS - Password Reset";
                    $mail->Body    = "Hello,<br/><br/>Scoping-Tech received a request to reset your password.<br/><br/>
                    To reset your password, click this link or copy the URL below and paste it into your web browser's navigation bar:<br/><br/>
                    <a href=\"" . $resetUrl . "\">" . $resetUrl . "</a><br/><br/>
                    You will need to enter your new password both at that URL and again to log in to your account. Keep this in mind, and be sure you enter your password correctly both times.<br/><br/>
                    If you did not request this password reset, please ignore this email.<br/><br/>
                    Sincerely,<br/>Administrator<br/><a href=\"http://www.scoping-tech.com/\">http://www.scoping-tech.com</a>";

                    if ($mail->send()) {
                        $successMessage = 'Reset email has been sent';
                    }
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }

            }
        }
    }
}
?>
<?php include "includes/navigation.php" ?>
<div class="container">
    <div class="form-gap"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">
                            <?php if (!isset($successMessage)): ?>
                            <h3><i class="fa fa-lock fa-4x"></i></h3>
                            <h2 class="text-center">Forgot Password?</h2>
                            <p>You can reset your password here.</p>
                            <div class="panel-body">
                                <form id="register-form" role="form" autocomplete="off" class="form" method="post">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                            <input id="email" name="tbEmail" placeholder="email address" class="form-control"  type="email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
                                    </div>
                                    <input type="hidden" class="hide" name="token" id="token" value="">
                                </form>
                            </div>
                            <?php else: ?>
                            <div class="alert alert-success" role="alert" id="save-alert">
                                <i class="fa fa-check-circle"></i>&nbsp;&nbsp;<?=$successMessage?>
                            </div>
                            <div class="text-center">
                                <a href="./" style="color:#3C763D">Go to Homepage</a></div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <?php include "includes/footer.php";?>
</div>