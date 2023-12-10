<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php"><i class="fa fa-home"></i></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php
                    $query = "SELECT cat_id, cat_title FROM categories";
                    $response = mysqli_query($connection, $query);
                    while($categoriesRS = mysqli_fetch_assoc($response)) {
                        echo "<li><a href=\"#\">" . $categoriesRS["cat_title"] . "</a></li>";
                    }
                    ?>
                    <li><a href="./admin/">Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>