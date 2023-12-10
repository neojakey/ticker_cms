<?php include "includes/db.php" ?>
<?php include "includes/header.php" ?>
    <?php include "includes/navigation.php" ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php
                if (isset($_POST['tbSearch'])) {
                    $searchQuery = $_POST['tbSearch'];
                    $postsSQL = <<<SQL
                        SELECT
                            p.post_id, p.post_title,
                            p.post_author, p.post_date,
                            p.post_image, c.cat_title,
                            p.post_category_id AS cat_id,
                            post_content
                        FROM
                            posts AS p
                            INNER JOIN categories AS c ON p.post_category_id = c.cat_id
                        WHERE
                            post_tags
                        LIKE
                            '%$searchQuery%'
                    SQL;
                } else {
                    $postsSQL = <<<SQL
                        SELECT
                            p.post_id, p.post_title,
                            p.post_author, p.post_date,
                            p.post_image, c.cat_title,
                            p.post_category_id AS cat_id,
                            post_content
                        FROM
                            posts AS p
                            INNER JOIN categories AS c ON p.post_category_id = c.cat_id
                        WHERE
                            post_tags
                    SQL;
                }
                $response = mysqli_query($connection, $postsSQL);
                $numResult = mysqli_num_rows($response);
                if ($numResult == 0) {
                    echo "<h1>No Results Found</h1>";
                } else {
                    while($postsRS = mysqli_fetch_assoc($response)) {
                        ?>
                        <h1 class="page-header">
                            Scoping Tech
                            <small>An in-depth look at technology</small>
                        </h1>
                        <h2>
                            <a href="#"><?=$postsRS["post_title"]?></a>
                        </h2>
                        <p class="lead">
                            by <a href="index.php"><?=$postsRS["post_author"]?></a>
                        </p>
                        <p><i class="fa fa-fw fa-clock-o"></i>&nbsp;Posted on <?=$postsRS["post_date"]?></p>
                        <p><i class="fa fa-fw fa-tags"></i>&nbsp;Category: <a href="category.php?cid=<?=$postsRS["cat_id"]?>"><?=$postsRS["cat_title"]?></a></p>
                        <hr>
                        <?php
                        if (strpos($postsRS["post_image"], "http") > -1) {
                            echo "<img src=\"" . $postsRS["post_image"] . "\" class=\"img-responsive\" alt=\"\"/>";
                        } else {
                            echo "<img src=\"images/" . $postsRS["post_image"] . "\" class=\"img-responsive\" alt=\"\"/>";
                        }
                        ?>
                        <hr>
                        <p><?=$postsRS["post_content"]?></p>
                        <a class="btn btn-primary" href="#">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

                        <hr>
                        <?php
                    }
                }
                ?>
                <ul class="pager">
                    <li class="previous">
                        <a href="#">&larr; Older</a>
                    </li>
                    <li class="next">
                        <a href="#">Newer &rarr;</a>
                    </li>
                </ul>
            </div>
            <?php include "includes/sidebar.php" ?>
        </div>
        <hr>
<?php include "includes/footer.php" ?>