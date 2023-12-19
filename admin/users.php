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
                        <?php if (isset($_GET["n"])) { ?>
                        <div class="alert alert-success" role="alert" id="save-alert">
                            <i class="fa fa-check\"></i>&nbsp;&nbsp;New user added successfully..!
                        </div>
                        <?php } ?>
                        <?php if (isset($_GET["e"])) { ?>
                        <div class="alert alert-success" role="alert" id="save-alert">
                            <i class="fa fa-check\"></i>&nbsp;&nbsp;User changes saved successfully..!
                        </div>
                        <?php } ?>
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
<script type="text/javascript">
    $(document).ready(function () {
        if ($('#save-alert').length) {
            setTimeout(function () {
                $('#save-alert').slideUp();
            }, 3000)
        }
    });

    function ConfirmUserDelete(userid) {
        var agree = confirm('Are you sure you wish to delete this user?\n');
        if (agree) {
            document.location.href = 'users.php?delete=' + userid;
        }
    }
</script>