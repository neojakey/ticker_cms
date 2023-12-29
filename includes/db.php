<?php
    define("DB_HOSTNAME", "127.0.0.1");
    define("DB_USERNAME", "root");
    define("DB_PASSWORD", "@H2rf36t4DMq");
    define("DB_DATABASE", "cms");

    $connection = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if (!$connection) {
        echo "Database connection was not established";
    }

    mysqli_query($connection, "SET NAMES utf8");
?>