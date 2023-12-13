<?php include "includes/admin_header.php" ?>
    <div id="wrapper">
        <?php include "includes/admin_navigation.php" ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            User Management
                        </h1>
                        <?php
                        $source = "";
                        if (isset($_GET["source"])) {
                            $source = $_GET["source"];
                        }
                        switch($source) {
                            case 'add';
                                include "includes/users_add.php";
                                break;
                            case 'edit';
                                include "includes/users_edit.php";
                                break;
                            default:
                                include "includes/users_view.php";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include "includes/admin_footer.php" ?>