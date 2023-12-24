<?php include "includes/db.php" ?>
<?php include "includes/header.php" ?>
    <?php include "includes/navigation.php" ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php
                /* CHECK ID IS BEING PASSED IF NOT RE-DIRECT USER */
                if (!isset($_GET["cid"]) || empty($_GET["cid"])) {
                    header("Location: index.php");
                }
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
                        p.post_category_id = {$_GET["cid"]}
                        AND p.post_status = 'Approved'
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
                        by <a href="author_posts.php?author=<?=$postsRS["post_author"]?>"><?=$postsRS["user_firstname"] . " " . $postsRS["user_lastname"]?></a>
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
                    <p><?=substr($postsRS["post_content"], 0, 300)?>...</p>
                    <a class="btn btn-primary" href="post.php?pid=<?=$postsRS["post_id"]?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
                    <hr>
                    <?php
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