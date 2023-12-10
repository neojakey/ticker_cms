            <div class="col-md-4">
                <div class="well">
                    <h4>Blog Search</h4>
                    <form action="./search.php" method="post">
                        <div class="input-group">
                            <input type="text" name="tbSearch" class="form-control">
                            <span class="input-group-btn">
                                <button type="submit" name="submit" class="btn btn-default">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="well">
                    <h4>Blog Categories</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="list-unstyled">
                                <?php
                                $query = <<<SQL
                                    SELECT
                                        cat_id,
                                        cat_title,
                                        (SELECT
                                            COUNT(post_id)
                                        FROM
                                            posts
                                        WHERE
                                            post_category_id = c.cat_id
                                        ) AS post_count
                                    FROM
                                        categories AS c
                                SQL;
                                $response = mysqli_query($connection, $query);
                                while($categoriesRS = mysqli_fetch_assoc($response)) {
                                    $categoryId = $categoriesRS["cat_id"];
                                    $categoryTitle = $categoriesRS["cat_title"];
                                    $postCount = $categoriesRS["post_count"];
                                    ?><li><a href="category.php?cid=<?=$categoryId?>"><?=$categoryTitle?></a>&nbsp;[<?=$postCount?>]</li><?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php include "./includes/widget.php" ?>
            </div>