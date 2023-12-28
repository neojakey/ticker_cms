<?php
$root = "/ticker_cms";

function redirect($location) {
    global $root;
    if ($location == "") {
        header("Location: {$root}/");
        exit;
    }
    header("Location: {$root}/{$location}");
    exit;
}

function isLoggedIn () {
    if (isset($_SESSION["loggedRole"])) {
        return true;
    }
    return false;
}

function escape($string) {
    global $connection;
    return mysqli_real_escape_string($connection, trim($string));
}

function usernameExists($username) {
    global $connection;
    $query = "SELECT username from users WHERE username = '{$username}'";
    $response = mysqli_query($connection, $query);
    $existCount = mysqli_num_rows($response);
    if ($existCount > 0) {
        return true;
    }
    return false;
}

function userEmailExists($userEmail) {
    global $connection;
    $query = "SELECT 1 FROM users WHERE user_email = '{$userEmail}'";
    $response = mysqli_query($connection, $query);
    $existCount = mysqli_num_rows($response);
    if ($existCount > 0) {
        return true;
    }
    return false;
}?>