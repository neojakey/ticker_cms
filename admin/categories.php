<?php
    include "includes/admin_header.php";
    checkAdminSecurity();
    ?>
    <div id="wrapper">
        <?php include "includes/admin_navigation.php" ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Category Management
                        </h1>
                        <div class="col-xs-6">
                            <?php
                            $mode = "Add";
                            if (isset($_GET['delete'])) {
                                /* DELETE CATEGORY QUERY */
                                $deleteSQL = "DELETE FROM categories WHERE cat_id = " . $_GET['delete'];
                                $deleteRS = mysqli_query($connection, $deleteSQL);
                                redirect("categories.php");
                            }

                            if (isset($_POST["cat_title"])) {
                                /* ADD/EDIT CATEGORY QUERY */
                                $catId = $_POST["cat_id"];
                                $catTitle = $_POST["cat_title"];
                                if ($catTitle == "" || empty($catTitle)) {
                                    echo "<div class=\"alert alert-danger\" role=\"alert\"><i class=\"fa fa-warning\"></i>&nbsp;&nbsp;Please enter the category name</div>";
                                } else {
                                    if (isset($_POST["cat_id"]) && $catId !== "") {
                                        /* UPDATE QUERY */
                                        $query = "UPDATE categories SET cat_title = '{$catTitle}' WHERE cat_id = " . $catId;
                                        echo $query;
                                        $update_category = mysqli_query($connection, $query);
                                        if (!$update_category) {
                                            die("Update Category Failed: " . mysqli_error($connection));
                                        }
                                        redirect("categories.php");
                                    } else {
                                        /* ADD QUERY */
                                        $query = "INSERT INTO categories(cat_title) VALUE('{$catTitle}')";
                                        $add_category = mysqli_query($connection, $query);
                                        if (!$add_category) {
                                            die("Add Category Failed: " . mysqli_error($connection));
                                        }
                                    }
                                }
                            }

                            $catEditId = "";
                            $edit_category_name = "";
                            if (isset($_GET["edit"])) {
                                /* EDIT CATEGORY QUERY */
                                $catEditId = $_GET["edit"];
                                if ($catEditId !== "" || !empty($catEditId)) {
                                    $mode = "Edit";
                                    $query = "SELECT cat_title FROM categories WHERE cat_id = " . $catEditId;
                                    $edit_category = mysqli_query($connection, $query);
                                    $row_count = mysqli_num_rows($edit_category);
                                    if ($row_count === 0) {
                                        echo "<div class=\"alert alert-danger\" role=\"alert\"><i class=\"fa fa-warning\"></i>&nbsp;&nbsp;Category was not found</div>";
                                    } else {
                                        $categoryEditRS = mysqli_fetch_assoc($edit_category);
                                        $edit_category_name = $categoryEditRS["cat_title"];
                                    }
                                }
                            }
                            ?>
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="cat-title"><?=$mode?> Category Name:</label>
                                    <input type="hidden" name="cat_id" value="<?=$catEditId?>"/>
                                    <input type="text" id="cat-title" value="<?=$edit_category_name?>" class="form-control" name="cat_title">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="submit">Save Category</button>
                                    <?php
                                    if ($mode == "Edit") {
                                        ?><button type="button" class="btn" name="cancel" onclick="location.href='categories.php';">Cancel</button><?php
                                    }
                                    ?>
                                </div>
                            </form>
                        </div>
                        <div class="col-xs-6">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Category Title</th>
                                        <th style="width:30px">Tools</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT cat_id, cat_title FROM categories";
                                    $response = mysqli_query($connection, $query);
                                    while($categoriesRS = mysqli_fetch_assoc($response)) {
                                        ?>
                                        <tr>
                                            <td><?=$categoriesRS["cat_id"]?></td>
                                            <td><?=$categoriesRS["cat_title"]?></td>
                                            <td style="width:30px">
                                                <div class="flex-icons">
                                                    <span>
                                                        <a href="./categories.php?edit=<?=$categoriesRS["cat_id"]?>">
                                                            <i class="fa fa-fw fa-pencil"></i></span>
                                                        </a>
                                                    </span>
                                                    <span>
                                                        <a href="./categories.php?delete=<?=$categoriesRS["cat_id"]?>">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include "includes/admin_footer.php" ?>