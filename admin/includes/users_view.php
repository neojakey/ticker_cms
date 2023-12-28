                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Username</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Tools</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = <<<SQL
                                    SELECT
                                        u.user_id,
                                        u.username,
                                        u.user_firstname,
                                        u.user_lastname,
                                        u.user_email,
                                        u.user_image,
                                        u.user_role
                                    FROM
                                        users AS u
                                SQL;
                                $response = mysqli_query($connection, $query);
                                while($usersRS = mysqli_fetch_assoc($response)) {
                                    ?>
                                    <tr>
                                        <td><?=$usersRS["user_id"]?></td>
                                        <td>
                                            <div class="flex-icons">
                                                <span><?php
                                                    if (!empty($usersRS["user_image"])) {
                                                        if (strpos($usersRS["user_image"], "http") > -1) {
                                                            echo "<img src=\"" . $usersRS["user_image"] . "\" style=\"width:20px\" alt=\"\"/>";
                                                        } else {
                                                            echo "<img src=\"../images/users/" . $usersRS["user_image"] . "\" style=\"width:20px\" alt=\"\"/>";
                                                        }
                                                    }
                                                ?></span>
                                                <span><?=$usersRS["username"]?></span>
                                            </div>
                                        </td>
                                        <td><?=$usersRS["user_firstname"]?></td>
                                        <td><?=$usersRS["user_lastname"]?></td>
                                        <td><a href="mailto:<?=$usersRS["user_email"]?>"><?=$usersRS["user_email"]?></a></td>
                                        <td><?=$usersRS["user_role"]?></td>
                                        <td style="width:30px">
                                            <div class="flex-icons">
                                                <span>
                                                    <a href="users.php?source=edit&uid=<?=$usersRS["user_id"]?>" title="Click to edit user">
                                                        <i class="fa fa-fw fa-pencil"></i></span>
                                                    </a>
                                                </span>
                                                <span>
                                                    <a href="javascript:void(0);" onclick="ConfirmUserDelete(<?=$usersRS['user_id']?>);" title="Click to delete user">
                                                        <i class="fa fa-fw fa-times"></i>
                                                    </a>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php
                        /* DELETE USER */
                        if (isset($_GET["delete"])) {
                            if (isset($_SESSION["loggedRole"])) {
                                if ($_SESSION["loggedRole"] === "Admin") {
                                    $deleteSQL = "DELETE FROM users WHERE user_id = " . $_GET['delete'];
                                    $deleteRS = mysqli_query($connection, $deleteSQL);
                                    redirect("users.php");
                                }
                            }
                        }
                        ?>