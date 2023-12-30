<?php
function redirect($location) {
    header("Location:" . $location);
    exit;
}

function escape($string) {
    global $connection;
    return mysqli_real_escape_string($connection, trim($string));
}

function recordCount($tableName) {
    global $connection;
    $query = "SELECT COUNT(*) AS count_result FROM {$tableName}";
    $response =  mysqli_query($connection, $query);
    $countRS = mysqli_fetch_assoc($response);
    return $countRS["count_result"];
}

function recordCountComments() {
    global $connection;
    $query = <<<SQL
        SELECT
            COUNT(*) AS count_result
        FROM
            comments AS c
            INNER JOIN posts AS p ON c.comment_post_id = p.post_id
        WHERE
            c.comment_author = {$_SESSION["loggedUserId"]}
    SQL;
    $response =  mysqli_query($connection, $query);
    $countRS = mysqli_fetch_assoc($response);
    return $countRS["count_result"];
}

function customRecordCount($all, $userField, $tableName, $fieldName, $status) {
    global $connection;

    $byUserSQL = "";
    if (!$all) {
        $byUserSQL = " AND {$userField} = {$_SESSION["loggedUserId"]}";
    }

    $query = "SELECT COUNT(*) AS count_result FROM {$tableName} WHERE {$fieldName} = '{$status}'{$byUserSQL}";
    $response =  mysqli_query($connection, $query);
    $countRS = mysqli_fetch_assoc($response);
    return $countRS["count_result"];
}

function checkAdminSecurity() {
    if (!$_SESSION["loggedIsAdmin"]) {
        header("Location: ../index.php");
    }
}

function showSQL($query) {
    echo "QUERY: " . $query . "<br/>";
}

function users_online() {
    if (isset($_GET["onlineusers"])) {
        global $connection;

        if (!$connection) {
            session_start();
            include "../../includes/db.php";

            /* DEFINE AND SET USERS ONLINE VARIABLES */
            $sessionId = session_id();
            $time = time();
            $timeOutInSeconds = 60;
            $timeOut = $time - $timeOutInSeconds;
        
            /* CHECK IF CURRENT USER HAS A SESSION IN DATABASE */
            $query = "SELECT * FROM users_online WHERE session = '$sessionId'";
            $response = mysqli_query($connection, $query);
            $usersOnlineCount = mysqli_num_rows($response);
        
            if ($usersOnlineCount == NULL) {
                /* USER NOT FOUND - CREATE ONE */
                $insertSQL = "INSERT INTO users_online(session, session_time) VALUES('$sessionId', '$time')";
                mysqli_query($connection, $insertSQL);
            } else {
                /* USER FOUND */
                $updateSQL = "UPDATE users_online SET session_time = '$time' WHERE session = '$sessionId'";
                mysqli_query($connection, $updateSQL);
            }
        
            /* COUNT NUMBER OF USER SESSIONS WITHIN TIMEOUT */
            $countUsersSQL = "SELECT * FROM users_online WHERE session_time > '$timeOut'";
            $response = mysqli_query($connection, $countUsersSQL);
            echo mysqli_num_rows($response);
        }
    }
}
users_online();
?>