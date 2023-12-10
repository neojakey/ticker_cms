                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th style="width:10%">Image</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Tags</th>
                                    <th>Comments</th>
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
                                        (SELECT COUNT(*) FROM comments WHERE comment_post_id = p.post_id) AS post_comment_count
                                    FROM
                                        posts AS p
                                        INNER JOIN categories AS c ON p.post_category_id = c.cat_id
                                SQL;
                                $response = mysqli_query($connection, $query);
                                while($postsRS = mysqli_fetch_assoc($response)) {
                                    ?>
                                    <tr>
                                        <td><?=$postsRS["post_id"]?></td>
                                        <?php
                                        if (strpos($postsRS["post_image"], "http") > -1) {
                                            echo "<td><img src=\"" . $postsRS["post_image"] . "\" class=\"img-responsive\" alt=\"\"/></td>";
                                        } else {
                                            echo "<td><img src=\"../images/" . $postsRS["post_image"] . "\" class=\"img-responsive\" alt=\"\"/></td>";
                                        }
                                        ?>
                                        <td><a href="../post.php?pid=<?=$postsRS["post_id"]?>" target="_blank" rel="noopener noreferrer"><?=$postsRS["post_title"]?></a></td>
                                        <td><?=$postsRS["cat_title"]?></td>
                                        <td><?=$postsRS["post_author"]?></td>
                                        <td><?=$postsRS["post_status"]?></td>
                                        <td><?=$postsRS["post_tags"]?></td>
                                        <td><?=$postsRS["post_comment_count"]?></td>
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
                                                    <a href="posts.php?delete=<?=$postsRS["post_id"]?>" title="Click to delete post">
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