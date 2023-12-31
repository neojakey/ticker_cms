<?php
/* IF FORM WAS SUBMITTED */
if (isset($_POST["hidUserId"])) {
    /* DECLARE AND SET VARIABLES */
    $userId = $_POST["hidUserId"];
    $username = escape($_POST["tbUsername"]);
    $userFirstName = escape($_POST["tbFirstname"]);
    $userLastName = escape($_POST["tbLastname"]);
    $userEmail = escape($_POST["tbEmail"]);
    $userPassword = escape($_POST["tbPassword"]);
    $userImage = $_FILES["fileImage"]["name"];
    $userImageTemp = $_FILES["fileImage"]["tmp_name"];
    $userRole = $_POST["ddRole"];

    /* PREPARE PASSWORD */
    $passwordSQL = "";
    if (!empty($userPassword)) {
        /* SECURE PASSWORD WITH HASH */
        $userPassword = password_hash($userPassword, PASSWORD_BCRYPT, array('cost' => 10));

        /* GENERATE SQL */
        $passwordSQL = "user_password = '{$userPassword}',";
    }

    /* UPLOAD IMAGE IF FOUND */
    $imageSQL = "";
    if (!empty($userImage)) { // IMAGE WAS INCLUDED
        move_uploaded_file($userImageTemp, "../images/users/$userImage");
        $imageSQL = "user_image = '{$userImage}',";
    }
    /* UPDATE POST IN THE DATABASE */
    $query = <<<SQL
        UPDATE users SET
            username = '{$username}', user_firstname = '{$userFirstName}',
            user_lastname = '{$userLastName}', {$imageSQL}
            {$passwordSQL} user_email = '{$userEmail}', user_role = '{$userRole}'
        WHERE
            user_id = $userId
    SQL;
    $editUser = mysqli_query($connection, $query);
    if (!$editUser) {
        die("Edit User Failed: " . mysqli_error($connection));
    } else {
        redirect("users.php?e=y");
    }
}

if (isset($_GET["uid"])) {
    $query = <<<SQL
        SELECT
            user_id, user_image, username, user_firstname,
            user_lastname, user_email, user_role
        FROM
            users
        WHERE
            user_id = {$_GET["uid"]}
    SQL;
    $response = mysqli_query($connection, $query);
    $usersRS = mysqli_fetch_assoc($response);
} else {
    redirect("index.php");
}
?>
<form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="hidUserId" value="<?=$_GET["uid"]?>"/>
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" id="username" name="tbUsername" value="<?=$usersRS["username"]?>"/>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="text" autocomplete="off" class="form-control" id="password" name="tbPassword">
        <div style="margin-top:5px; color:#5bc0de; font-size:10px">(Only enter to change existing password, otherwise leave blank)</div>
    </div>
    <div class="row">
        <div class="form-group col-xs-6">
            <label for="firstname">First Name:</label>
            <input type="text" class="form-control" id="firstname" name="tbFirstname" value="<?=$usersRS["user_firstname"]?>"/>
        </div>
        <div class="form-group col-xs-6">
            <label for="lastname">Last Name:</label>
            <input type="text" class="form-control" id="lastname" name="tbLastname" value="<?=$usersRS["user_lastname"]?>"/>
        </div>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="tbEmail" value="<?=$usersRS["user_email"]?>"/>
    </div>
    <div class="form-group">
        <label for="role">User Role:</label>
        <select class="form-control" id="role" name="ddRole">
            <option value="">Select One...</option>
            <option value="Subscriber"<?php if ($usersRS["user_role"] == "Subscriber") { echo " selected=\"selected\""; } ?>>Subscriber</option>
            <option value="Admin"<?php if ($usersRS["user_role"] == "Admin") { echo " selected=\"selected\""; } ?>>Admin</option>
        </select>
    </div>
    <div class="row">
        <div class="form-group col-xs-6">
            <label for="user-image">User Image</label>
            <input type="file" id="user-image" name="fileImage"/>
        </div>
        <div class="form-group col-xs-6">
            <?php
            if (!empty($usersRS["user_image"])) {
                if (strpos($usersRS["user_image"], "http") > -1) {
                    echo "<img src=\"" . $usersRS["user_image"] . "\" style=\"width:40px\" alt=\"\"/>";
                } else {
                    echo "<img src=\"../images/users/" . $usersRS["user_image"] . "\" style=\"width:40px\" alt=\"\"/>";
                }
            } else {
                echo "&nbsp;";
            }
            ?>
        </div>
    </div>
    <div class="form-group" style="margin-top:15px">
        <button type="submit" class="btn btn-primary">Save User</button>
        <button type="button" class="btn btn-cancel" onclick="location.href='./users.php';">Cancel</button>
    </div>
</form>