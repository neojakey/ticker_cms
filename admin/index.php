<?php include "includes/admin_header.php" ?>
    <div id="wrapper">
        <?php include "includes/admin_navigation.php" ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Welcome to the Admin Panel
                            <small><?=$_SESSION["loggedUsername"]?></small>
                        </h1>
                    </div>
                </div>
                <?php
                $query = "SELECT COUNT(*) AS post_count FROM posts WHERE post_status <> 'Draft'";
                $response = mysqli_query($connection, $query);
                $postsRS = mysqli_fetch_assoc($response);
                ?>
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-file-text fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                  <div class="huge"><?=$postsRS["post_count"]?></div>
                                        <div>Posts</div>
                                    </div>
                                </div>
                            </div>
                            <a href="./posts.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php
                    $query = "SELECT COUNT(*) AS comment_count FROM comments WHERE comment_status <> 'New'";
                    $response = mysqli_query($connection, $query);
                    $commentsRS = mysqli_fetch_assoc($response);
                    ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-comments fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                     <div class="huge"><?=$commentsRS["comment_count"]?></div>
                                      <div>Comments</div>
                                    </div>
                                </div>
                            </div>
                            <a href="./comments.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php
                    $query = "SELECT COUNT(*) AS user_count FROM users";
                    $response = mysqli_query($connection, $query);
                    $usersRS = mysqli_fetch_assoc($response);
                    ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-yellow">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-user fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                    <div class="huge"><?=$usersRS["user_count"]?></div>
                                        <div> Users</div>
                                    </div>
                                </div>
                            </div>
                            <a href="./users.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php
                    $query = "SELECT COUNT(*) AS category_count FROM categories";
                    $response = mysqli_query($connection, $query);
                    $categoriesRS = mysqli_fetch_assoc($response);
                    ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-red">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-list fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"><?=$categoriesRS["category_count"]?></div>
                                         <div>Categories</div>
                                    </div>
                                </div>
                            </div>
                            <a href="./categories.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <?php
                $query = "SELECT COUNT(*) AS draft_post_count FROM posts WHERE post_status = 'Draft'";
                $response = mysqli_query($connection, $query);
                $draftPostsRS = mysqli_fetch_assoc($response);

                $query = "SELECT COUNT(*) AS new_comment_count FROM comments WHERE comment_status = 'New'";
                $response = mysqli_query($connection, $query);
                $newCommentsRS = mysqli_fetch_assoc($response);

                ?>
                <div class="row" style="padding-left:15px; padding-right:15px">
                    <script type="text/javascript">
                        google.charts.load('current', {'packages':['bar']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                                <?php
                                $element_text = ['Active Posts', 'Draft Posts', 'Comments', 'New Comments', 'Users', 'Categories'];
                                $element_count = [$postsRS["post_count"], $draftPostsRS["draft_post_count"], $commentsRS["comment_count"], $newCommentsRS["new_comment_count"], $usersRS["user_count"], $categoriesRS["category_count"]];
                                for ($i = 0; $i < 6; $i++) {
                                    echo "['{$element_text[$i]}'" . "," . "{$element_count[$i]}],";
                                }
                                ?>
                            ], true);

                            var options = {
                                legend: {
                                    position: 'none'
                                },
                                chart: {
                                    title: '',
                                    subtitle: '',
                                }
                            };

                            var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                            chart.draw(data, google.charts.Bar.convertOptions(options));
                        }
                    </script>
                    <div id="columnchart_material" style="width: 'auto'; height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>
<?php include "includes/admin_footer.php" ?>