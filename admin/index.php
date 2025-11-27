<?php
$current_secret = "";
session_start();
function logOut(){
    session_destroy();
    header("Location: login");
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Admin Dashboard - PIKAA</title>
        <link rel="stylesheet" href="/styles.css">
        <link rel="stylesheet" href="styles.css">
        <?php
        if(isset($_POST["logout"]))
            logOut();
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
        //if(!isset($_GET["view"])){
        //    header("Location: index?view=songs");
        //    exit();
        //}
        //else if($_GET["view"] != "songs" && $_GET["view"] != "feedbacks" && $_GET["view"] != "secrets"){
        //    header("Location: index?view=songs");
        //    exit();
        //}
        ?>
    </head>
    <body>
        <header id="topbar">
            <div id="logo-area">
                <a href="/"><img src="/media/pikaa.png" alt="pikaa logo"></a>
            </div>

            <div id="topbar-actions">
                <div id="menu-dropdown">
                    <button id="menu-dropdown-button">Menu ▾</button>
                    <div id="menu-dropdown-content">
                        <a href="?view=songs">Songs</a>
                        <a href="?view=feedbacks">Feedbacks</a>
                        <a href="?view=secrets">Secret Keys</a>
                    </div>
                </div>

                <form method="POST">
                    <input id="logout-button" type="submit" name="logout" value="Log Out">
                </form>
            </div>
        </header>
        <section id="main">
            <div id="main-header">
                <h1>
                    <?php
                    if(isset($_GET["view"])){
                        switch($_GET["view"]){
                            case "songs": echo "Songs List"; break;
                            case "feedbacks": echo "Feedback Messages"; break;
                            case "secrets": echo "Secret Keys"; break;
                        }
                    }
                    else echo "Admin Dashboard";
                    ?>
                </h1>
                <?php if(isset($_GET["view"])){ ?>
                    <form method="GET" id="search-bar">
                        <input type="hidden" name="view" value="<?php echo $_GET["view"]; ?>">
                        <input type="text" name="search" placeholder="Search..." required>
                        <button type="submit">Search</button>
                    </form>
                <?php } ?>
            </div>
            <?php if(isset($_GET["view"])){ ?>
                <div id="section-list-container">
                    <div class="list-item">
                        <div class="list-item-left">
                            <h3>Item Title</h3>
                            <p>Small description preview of the content...</p>
                        </div>
                        <div class="list-item-right">
                            <span class="list-item-date">Jan 22, 2025</span>
                            <button class="object-view-button">View</button>
                        </div>
                    </div>
                </div>
            <?php
            } else{
            ?>
            <p>This admin dashboard is used to check all of the feedback submissions from users. Song entries can also be edited using this page. Same goes to the secret key entries. </p>
            <?php } ?>
        </section>
        <?php if(isset($_GET["view"])){ ?>
            <footer id="footer-pagination">
                <?php 
                for($i = 1; $i <= 10; $i++){ 
                ?>
                    <a class="page-link" href="<?php echo "index?view=".$_GET["view"]."&page=".$i; ?>"><?php echo $i; ?></a>
                <?php
                } 
                ?>

                <form method="GET">
                    <input type="hidden" name="view" value="<?php echo $_GET["view"]; ?>">
                    <input id="page-input" type="number" name="page" min="1" placeholder="Page" required>
                    <button id="page-submin-button" type="submit">Go</button>
                </form>
            </footer>
        <?php } ?>
    </body>
</html>