<?php ob_start(); ?>
<?php include "../includes/db.php" ?>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>SB Admin - Bootstrap Admin Template</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/sb-admin.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .flex-icons {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: center;
            gap: 4px;
            font-size: 16px;
        }

        .flex-icons .fa-pencil {
            color: lightskyblue;
        }

        .flex-icons .fa-times {
            color: lightcoral;
        }

        .flex-icons .fa-check {
            color: green;
        }

        .flex-icons .fa-ban {
            color: darkgray;
        }
    </style>
</head>

<body>