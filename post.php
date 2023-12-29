<?php include "includes/db.php" ?>
<?php include "includes/header.php" ?>
    <?php include "includes/navigation.php" ?>
    <?php
    $updateViews = "y";
    if (isset($_GET["view"])) {
        $updateViews = $_GET["view"];
    }

    if (isset($_POST["action"])) {
        /* DECLARE 'LIKE' VARIABLES AND SET THEM */
        $likeAction = $_POST["action"];
        $postId = $_POST["post_id"];
        $userId = $_POST["user_id"];

        if ($likeAction === "add") {
            /* SAVE LIKE FOR POST AND USER TO THE DATABASE */
            $query = "INSERT INTO likes (user_id, post_id) VALUE ({$userId}, {$postId})";
            $addLike = mysqli_query($connection, $query);
            if (!$addLike) {
                die("Add Like Failed: " . mysqli_error($connection));
            }
            $updateViews = false;
        } elseif ($likeAction === "delete") {
            /* DELETE LIKE RECORD FOR POST AND USER */
            $query = "DELETE FROM likes WHERE user_id = {$userId} AND post_id = {$postId}";
            $deleteLike = mysqli_query($connection, $query);
            if (!$deleteLike) {
                die("Delete Like Failed: " . mysqli_error($connection));
            }
            $updateViews = false;
        }
    }
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php
                /* CHECK ID IS BEING PASSED IF NOT RE-DIRECT USER */
                if (!isset($_GET["pid"]) || empty($_GET["pid"])) {
                    redirect("index.php");
                } else {
                    $postId = $_GET["pid"];
                }
                /* INCREASE VIEW COUNT */
                if ($updateViews === "y") {
                    $postCountSQL = "UPDATE posts SET post_views = post_views + 1 WHERE post_id = " . $postId;
                    $response = mysqli_query($connection, $postCountSQL);
                }

                /* MODIFY SQL BASED ON USER ROLE */
                $approvedSQL = "AND post_status = 'Approved'";
                if (isset($_SESSION["loggedRole"]) && $_SESSION["loggedRole"] == "Admin") {
                    $approvedSQL = "";
                }

                /* GET POST FROM THE DATABASE */
                $postsSQL = <<<SQL
                    SELECT
                        p.post_title,
                        IFNULL(p.post_image, 'no-image-2.jpg') AS post_image,
                        p.post_content, p.post_date,
                        p.post_views, p.post_author,
                        u.user_firstname, u.user_lastname,
                        (SELECT COUNT(*) FROM likes WHERE post_id = p.post_id) AS likes
                    FROM
                        posts AS p
                        INNER JOIN users AS u ON p.post_author = u.user_id
                    WHERE
                        post_id = {$postId}
                        {$approvedSQL}
                SQL;
                $response = mysqli_query($connection, $postsSQL);
                $postCount = mysqli_num_rows($response);
                if ($postCount > 0) {
                    $postsRS = mysqli_fetch_assoc($response);
                    ?>
                    <h2><?=$postsRS["post_title"]?></h2>
                    <p class="lead">
                        by <a href="<?=$root?>/author/<?=$postsRS["post_author"]?>"><?=$postsRS["user_firstname"] . " " . $postsRS["user_lastname"]?></a>
                    </p>
                    <div class="flex">
                        <span><span class="glyphicon glyphicon-time"></span> Posted on <?=$postsRS["post_date"]?></span>
                        <span>|</span>
                        <span><i class="fa fa-area-chart"></i>&nbsp;Views: <?=$postsRS["post_views"]?></span>
                        <span>|</span>
                        <span><span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;Likes: <?=$postsRS["likes"]?></span>
                    </div>
                    <hr>
                    <?php
                    if (strpos($postsRS["post_image"], "http") > -1) {
                        echo "<img src=\"{$postsRS["post_image"]}\" class=\"img-responsive\" alt=\"\"/>";
                    } else {
                        echo "<img src=\"{$root}/images/{$postsRS["post_image"]}\" class=\"img-responsive\" alt=\"\"/>";
                    }
                    ?>
                    <hr>
                    <p><?=$postsRS["post_content"]?></p>
                    <?php
                    if (isLoggedIn()) {
                        $likeExistsSQL = <<<SQL
                            SELECT 1 FROM
                                likes
                            WHERE
                                post_id = {$postId}
                                AND user_id = {$_SESSION["loggedUserId"]}
                        SQL;
                        $response = mysqli_query($connection, $likeExistsSQL);
                        $hasLike = mysqli_num_rows($response);
                        if ($hasLike === 0) {
                            $likeIcon = "up";
                            $likeLabel = "Like";
                            $likeAction = "add";
                        } else {
                            $likeIcon = "down";
                            $likeLabel = "Unlike";
                            $likeAction = "delete";
                        }
                        ?>
                        <div class="text-right">
                            <a href="javascript:void(0);" title="Click to <?=strtolower($likeLabel)?> this post" class="btn btn-primary" id="like-btn" data-action="<?=$likeAction?>"><span class="glyphicon glyphicon-thumbs-<?=$likeIcon?>"></span>&nbsp;<?=$likeLabel?></a>
                        </div>
                        <?php if ($_SESSION["loggedIsAdmin"]): ?>
                            <hr>
                            <b><i class="fa fa-cogs"></i>&nbsp;&nbsp;ADMIN TOOLS:</b>&nbsp;&nbsp;<a href="<?=$root?>/admin/posts.php?source=edit&pid=<?=$postId?>">Edit Post</a>
                        <?php endif; ?>
                    <?php } else { ?>
                        <div class="text-right">
                            You must <a href="<?=$root?>/login">Login</a> to like this post.
                        </div>
                    <?php } ?>
                    <hr>
                    <?php
                    if (isset($_POST["tbCommentAuthor"])) {
                        /* DECLARE AND SET VARIABLES */
                        $commentAuthor = escape($_POST["tbCommentAuthor"]);
                        $commentEmail = escape($_POST["tbCommentEmail"]);
                        $commentContent = escape($_POST["taCommentContent"]);

                        if (!empty($commentAuthor) && !empty($commentEmail) && !empty($commentContent)) {
                            /* CREATE AND EXECUTE SQL */
                            $query = <<<SQL
                                INSERT INTO comments(
                                    comment_author,
                                    comment_content,
                                    comment_email,
                                    comment_status,
                                    comment_date,
                                    comment_post_id
                                ) VALUE(
                                    '{$commentAuthor}',
                                    '{$commentContent}',
                                    '{$commentEmail}',
                                    'New',
                                    now(),
                                    {$postId}
                                )
                            SQL;
                            $add_comment = mysqli_query($connection, $query);
                            if (!$add_comment) {
                                die("Add Comment Failed: " . mysqli_error($connection));
                            }
                        } else {
                            echo "<script type=\"text/javascript\">alert('Please fill in all fields before submitting');</script>";
                        }
                    }
                    ?>
                    <div class="well">
                        <h4>Leave a Comment:</h4>
                        <form action="" method="post" role="form">
                            <div class="form-group">
                                <label for="comment-author">Author:</label>
                                <input type="text" id="comment-author" name="tbCommentAuthor" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label for="comment-email">Email:</label>
                                <input type="email" id="comment-email" name="tbCommentEmail" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label for="comment-content">Comments:</label>
                                <textarea id="comment-content" name="taCommentContent" rows="3" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                    <?php
                    $commentsSQL = <<<SQL
                        SELECT
                            comment_id, comment_author,
                            comment_date, comment_content
                        FROM
                            comments
                        WHERE
                            comment_post_id = $postId
                            AND comment_status = 'Approved'
                        ORDER BY
                            comment_id DESC
                    SQL;
                    $response = mysqli_query($connection, $commentsSQL);
                    while($commentsRS = mysqli_fetch_assoc($response)) {
                        ?>
                        <div class="media">
                            <a class="pull-left" href="#">
                                <img class="media-object" src="https://source.unsplash.com/random/64x64?sig=<?=$commentsRS["comment_id"]?>" alt="">
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading"><?=$commentsRS["comment_author"]?>
                                    <small><?=$commentsRS["comment_date"]?></small>
                                </h4>
                                <?=$commentsRS["comment_content"]?>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class=\"alert alert-info\" role=\"alert\"><i class=\"fa fa-info-circle\"></i>&nbsp;&nbsp;This post is unavailable...</div>";
                }
                ?>
            </div>
            <?php include "includes/sidebar.php" ?>
        </div>
        <hr>
    <?php include "includes/footer.php" ?>
    <script type="text/javascript">
        $(document).ready(function () {
            var root = '<?=$root?>';
            var postId = <?=$postId?>;
            var userId = <?=$_SESSION["loggedUserId"]?>;
            $('#like-btn').click(function () {
                var action = $(this).data('action');
                $.ajax({
                    url: `${root}/post.php?pid=${postId}`,
                    type: 'post',
                    data: {
                        'action': action,
                        'post_id': postId,
                        'user_id': userId
                    }
                }).done(function () {
                    location.href = `${root}/post.php?pid=${postId}&view=n`;
                });
            });
        });
    </script>