<?php
    $loggedIn = false;
    if(isset($_POST["secret"]))
        $admin_secret = $_POST["secret"];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Admin Page - PIKAA</title>
        <link rel="stylesheet" href="/styles.css">
        <link rel="stylesheet" href="admin-styles.css">
    </head>
    <body>
        <?php
            if(isset($admin_secret)){
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
                        echo "<div id=\"error-panel\">";
                        echo "<p>Error: Wrong key.</p>";
                        echo "</div>";
                    }
                    else{ 
                        $loggedIn = true;
                    }
                }
            }
            if(!$loggedIn){
        ?>
        <div id="logged-out-container">
            <div style="margin: 100px auto; width: fit-content; height: fit-content; flex: 1;">
                <a href="/" style="width: fit-content; height: fit-content;"><img id="logo" src="/media/pikaa.png" alt="pikaa logo"></a>
            </div>
            <div id="form-area" style="flex: 2;">
                <form method="POST">
                    <label for="secret-input">Please enter your secret key. </label>
                    <input type="password" name="secret" id="secret-input">
                    <input type="submit">
                </form>
            </div>
        </div>
        <?php
            }
            else{
        ?>
        <img id="logged-in-logo" src="/media/pikaa.png" alt="pikaa logo">
        <!-- TODO: create logged in admin page -->
        <?php
            }
        ?>
        <script src="admin-scripts.js"></script>
    </body>
</html>