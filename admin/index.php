<?php
function logOut(){
    session_destroy();
    echo "<script>window.location.href = \"login\";</script>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Admin Dashboard - PIKAA</title>
        <link rel="stylesheet" href="/styles.css">
        <link rel="stylesheet" href="styles.css">
        <?php
        $current_secret = "";
        session_start();
        if(!isset($_SESSION["secret"])) logOut();
        else{
            $admin_secret = $_SESSION["secret"];
            $dbServername = "localhost";
            $dbUsername = "root";
            $dbPassword = "";
            $dbName = "pikaa";
            $connection = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);
            if($connection->connect_error){
                die("Error: Connection failed. ".$connection->connect_error);
            }
            else{
                $searchQuery = "SELECT * FROM admin WHERE ADMIN_SECRET = '$admin_secret'";
                $result = $connection->query($searchQuery);
                if($result->num_rows < 1){
                    logOut();
                }
                else $current_secret = $admin_secret;
            }
        }
        ?>
    </head>
    <body>
    </body>
</html>