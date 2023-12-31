            <div class="col-md-4">
                <div class="well">
                    <h4>Blog Search</h4>
                    <form action="<?=$root?>/search" method="post">
                        <div class="input-group">
                            <input type="text" name="tbSearch" class="form-control">
                            <span class="input-group-btn">
                                <button type="submit" name="submit" class="btn btn-default">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
                <?php
                if (!isset($_SESSION["loggedRole"])): ?>
                    <div class="well">
                        <h4>Login Session</h4>
                        <form action="<?=$root?>/login" method="post">
                            <div class="form-group">
                                <label for="login-username">Username:</label>
                                <input type="text" autocomplete="off" name="tbUsername" id="login-username" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="login-password">Password:</label>
                                <input type="password" autocomplete="off" name="tbPassword" id="login-password" class="form-control">
                            </div>
                            <div class="flex">
                                <span><button type="submit" class="btn btn-primary">Log In</button></span>
                                <span><a href="<?=$root?>/forgot.php?forgot=<?=uniqid(true)?>">Forgot Password</a></span>
                            </div>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="well">
                        <h4>Logout Session</h4>
                        <p>Logged in as <?=$_SESSION["loggedFirstname"]?></p>
                        <a href="<?=$root?>/includes/logout.php" class="btn btn-primary">Logout</a>
                    </div>
                <?php endif; ?>
                <div class="well">
                    <h4>Blog Categories</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="list-unstyled">
                                <?php
                                /* MODIFY SQL BASED ON USER ROLE */
                                $approvedSQL = "AND post_status = 'Approved'";
                                if (isset($_SESSION["loggedRole"]) && $_SESSION["loggedRole"] == "Admin") {
                                    $approvedSQL = "";
                                }

                                $query = <<<SQL
                                    SELECT
                                        cat_id,
                                        cat_title,
                                        (SELECT
                                            COUNT(post_id)
                                        FROM
                                            posts
                                        WHERE
                                            post_category_id = c.cat_id
                                            {$approvedSQL}
                                        ) AS post_count
                                    FROM
                                        categories AS c
                                SQL;
                                $response = mysqli_query($connection, $query);
                                while($categoriesRS = mysqli_fetch_assoc($response)) {
                                    $categoryId = $categoriesRS["cat_id"];
                                    $categoryTitle = $categoriesRS["cat_title"];
                                    $postCount = $categoriesRS["post_count"];
                                    echo "<li><a href=\"{$root}/category/{$categoryId}\">{$categoryTitle}</a>&nbsp;[{$postCount}]</li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php include "./includes/widget.php" ?>
            </div>