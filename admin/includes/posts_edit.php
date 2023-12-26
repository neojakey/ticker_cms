<?php
/* IF FORM WAS SUBMITTED */
if (isset($_POST["hidPostId"])) {
    /* DECLARE AND SET VARIABLES */
    $postId = $_POST["hidPostId"];
    $postTitle = escape($_POST["tbTitle"]);
    $postCategoryId = $_POST["ddCategoryId"];
    $postAuthor = escape($_POST["tbAuthor"]);
    $postStatus = $_POST["ddStatus"];
    $postImage = $_FILES["fileImage"]["name"];
    $postImageTemp = $_FILES["fileImage"]["tmp_name"];
    $postTags = escape($_POST["tbTags"]);
    $postContent = escape($_POST["taContent"]);

    /* UPLOAD IMAGE IF FOUND */
    if (!empty($postImage)) { // IMAGE WAS INCLUDED
        move_uploaded_file($postImageTemp, "../images/$postImage");
        /* UPDATE POST IN THE DATABASE */
        $query = <<<SQL
            UPDATE posts SET
                post_title = '{$postTitle}', post_category_id = {$postCategoryId},
                post_author = {$postAuthor}, post_image = '{$postImage}',
                post_content = '{$postContent}', post_tags = '{$postTags}',
                post_status = '{$postStatus}'
            WHERE
                post_id = $postId
        SQL;
    } else {
        $query = <<<SQL
            UPDATE posts SET
                post_title = '{$postTitle}', post_category_id = {$postCategoryId},
                post_author = {$postAuthor}, post_content = '{$postContent}',
                post_tags = '{$postTags}', post_status = '{$postStatus}'
            WHERE
                post_id = $postId
        SQL;
    }
    $editPost = mysqli_query($connection, $query);
    if (!$editPost) {
        die("Edit Post Failed: " . mysqli_error($connection));
    } else {
        header("Location: posts.php?e=y");
    }
}

$query = <<<SQL
    SELECT
        post_id, post_image, post_title, post_category_id,
        post_author, post_status, post_tags,
        post_date, post_content
    FROM
        posts
    WHERE
        post_id = {$_GET["pid"]}
SQL;
$response = mysqli_query($connection, $query);
$postsRS = mysqli_fetch_assoc($response);
?>
<form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="hidPostId" value="<?=$_GET["pid"]?>"/>
    <div class="form-group">
        <label for="title">Post Title</label>
        <input type="text" class="form-control" id="title" name="tbTitle" value="<?=$postsRS["post_title"]?>"/>
    </div>
    <div class="form-group">
        <label for="cat-id">Post Category Id</label>
        <select class="form-control" id="cat-id" name="ddCategoryId">
            <option value="">Select One...</option>
            <?php
            $query = "SELECT cat_id, cat_title FROM categories";
            $response = mysqli_query($connection, $query);
            while($categoriesRS = mysqli_fetch_assoc($response)) {
                if (intval($postsRS["post_category_id"]) == intval($categoriesRS["cat_id"])) {
                    echo "<option value=\"" . $categoriesRS["cat_id"] . "\" selected=\"selected\">" . $categoriesRS["cat_title"] . "</option>";
                } else {
                    echo "<option value=\"" . $categoriesRS["cat_id"] . "\">" . $categoriesRS["cat_title"] . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="author">Post Author</label>
        <select class="form-control" id="author" name="tbAuthor">
            <option value="">Select One...</option>
            <?php
            $query = "SELECT user_firstname, user_lastname, user_id, username FROM users";
            $response = mysqli_query($connection, $query);
            while($users = mysqli_fetch_assoc($response)) {
                $fullName = $users["user_firstname"] . " " . $users["user_lastname"];
                if (empty($users["user_firstname"])) {
                    $fullName = "Unknown User";
                }
                if (intval($users["user_id"]) === intval($postsRS["post_author"])) {
                    echo "<option value=\"" . $users["user_id"] . "\" selected=\"selected\">" . $fullName . " - [" . $users["username"] . "]</option>";
                } else {
                    echo "<option value=\"" . $users["user_id"] . "\">" . $fullName . " - [" . $users["username"] . "]</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="status">Post Status</label>
        <select class="form-control" id="status" name="ddStatus">
            <option value="Draft"<?php if ($postsRS["post_status"] == "Draft") { echo " selected=\"selected\""; } ?>>Draft</option>
            <option value="Approved"<?php if ($postsRS["post_status"] == "Approved") { echo " selected=\"selected\""; } ?>>Approved</option>
        </select>
    </div>
    <div class="row">
        <div class="form-group col-xs-6">
            <label for="post-image">Post Image</label>
            <input type="file" id="post-image" name="fileImage"/>
        </div>
        <div class="form-group col-xs-6">
            <?php
            if (!empty($postsRS["post_image"])) {
                if (strpos($postsRS["post_image"], "http") > -1) {
                    echo "<img src=\"" . $postsRS["post_image"] . "\" style=\"width:150px\" alt=\"\"/>";
                } else {
                    echo "<img src=\"../images/" . $postsRS["post_image"] . "\" style=\"width:150px\" alt=\"\"/>";
                }
            } else {
                echo "&nbsp;";
            }
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="tags">Post Tags</label>
        <input type="text" class="form-control" id="tags" name="tbTags" value="<?=$postsRS["post_tags"]?>"/>
    </div>
    <div class="form-group">
        <label for="post-content">Post Content</label>
        <textarea name="taContent" id="post-content" class="form-control" cols="30" rows="10"><?=$postsRS["post_content"]?></textarea>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
</form>