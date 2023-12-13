        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">CMS Admin</a>
            </div>
            <ul class="nav navbar-right top-nav">
                <li><a href="../">Home</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> John Smith <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><i class="fa fa-fw fa-user"></i> Profile</a></li>
                        <li class="divider"></li>
                        <li><a href="#"><i class="fa fa-fw fa-power-off"></i> Log Out</a></li>
                    </ul>
                </li>
            </ul>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li>
                        <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#posts_dd"><i class="fa fa-fw fa-file-text"></i> Posts <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="posts_dd" class="collapse">
                            <li>
                                <a href="posts.php">View Posts</a>
                            </li>
                            <li>
                                <a href="posts.php?source=add">Add Post</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="categories.php"><i class="fa fa-fw fa-tags"></i> Categories</a>
                    </li>
                    <li>
                        <a href="comments.php"><i class="fa fa-fw fa-comments"></i> Comments</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#users"><i class="fa fa-fw fa-users"></i> Users <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="users" class="collapse">
                            <li>
                                <a href="users.php">View Users</a>
                            </li>
                            <li>
                                <a href="users.php?source=add">Add User</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="blank-page.html"><i class="fa fa-fw fa-user"></i> Profile</a>
                    </li>
                </ul>
            </div>
        </nav>