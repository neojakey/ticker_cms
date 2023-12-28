<?php include "./db.php"; ?>
<?php
ob_start();
session_start();
session_unset();
session_destroy();
$_SESSION = array();

header("Location: ../index.php");
?>