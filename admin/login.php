
<!DOCTYPE html>
<html>
    <head>
        <title>Admin Login - PIKAA</title>
        <link rel="stylesheet" href="/styles.css">
        <link rel="stylesheet" href="styles.css">
        <?php
        require $_SERVER['DOCUMENT_ROOT']."/dbInfo.php";
        session_start();
        function logIn($secret){
            $_SESSION["secret"] = $secret;
            header("Location: index");
            exit();
        }
        if(isset($_POST["secret"]))
            $admin_secret = $_POST["secret"];
        ?>
    </head>
    <body>
        <?php
        function wrongKey(){
        ?>
        <div id="error-panel">
        <p>Error: Wrong key.</p>
        </div>
        <?php
        }
        ?>
        <?php
            if(isset($admin_secret)){
                $connection = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);
                if($connection->connect_error){
                    die("Error: Connection failed. ".$connection->connect_error);
                }
                else{
                    $searchQuery = "SELECT ADMIN_SECRET FROM admin";
                    $result = $connection->query($searchQuery);
                    if($result->num_rows < 1){
                        logIn($admin_secret);
                    }
                    else{
                        foreach($result->fetch_all() as $row){
                            var_dump($row[0]);
                            var_dump($admin_secret);
                            if($admin_secret == $row[0]){
                                logIn($admin_secret);
                                exit();
                            }
                        }
                        wrongKey();
                    }
                }
            }
        ?>
        <div id="logged-out-container">
            <div style="margin: 100px auto; width: fit-content; height: fit-content; flex: 1;">
                <a href="/" style="width: fit-content; height: fit-content;"><img id="logo" src="/media/pikaa.png" alt="pikaa logo"></a>
            </div>
            <div id="form-area" style="flex: 1;">
                <form method="POST">
                    <label for="secret-input">Please enter your secret key. </label>
                    <input type="password" name="secret" id="secret-input" style="text-align: center;">
                    <input id="submit-key-button" type="submit">
                </form>
            </div>
        </div>
    </body>
</html>