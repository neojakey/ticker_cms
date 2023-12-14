<?php include "includes/admin_header.php" ?>
    <?php
    /* IF FORM WAS SUBMITTED */
    if (isset($_POST["hidUserId"])) {
        /* DECLARE AND SET VARIABLES */
        $userId = $_POST["hidUserId"];
        $username = $_POST["tbUsername"];
        $userFirstName = $_POST["tbFirstname"];
        $userLastName = $_POST["tbLastname"];
        $userEmail = $_POST["tbEmail"];
        $userImage = $_FILES["fileImage"]["name"];
        $userImageTemp = $_FILES["fileImage"]["tmp_name"];
        $userRole = $_POST["ddRole"];

        /* SANITIZE INPUT */
        $username = mysqli_real_escape_string($connection, $username);
        $userFirstName = mysqli_real_escape_string($connection, $userFirstName);
        $userLastName = mysqli_real_escape_string($connection, $userLastName);
        $userEmail = mysqli_real_escape_string($connection, $userEmail);

        /* UPLOAD IMAGE IF FOUND */
        if (!empty($userImage)) { // IMAGE WAS INCLUDED
            move_uploaded_file($userImageTemp, "../images/users/$userImage");
            /* UPDATE POST IN THE DATABASE */
            $query = <<<SQL
                UPDATE users SET
                    username = '{$username}', user_firstname = '{$userFirstName}',
                    user_lastname = '{$userLastName}', user_image = '{$userImage}',
                    user_email = '{$userEmail}', user_role = '{$userRole}'
                WHERE
                    user_id = $userId
            SQL;
        } else {
            $query = <<<SQL
                UPDATE users SET
                    username = '{$username}', user_firstname = '{$userFirstName}',
                    user_lastname = '{$userLastName}', user_email = '{$userEmail}',
                    user_role = '{$userRole}'
                WHERE
                    user_id = $userId
            SQL;
        }
        $editUser = mysqli_query($connection, $query);
        if (!$editUser) {
            die("Edit User Failed: " . mysqli_error($connection));
        } else {
            $_SESSION["loggedUsername"] = $username;
            $_SESSION["loggedFirstname"] = $userFirstName;
            $_SESSION["loggedLastname"] = $userLastName;
            $_SESSION["loggedRole"] = $userRole;
        }
    }
    ?>
    <div id="wrapper">
        <?php include "includes/admin_navigation.php" ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            User Profile
                        </h1>
                        <?php
                        $query = <<<SQL
                            SELECT
                                user_id, user_image, user_firstname,
                                user_lastname, user_email, user_role
                            FROM
                                users
                            WHERE
                                username = '{$_SESSION["loggedUsername"]}'
                        SQL;
                        $response = mysqli_query($connection, $query);
                        $userRS = mysqli_fetch_assoc($response);
                        ?>
                        <?php if (isset($_POST["hidUserId"])) { ?>
                        <div class="alert alert-success" role="alert" id="save-alert">
                            <i class="fa fa-check\"></i>&nbsp;&nbsp;Changes saved successfully..!
                        </div>
                        <?php } ?>
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="hidUserId" value="<?=$userRS["user_id"]?>"/>
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="tbUsername" value="<?=$_SESSION["loggedUsername"]?>"/>
                            </div>
                            <div class="row">
                                <div class="form-group col-xs-6">
                                    <label for="firstname">First Name:</label>
                                    <input type="text" class="form-control" id="firstname" name="tbFirstname" value="<?=$userRS["user_firstname"]?>"/>
                                </div>
                                <div class="form-group col-xs-6">
                                    <label for="lastname">Last Name:</label>
                                    <input type="text" class="form-control" id="lastname" name="tbLastname" value="<?=$userRS["user_lastname"]?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="tbEmail" value="<?=$userRS["user_email"]?>"/>
                            </div>
                            <div class="form-group">
                                <label for="role">User Role:</label>
                                <select class="form-control" id="role" name="ddRole">
                                    <option value="">Select One...</option>
                                    <option value="Subscriber"<?php if ($userRS["user_role"] == "Subscriber") { echo " selected=\"selected\""; } ?>>Subscriber</option>
                                    <option value="Admin"<?php if ($userRS["user_role"] == "Admin") { echo " selected=\"selected\""; } ?>>Admin</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="form-group col-xs-6">
                                    <label for="user-image">User Image</label>
                                    <input type="file" id="user-image" name="fileImage"/>
                                </div>
                                <div class="form-group col-xs-6">
                                    <?php
                                    if (!empty($userRS["user_image"])) {
                                        if (strpos($userRS["user_image"], "http") > -1) {
                                            echo "<img src=\"" . $userRS["user_image"] . "\" style=\"width:40px\" alt=\"\"/>";
                                        } else {
                                            echo "<img src=\"../images/users/" . $userRS["user_image"] . "\" style=\"width:40px\" alt=\"\"/>";
                                        }
                                    } else {
                                        echo "&nbsp;";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group" style="margin-top:15px">
                                <button type="submit" class="btn btn-primary">Save Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include "includes/admin_footer.php" ?>