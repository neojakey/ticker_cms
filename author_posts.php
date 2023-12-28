<?php include "includes/db.php" ?>
<?php include "includes/header.php" ?>
    <?php include "includes/navigation.php" ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1 class="page-header">
                    Scoping Tech
                    <small>An in-depth look at technology</small>
                </h1>
                <?php
                if (isset($_GET["author"])) {
                    $postsSQL = <<<SQL
                        SELECT
                            p.post_id,
                            p.post_title,
                            p.post_author,
                            p.post_date,
                            p.post_image,
                            c.cat_title,
                            p.post_category_id AS cat_id,
                            p.post_content,
                            u.user_firstname,
                            u.user_lastname
                        FROM
                            posts AS p
                            INNER JOIN categories AS c ON p.post_category_id = c.cat_id
                            INNER JOIN users AS u ON p.post_author = u.user_id
                        WHERE
                            p.post_author = '{$_GET["author"]}'
                    SQL;
                } else {
                    header("Location: index.php");
                }
                $response = mysqli_query($connection, $postsSQL);
                $numResult = mysqli_num_rows($response);
                if ($numResult == 0) {
                    echo "<h1>No Results Found</h1>";
                } else {
                    while($postsRS = mysqli_fetch_assoc($response)) {
                        ?>
                        <h2>
                            <a href="<?=$root?>/post/<?=$postsRS["post_id"]?>"><?=$postsRS["post_title"]?></a>
                        </h2>
                        <p class="lead">
                            post by <?=$postsRS["user_firstname"] . " " . $postsRS["user_lastname"]?>
                        </p>
                        <p><i class="fa fa-fw fa-clock-o"></i>&nbsp;Posted on <?=$postsRS["post_date"]?></p>
                        <p><i class="fa fa-fw fa-tags"></i>&nbsp;Category: <a href="<?=$root?>/category/<?=$postsRS["cat_id"]?>"><?=$postsRS["cat_title"]?></a></p>
                        <hr>
                        <?php
                        if (strpos($postsRS["post_image"], "http") > -1) {
                            echo "<img src=\"{$postsRS["post_image"]}\" class=\"img-responsive\" alt=\"\"/>";
                        } else {
                            echo "<img src=\"{$root}/images/{$postsRS["post_image"]}\" class=\"img-responsive\" alt=\"\"/>";
                        }
                        ?>
                        <hr>
                        <p><?=substr($postsRS["post_content"], 0, 300)?>...</p>
                        <a class="btn btn-primary" href="<?=$root?>/post/<?=$postsRS["post_id"]?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
                        <hr>
                        <?php
                    }
                }
                ?>
            </div>
            <?php include "includes/sidebar.php" ?>
        </div>
        <hr>
<?php include "includes/footer.php" ?>