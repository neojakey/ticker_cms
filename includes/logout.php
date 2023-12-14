<?php include "./db.php"; ?>
<?php
session_start();
session_unset();

header("Location: ../index.php");
?>