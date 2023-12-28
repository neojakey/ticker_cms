<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?=$root?>/"><i class="fa fa-home"></i></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php
                    $pageName = basename($_SERVER["PHP_SELF"]);
                    $query = "SELECT cat_id, cat_title FROM categories";
                    $response = mysqli_query($connection, $query);
                    while($categoriesRS = mysqli_fetch_assoc($response)) {
                        $categoryClass = "";
                        if (isset($_GET["cid"]) && $_GET["cid"] == $categoriesRS["cat_id"]) {
                            $categoryClass = " active";
                        }
                        echo "<li class=\"nav-link{$categoryClass}\"><a href=\"{$root}/category/{$categoriesRS["cat_id"]}\">{$categoriesRS["cat_title"]}</a></li>";
                    }
                    if (isset($_SESSION["loggedRole"])) {
                        if (!$_SESSION["loggedIsAdmin"]) {
                            ?><li><a href="<?=$root?>/includes/logout.php">Log Out</a></li><?php
                        } else {
                            ?>
                            <li><a href="<?=$root?>/admin/">Admin</a></li>
                            <li><a href="<?=$root?>/includes/logout.php">Log Out</a></li>
                            <?php
                        }
                    } else {
                        $registrationClass = "";
                        if ($pageName == "registration.php") {
                            $registrationClass = "active";
                        }
                        ?><li class="<?=$registrationClass?>"><a href="<?=$root?>/registration">Registration</a></li><?php
                    }
                    $contactClass = "";
                    if ($pageName == "contact.php") {
                        $contactClass = "active";
                    }
                    ?>
                    <li class="<?=$contactClass?>"><a href="<?=$root?>/contact">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </nav>