<?php
if (isset($_POST["tbTitle"])) {
    /* DECLARE AND SET VARIABLES */
    $postTitle = escape($_POST["tbTitle"]);
    $postCategoryId = $_POST["ddCategoryId"];
    $postAuthor = $_POST["tbAuthor"];
    $postStatus = $_POST["ddStatus"];
    $postImage = $_FILES["fileImage"]["name"];
    $postImageTemp = $_FILES["fileImage"]["tmp_name"];
    $postTags = escape($_POST["tbTags"]);
    $postContent = escape($_POST["taContent"]);

    if (!empty($postImage)) { // IMAGE WAS INCLUDED
        /* UPLOAD IMAGE IF FOUND */
        move_uploaded_file($postImageTemp, "../images/$postImage");
        $imageSQL = "'{$postImage}',";
    } else {
        $imageSQL = "NULL,";
    }

    /* SAVE POST TO THE DATABASE */
    $query = <<<SQL
        INSERT INTO posts(
            post_title, post_category_id, post_author, post_date,
            post_image, post_content, post_tags, post_status
        ) VALUE(
            '{$postTitle}', {$postCategoryId}, {$postAuthor}, now(),
            {$imageSQL} '{$postContent}', '{$postTags}', '{$postStatus}'
        )
    SQL;
    echo $query;
    $addPost = mysqli_query($connection, $query);
    if (!$addPost) {
        die("Add Post Failed: " . mysqli_error($connection));
    } else {
        redirect("posts.php?n=y");
    }
}
?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Post Title</label>
        <input type="text" class="form-control" id="title" name="tbTitle"/>
    </div>
    <div class="form-group">
        <label for="cat-id">Post Category Id</label>
        <select class="form-control" id="cat-id" name="ddCategoryId">
            <option value="">Select One...</option>
            <?php
            $query = "SELECT cat_id, cat_title FROM categories";
            $response = mysqli_query($connection, $query);
            while($categoriesRS = mysqli_fetch_assoc($response)) {
                ?><option value="<?=$categoriesRS["cat_id"]?>"><?=$categoriesRS["cat_title"]?></option><?php
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
                if (intval($users["user_id"]) === intval($_SESSION["loggedUserId"])) {
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
            <option value="Draft">Draft</option>
            <option value="Approved">Approved</option>
        </select>
    </div>
    <div class="form-group">
        <label for="post-image">Post Image</label>
        <input type="file" id="post-image" name="fileImage"/>
    </div>
    <div class="form-group">
        <label for="tags">Post Tags</label>
        <input type="text" class="form-control" id="tags" name="tbTags"/>
    </div>
    <div class="form-group">
        <label for="post-content">Post Content</label>
        <textarea name="taContent" id="post-content" class="form-control" cols="30" rows="10"></textarea>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Save Post</button>
    </div>
</form>