<?php
if (isset($_POST["tbUsername"])) {
    /* DECLARE AND SET VARIABLES */
    $username = escape($_POST["tbUsername"]);
    $userFirstName = escape($_POST["tbFirstname"]);
    $userLastName = escape($_POST["tbLastname"]);
    $userEmail = escape($_POST["tbEmail"]);
    $userRole = $_POST["ddRole"];
    $userImage = $_FILES["fileImage"]["name"];
    $userImageTemp = $_FILES["fileImage"]["tmp_name"];
    $userPassword = escape($_POST["tbPassword"]);

    /* SECURE PASSWORD WITH HASH */
    $userPassword = password_hash($userPassword, PASSWORD_BCRYPT, array('cost' => 10));

    /* UPLOAD IMAGE IF FOUND */
    move_uploaded_file($userImageTemp, "../images/users/$userImage");

    /* SAVE USER TO THE DATABASE */
    $query = <<<SQL
        INSERT INTO users(
            username,
            user_password,
            user_firstname,
            user_lastname,
            user_email,
            user_image,
            user_role
        ) VALUE(
            '{$username}',
            '{$userPassword}',
            '{$userFirstName}',
            '{$userLastName}',
            '{$userEmail}',
            '{$userImage}',
            '{$userRole}'
        )
    SQL;
    $addUser = mysqli_query($connection, $query);
    if (!$addUser) {
        die("Add User Failed: " . mysqli_error($connection));
    } else {
        redirect("users.php?n=y");
    }
}
?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" id="username" name="tbUsername"/>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="text" class="form-control" id="password" name="tbPassword">
    </div>
    <div class="row">
        <div class="form-group col-xs-6">
            <label for="firstname">First Name:</label>
            <input type="text" class="form-control" id="firstname" name="tbFirstname"/>
        </div>
        <div class="form-group col-xs-6">
            <label for="lastname">Last Name:</label>
            <input type="text" class="form-control" id="lastname" name="tbLastname"/>
        </div>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="tbEmail"/>
    </div>
    <div class="form-group">
        <label for="role">User Role:</label>
        <select class="form-control" id="role" name="ddRole">
            <option value="">Select One...</option>
            <option value="Subscriber">Subscriber</option>
            <option value="Admin">Admin</option>
        </select>
    </div>
    <div class="form-group">
        <label for="user-image">User Image:</label>
        <input type="file" id="user-image" name="fileImage"/>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Save User</button>
        <button type="button" class="btn btn-cancel" onclick="location.href='./users.php';">Cancel</button>
    </div>
</form>