<?php
function escape($string) {
    global $connection;
    return mysqli_real_escape_string($connection, trim($string));
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