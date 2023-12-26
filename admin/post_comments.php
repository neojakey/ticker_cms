<?php include "includes/admin_header.php" ?>
    <div id="wrapper">
        <?php include "includes/admin_navigation.php" ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        if (!isset($_GET["pid"])) {
                            header("Location: index.php");
                        } else {
                            $pid = $_GET["pid"];
                            $pid = escape($pid);

                            $postsSQL = "SELECT post_title FROM posts WHERE post_id = " . $pid;
                            $response = mysqli_query($connection, $postsSQL);
                            $postsRS = mysqli_fetch_assoc($response);
                        }
                        ?>
                        <h1 class="page-header">
                            Comment Management
                        </h1>
                        <div style="font-size: 16px; margin-bottom: 20px;">Comments for Post: <?=$postsRS["post_title"]?></div>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Author</th>
                                    <th>Comment</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Post</th>
                                    <th>Date</th>
                                    <th>Tools</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = <<<SQL
                                    SELECT
                                        c.comment_id,
                                        c.comment_author,
                                        c.comment_content,
                                        c.comment_email,
                                        c.comment_status,
                                        p.post_title,
                                        c.comment_date,
                                        c.comment_post_id
                                    FROM
                                        comments AS c
                                        INNER JOIN posts AS p ON c.comment_post_id = p.post_id
                                    WHERE
                                        p.post_id = {$pid}
                                SQL;
                                $response = mysqli_query($connection, $query);
                                $rowCount = mysqli_num_rows($response);
                                if ($rowCount > 0) {
                                    while($commentsRS = mysqli_fetch_assoc($response)) {
                                        ?>
                                        <tr>
                                            <td><?=$commentsRS["comment_id"]?></td>
                                            <td><?=$commentsRS["comment_author"]?></td>
                                            <td><?=$commentsRS["comment_content"]?></td>
                                            <td><a href="mailto:<?=$commentsRS["comment_email"]?>"><?=$commentsRS["comment_email"]?></a></td>
                                            <td><?=$commentsRS["comment_status"]?></td>
                                            <td><a href="../post.php?pid=<?=$commentsRS["comment_post_id"]?>" target="_blank" rel="noopener noreferrer"><?=$commentsRS["post_title"]?></a></td>
                                            <td><?=$commentsRS["comment_date"]?></td>
                                            <td style="width:30px">
                                                <div class="flex-icons">
                                                    <span>
                                                        <a href="post_comments.php?approve=<?=$commentsRS["comment_id"]?>&pid=<?=$pid?>" title="Click to approve comment">
                                                            <i class="fa fa-fw fa-check"></i></span>
                                                        </a>
                                                    </span>
                                                    <span>
                                                        <a href="post_comments.php?decline=<?=$commentsRS["comment_id"]?>&pid=<?=$pid?>" title="Click to decline comment">
                                                            <i class="fa fa-fw fa-ban"></i></span>
                                                        </a>
                                                    </span>
                                                    <span>
                                                        <a href="post_comments.php?delete=<?=$commentsRS["comment_id"]?>&pid=<?=$pid?>" title="Click to delete comment">
                                                            <i class="fa fa-fw fa-times"></i>
                                                        </a>
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="8">
                                            <div class="alert alert-info" role="alert" style="margin-bottom:0">
                                                <i class="fa fa-info-circle"></i>&nbsp;&nbsp;No comments found for this post
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php
                        /* APPROVE COMMENT */
                        if (isset($_GET["approve"])) {
                            $approveSQL = "UPDATE comments SET comment_status = 'Approved' WHERE comment_id = " . $_GET['approve'];
                            $approveRS = mysqli_query($connection, $approveSQL);
                            header("Location: post_comments.php?pid=" . $pid);
                        }

                        /* DECLINE COMMENT */
                        if (isset($_GET["decline"])) {
                            $declineSQL = "UPDATE comments SET comment_status = 'Declined' WHERE comment_id = " . $_GET['decline'];
                            $declineRS = mysqli_query($connection, $declineSQL);
                            header("Location: post_comments.php?pid=" . $pid);
                        }

                        /* DELETE COMMENT */
                        if (isset($_GET["delete"])) {
                            $deleteSQL = "DELETE FROM comments WHERE comment_id = " . $_GET['delete'];
                            $deleteRS = mysqli_query($connection, $deleteSQL);
                            header("Location: post_comments.php?pid=" . $pid);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include "includes/admin_footer.php" ?>