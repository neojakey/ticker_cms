<?php include "includes/db.php" ?>
<?php include "includes/header.php" ?>
    <?php include "includes/navigation.php" ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php
                /* DEFINE AND SET PAGINATION VARIABLES */
                $pg = 1;
                $offset = 0;
                if (isset($_GET["pg"])) {
                    $pg = $_GET["pg"];
                    $offset = ($pg * 5) - 5;
                }

                /* GET AND CALCULATION PAGINATION */
                $postCountSQL = <<<SQL
                    SELECT
                        COUNT(*) AS postCount
                    FROM
                        posts
                    WHERE
                        post_status = 'Approved'
                SQL;
                $response = mysqli_query($connection, $postCountSQL);
                $postCountRS = mysqli_fetch_assoc($response);
                $postCount = $postCountRS["postCount"];
                $postCount = ceil($postCount / 5);

                /* FAIL SAFE IF PAGE NUM IS GREATER THAN MAXIMUM */
                if (intval($pg) > intval($postCount)) {
                    $pg = $postCount;
                    $offset = ($pg * 5) - 5;
                }

                /* GET POSTS FROM THE DATABASE */
                $postsSQL = <<<SQL
                    SELECT
                        p.post_id,
                        p.post_title,
                        p.post_author,
                        p.post_date,
                        p.post_image,
                        c.cat_title,
                        p.post_category_id AS cat_id,
                        post_content
                    FROM
                        posts AS p
                        INNER JOIN categories AS c ON p.post_category_id = c.cat_id
                    WHERE
                        p.post_status = 'Approved'
                    LIMIT
                        $offset, 5
                SQL;
                $response = mysqli_query($connection, $postsSQL);
                while($postsRS = mysqli_fetch_assoc($response)) {
                    ?>
                    <h1 class="page-header">
                        Scoping Tech
                        <small>An in-depth look at technology</small>
                    </h1>
                    <h2>
                        <a href="post.php?pid=<?=$postsRS["post_id"]?>"><?=$postsRS["post_title"]?></a>
                    </h2>
                    <p class="lead">
                        by <a href="author_posts.php?author=<?=$postsRS["post_author"]?>"><?=$postsRS["post_author"]?></a>
                    </p>
                    <p><i class="fa fa-fw fa-clock-o"></i>&nbsp;Posted on <?=$postsRS["post_date"]?></p>
                    <p><i class="fa fa-fw fa-tags"></i>&nbsp;Category: <a href="category.php?cid=<?=$postsRS["cat_id"]?>"><?=$postsRS["cat_title"]?></a></p>
                    <hr>
                    <a href="post.php?pid=<?=$postsRS["post_id"]?>"><?php
                    if (strpos($postsRS["post_image"], "http") > -1) {
                        echo "<img src=\"" . $postsRS["post_image"] . "\" class=\"img-responsive\" alt=\"\"/>";
                    } else {
                        echo "<img src=\"images/" . $postsRS["post_image"] . "\" class=\"img-responsive\" alt=\"\"/>";
                    }
                    ?></a>
                    <hr>
                    <p><?=substr($postsRS["post_content"], 0, 300)?>...</p>
                    <a class="btn btn-primary" href="post.php?pid=<?=$postsRS["post_id"]?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
                    <hr>
                    <?php
                }
                ?>
                <ul class="pagination">
                    <?php
                    if (intval($pg) === 1) {
                        echo "<li class=\"disabled\"><a href=\"javascript:void(0);\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo;</span></a></li>";
                    } else {
                        $previousPage = ($pg - 1);
                        echo "<li><a href=\"index.php?pg={$previousPage}\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo;</span></a></li>";
                    }
                    for ($i = 1; $i <= $postCount; $i++) {
                        if ($i == $pg) {
                            echo "<li class=\"active\"><a href=\"index.php?pg={$i}\">{$i}</a></li>";
                        } else {
                            echo "<li><a href=\"index.php?pg={$i}\">{$i}</a></li>";
                        }
                    }
                    if (intval($pg) === intval($postCount)) {
                        echo "<li class=\"disabled\"><a href=\"javascript:void(0);\" aria-label=\"Previous\"><span aria-hidden=\"true\">&raquo;</span></a></li>";
                    } else {
                        $nextPage = ($pg + 1);
                        echo "<li><a href=\"index.php?pg={$nextPage}\" aria-label=\"Previous\"><span aria-hidden=\"true\">&raquo;</span></a></li>";
                    }
                    ?>
                </ul>
            </div>
            <?php include "includes/sidebar.php" ?>
        </div>
        <hr>
<?php include "includes/footer.php" ?>