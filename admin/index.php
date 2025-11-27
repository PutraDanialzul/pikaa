<?php
require $_SERVER['DOCUMENT_ROOT']."/dbInfo.php";
$current_secret = "";
$pageCount = 1;
$contentPerPage = 5;
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
            $connection = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);
            if($connection->connect_error){
                die("Error: Connection failed. ".$connection->connect_error);
            }
            else{
                $searchQuery = "SELECT ADMIN_SECRET FROM admin WHERE ADMIN_SECRET = '$admin_secret'";
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
                <?php function addListRow(string $title, string $description, string $rightInformation, int $id){ ?>
                <div class="list-item">
                    <div class="list-item-left">
                        <h3><?php echo $title; ?></h3>
                        <p><?php echo $description; ?></p>
                    </div>
                    <div class="list-item-right">
                        <span class="list-item-right-info"><?php echo $rightInformation; ?></span>
                        <form method="GET">
                            <input type="hidden" name="view" value="<?php echo $_GET["view"]; ?>">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="submit" id="object-view-button" value="View">
                        </form>
                    </div>
                </div>
                <?php } ?>
                <?php function addCreateObjectRow(string $title, string $description, string $rightInformation){ ?>
                <div class="list-item">
                    <div class="list-item-left">
                        <h3><?php echo $title; ?></h3>
                        <p><?php echo $description; ?></p>
                    </div>
                    <div class="list-item-right">
                        <span class="list-item-right-info"><?php echo $rightInformation; ?></span>
                        <form method="GET">
                            <input type="hidden" name="view" value="<?php echo $_GET["view"]; ?>">
                            <input type="hidden" name="action" value="create">
                            <input type="submit" id="object-view-button" value="Create">
                        </form>
                    </div>
                </div>
                <?php } ?>
                <?php
                $page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
                switch($_GET["view"]){
                    case "songs":
                        addCreateObjectRow("Add a new song to the list", "A new song to be available for users to listen.", "");
                        break;
                    case "feedbacks":
                        $searchQuery = "SELECT SENDER_NAME, FEEDBACK_TEXT, FEEDBACK_TIME, FEEDBACK_ID FROM feedback;";
                        $result = $connection->query($searchQuery);
                        $pageCount = ceil($result->num_rows/$contentPerPage);
                        for($i = $page - 1; $i < min($result->num_rows, $page*$contentPerPage); $i++){
                            $row = $result->fetch_all()[$i];
                            $desc = $row[1];
                            if(strlen($desc) > 75)
                                $desc = substr($desc, 0, 75)."...";
                            addListRow($row[0], $desc, $row[2], $row[3]);
                        }
                        break;
                    case "secrets":
                        addCreateObjectRow("Create a new secret key", "Secret keys are used to open the admin dashboard. ", "");
                        $searchQuery = "SELECT SECRET_ID, ADMIN_SECRET FROM admin;";
                        $result = $connection->query($searchQuery);
                        $pageCount = ceil($result->num_rows/$contentPerPage);
                        for($i = $page - 1; $i < min($result->num_rows, $page*$contentPerPage); $i++){
                            $row = $result->fetch_all()[$i];
                            $desc = $row[1];
                            if(strlen($desc) > 75)
                                $desc = substr($desc, 0, 75)."...";
                            addListRow($row[0], $desc, "", $row[0]);
                        }
                        break;
                }
                ?>
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
                for($i = 1; $i <= min($pageCount, 10); $i++){ 
                ?>
                    <a class="page-link" href="<?php echo "index?view=".$_GET["view"]."&page=".($i <= 5 ? $i : $pageCount-10+$i); ?>"><?php echo $i <= 5 ? $i : $pageCount-10+$i;; ?></a>
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