<?php include "./db.php"; ?>
<?php include "./functions.php"; ?>
<?php session_start(); ?>
<?php
if (isset($_POST["tbUsername"])) {
    /* DECLARE AND SET VARIABLES */
    $username = $_POST["tbUsername"];
    $password = $_POST["tbPassword"];

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

                $_SESSION["loggedIsAdmin"] = false;
                if ($userLoggedRole === "Admin") {
                    $_SESSION["loggedIsAdmin"] = true;
                }

                header("Location: ../admin/index.php");
            } else {
                header("Location: ../index.php");
            }
        } else {
            header("Location: ../index.php");
        }
    }
}
?>