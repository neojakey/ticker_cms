                    <?php
                    if (isset($_POST["checkBoxArray"])) {
                        $bulkOptions = $_POST["ddBulkOptions"];
                        foreach($_POST["checkBoxArray"] as $checkboxValues) {
                            switch($bulkOptions) {
                                case "draft";
                                    $bulkQuery = "UPDATE posts SET post_status = 'Draft' WHERE post_id = {$checkboxValues}";
                                    $response = mysqli_query($connection, $bulkQuery);
                                    break;
                                case "approve";
                                    $bulkQuery = "UPDATE posts SET post_status = 'Approved' WHERE post_id = {$checkboxValues}";
                                    $response = mysqli_query($connection, $bulkQuery);
                                    break;
                                case "reset";
                                    $bulkQuery = "UPDATE posts SET post_views = 0 WHERE post_id = {$checkboxValues}";
                                    $response = mysqli_query($connection, $bulkQuery);
                                    break;
                                case "delete";
                                    $bulkQuery = "DELETE FROM posts WHERE post_id = {$checkboxValues}";
                                    $response = mysqli_query($connection, $bulkQuery);
                                    break;
                                case "clone";
                                    $bulkQuery = <<<SQL
                                        INSERT INTO posts (
                                            post_title, post_category_id, post_author, post_image,
                                            post_content, post_tags, post_status, post_date
                                        )
                                        SELECT
                                            post_title, post_category_id, post_author, post_image,
                                            post_content, post_tags, post_status, post_date
                                        FROM
                                            posts
                                        WHERE 
                                            post_id = {$checkboxValues}
                                    SQL;
                                    $response = mysqli_query($connection, $bulkQuery);
                                    break;
                            }
                        }
                    }
                    ?>
                    <form action="" method="post">
                        <div style="margin-bottom:15px; display:flex">
                            <div id="bulk-options-container" class="col-xs-4" style="padding:0">
                                <select class="form-control" name="ddBulkOptions" id="bulk-options">
                                    <option value="">Select Options...</option>
                                    <option value="draft">Draft</option>
                                    <option value="approve">Approve</option>
                                    <option value="delete">Delete</option>
                                    <option value="clone">Clone</option>
                                    <option value="reset">Reset Views</option>
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <button type="submit" name="submit" class="btn btn-success">Apply</button>
                                <button type="button" class="btn btn-primary" onclick="location.href='posts.php?source=add';">Add New Post</button>
                            </div>
                        </div>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all-boxes"></th>
                                    <th style="width:10%">Image</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Comments</th>
                                    <th>Views</th>
                                    <th>Date</th>
                                    <th>Tools</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = <<<SQL
                                    SELECT
                                        p.post_id,
                                        p.post_image,
                                        p.post_title,
                                        c.cat_title,
                                        p.post_author,
                                        p.post_status,
                                        p.post_tags,
                                        p.post_date,
                                        p.post_views,
                                        (SELECT COUNT(*) FROM comments WHERE comment_post_id = p.post_id) AS post_comment_count
                                    FROM
                                        posts AS p
                                        INNER JOIN categories AS c ON p.post_category_id = c.cat_id
                                    ORDER BY
                                        post_id DESC
                                SQL;
                                $response = mysqli_query($connection, $query);
                                while($postsRS = mysqli_fetch_assoc($response)) {
                                    ?>
                                    <tr>
                                        <td><input type="checkbox" class="checkBoxes" name="checkBoxArray[]" value="<?=$postsRS["post_id"]?>"></td>
                                        <?php
                                        if (!empty($postsRS["post_image"])) {
                                            if (strpos($postsRS["post_image"], "http") > -1) {
                                                echo "<td><img src=\"" . $postsRS["post_image"] . "\" class=\"img-responsive\" alt=\"\"/></td>";
                                            } else {
                                                echo "<td><img src=\"../images/" . $postsRS["post_image"] . "\" class=\"img-responsive\" alt=\"\"/></td>";
                                            }
                                        } else {
                                            echo "<td>&nbsp;</td>";
                                        }
                                        ?>
                                        <td><a href="../post.php?pid=<?=$postsRS["post_id"]?>" target="_blank" rel="noopener noreferrer"><?=$postsRS["post_title"]?></a></td>
                                        <td><?=$postsRS["cat_title"]?></td>
                                        <td><?=$postsRS["post_author"]?></td>
                                        <td><?=$postsRS["post_status"]?></td>
                                        <td><?=$postsRS["post_comment_count"]?></td>
                                        <td><?=$postsRS["post_views"]?></td>
                                        <td><?=$postsRS["post_date"]?></td>
                                        <td style="width:30px">
                                            <div class="flex-icons">
                                                <span>
                                                    <a href="posts.php?approve=<?=$postsRS["post_id"]?>" title="Click to approve post">
                                                        <i class="fa fa-fw fa-check"></i></span>
                                                    </a>
                                                </span>
                                                <span>
                                                    <a href="posts.php?decline=<?=$postsRS["post_id"]?>" title="Click to decline post">
                                                        <i class="fa fa-fw fa-ban"></i></span>
                                                    </a>
                                                </span>
                                                <span>
                                                    <a href="posts.php?source=edit&pid=<?=$postsRS["post_id"]?>" title="Click to edit post">
                                                        <i class="fa fa-fw fa-pencil"></i></span>
                                                    </a>
                                                </span>
                                                <span>
                                                    <a href="javascript:void(0);" onclick="ConfirmPostDelete(<?=$postsRS['post_id']?>);" title="Click to delete post">
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
                    </form>
                    <?php
                    /* APPROVE POST */
                    if (isset($_GET["approve"])) {
                        $approveSQL = "UPDATE posts SET post_status = 'Approved' WHERE post_id = " . $_GET['approve'];
                        $approveRS = mysqli_query($connection, $approveSQL);
                        header("Location: posts.php");
                    }

                    /* DECLINE POST */
                    if (isset($_GET["decline"])) {
                        $declineSQL = "UPDATE posts SET post_status = 'Declined' WHERE post_id = " . $_GET['decline'];
                        $declineRS = mysqli_query($connection, $declineSQL);
                        header("Location: posts.php");
                    }

                    /* DELETE POST */
                    if (isset($_GET["delete"])) {
                        $deleteSQL = "DELETE FROM posts WHERE post_id = " . $_GET['delete'];
                        $deleteRS = mysqli_query($connection, $deleteSQL);
                        header("Location: posts.php");
                    }
                    ?>