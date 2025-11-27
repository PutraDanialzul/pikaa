
<!DOCTYPE html>
<html>
    <head>
        <title>Admin Login - PIKAA</title>
        <link rel="stylesheet" href="/styles.css">
        <link rel="stylesheet" href="styles.css">
        <?php
        session_start();
        $loggedIn = false;
        if(isset($_SESSION["secret"]))
            $admin_secret = $_SESSION["secret"];
        else if(isset($_POST["secret"]))
            $admin_secret = $_POST["secret"];
        ?>
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
                        $_SESSION["secret"] = $admin_secret;
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
            <div id="form-area" style="flex: 1;">
                <form method="POST">
                    <label for="secret-input">Please enter your secret key. </label>
                    <input type="password" name="secret" id="secret-input" style="text-align: center;">
                    <input type="submit">
                </form>
            </div>
        </div>
        <?php
            }
            else{
        ?>
        <script>window.location.href = ""</script>
        <?php
            }
        ?>
    </body>
</html>