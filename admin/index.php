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
        <script src="scripts.js"></script>
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
                <a href="/"><img id="logged-in-logo" src="/media/pikaa.png" alt="pikaa logo"></a>
            </div>

            <div id="topbar-actions">
                <div id="menu-dropdown">
                    <button type="button" id="menu-dropdown-button">Menu ▾</button>
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
                            case "songs": echo isset($_GET["id"]) ? "Song Editing: ".$_GET["id"] : "Songs List"; break;
                            case "feedbacks": echo isset($_GET["id"]) ? "Feedback Reviewing: ".$_GET["id"] : "Feedback Messages"; break;
                            case "secrets": echo isset($_GET["id"]) ? "Secret Key Editing: ".$_GET["id"] : "Secret Keys"; break;
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
                <?php if(!isset($_GET["id"])){ ?>
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
                                    <input type="submit" class="object-view-button" value="View">
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
                                    <input type="submit" class="object-view-button" value="Create">
                                </form>
                            </div>
                        </div>
                        <?php } ?>
                        <?php
                        $page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
                        switch($_GET["view"]){
                            case "songs":
                                addCreateObjectRow("Add a new song to the list", "A new song to be available for users to listen.", "");
                                $searchQuery = "SELECT SONG_TITLE, SONG_ARTIST, SONG_GENRE, SONG_ID from song;";
                                $result = $connection->query($searchQuery);
                                $pageCount = ceil($result->num_rows/$contentPerPage);
                                for($i = ($page - 1) * $contentPerPage; $i < min($result->num_rows, $page*$contentPerPage); $i++){
                                    $row = $result->fetch_all()[$i];
                                    $title = $row[0];
                                    $artist = $row[1];
                                    $genre = $row[2];
                                    $id = intval($row[3]);
                                    addListRow($title, $artist, $genre, $id);
                                }
                                break;
                            case "feedbacks":
                                addCreateObjectRow("Create a new feedback for testing purposes", "Feedback messages are great to make sure that the website work perfectly as intended. ", "");
                                $searchQuery = "SELECT SENDER_NAME, FEEDBACK_TEXT, FEEDBACK_TIME, FEEDBACK_ID FROM feedback;";
                                $result = $connection->query($searchQuery);
                                $pageCount = ceil($result->num_rows/$contentPerPage);
                                for($i = ($page - 1) * $contentPerPage; $i < min($result->num_rows, $page*$contentPerPage); $i++){
                                    $row = $result->fetch_all()[$i];
                                    $senderName = $row[0];
                                    $text = $row[1];
                                    $time = $row[2];
                                    $id = intval($row[3]);
                                    if(strlen($text) > 75)
                                        $text = substr($text, 0, 75)."...";
                                    addListRow($senderName, $text, $time, $id);
                                }
                                break;
                            case "secrets":
                                addCreateObjectRow("Create a new secret key", "Secret keys are used to open the admin dashboard. ", "");
                                $searchQuery = "SELECT SECRET_ID, ADMIN_SECRET FROM admin;";
                                $result = $connection->query($searchQuery);
                                $pageCount = ceil($result->num_rows/$contentPerPage);
                                for($i = ($page - 1) * $contentPerPage; $i < min($result->num_rows, $page*$contentPerPage); $i++){
                                    $row = $result->fetch_all()[$i];
                                    $secretKey = $row[1];
                                    $id = intval($row[0]);
                                    if(strlen($secretKey) > 75)
                                        $secretKey = substr($secretKey, 0, 75)."...";
                                    addListRow($id, $secretKey, "", $id);
                                }
                                break;
                        }
                        ?>
                    </div>
                <?php } else { ?>
                    <?php function createDisplayTable(array $data){ ?>
                    <div id="editor-container">
                        <table id="editor-table">
                            <tbody>
                                <?php foreach($data as $type => $value){ ?>
                                <tr>
                                    <td class="editor-table-data-title"><?php echo $type; ?></td>
                                    <td><?php echo $value; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                    <?php
                    $found = false;
                    switch($_GET["view"]){
                        case "songs":
                            $searchQuery = "SELECT SONG_TITLE, SONG_GENRE, SONG_ARTIST, SONG_RELEASE_YEAR, SONG_LYRICS, SONG_COVER_URL, SONG_VIDEO_URL, SONG_MUSICS_URL FROM song WHERE SONG_ID=".$_GET["id"].";";
                            $result = $connection->query($searchQuery);
                            if($result->num_rows < 0) break;
                            $found = true;
                            $fetchedData = $result->fetch_all()[0];
                            $data = [
                                "Title"=>$fetchedData[0],
                                "Genre"=>$fetchedData[1],
                                "Artist"=>$fetchedData[2],
                                "Release Year"=>$fetchedData[3],
                                "Lyrics"=>nl2br($fetchedData[4]),
                                "Cover URL"=>$fetchedData[5]."<br><br><img alt=\"song cover\" style=\"width: 300px;\" src=\"".$fetchedData[5]."\">",
                                "Youtube Embed URL"=>$fetchedData[6]."<br><br><iframe src=\"".$fetchedData[6]."\" title=\"Embed Youtube Video\" style=\"width: 300px;\"></iframe>",
                                "Spotify Embed URL"=>$fetchedData[7]."<br><br><iframe src=\"".$fetchedData[7]."\" title=\"Embed Spotify Music\" style=\"width: 300px; height: 80px;\"></iframe>"
                            ];
                            createDisplayTable($data);
                            break;
                        case "feedbacks":
                            $searchQuery = "SELECT SENDER_NAME, SENDER_GENDER, SENDER_EMAIL, FEEDBACK_TYPE, FEEDBACK_TEXT, FEEDBACK_TIME FROM feedback WHERE FEEDBACK_ID=".$_GET["id"].";";
                            $result = $connection->query($searchQuery);
                            if($result->num_rows < 0) break;
                            $found = true;
                            $fetchedData = $result->fetch_all()[0];
                            $data = [
                                "Sender Name"=>$fetchedData[0],
                                "Gender"=>$fetchedData[1],
                                "Email"=>$fetchedData[2],
                                "Feedback Type"=>$fetchedData[3],
                                "Feedback Messages"=>nl2br($fetchedData[4]),
                                "Feedback Time"=>$fetchedData[5]
                            ];
                            createDisplayTable($data);
                            break;
                        case "secrets":
                            $searchQuery = "SELECT ADMIN_SECRET FROM admin WHERE SECRET_ID=".$_GET["id"].";";
                            $result = $connection->query($searchQuery);
                            if($result->num_rows < 0) break;
                            $found = true;
                            $fetchedData = $result->fetch_all()[0];
                            $data = [
                                "Secret Key"=>$fetchedData[0]
                            ];
                            createDisplayTable($data);
                            break;
                    }
                    ?>
                    <div id="bottom-edit-actionbar">
                        <form method="GET" id="bottom-edit-left-form">
                            <input type="hidden" name="view" value=<?php echo $_GET["view"] ?>>
                            <input type="hidden" name="id" value=<?php echo $_GET["id"] ?>>
                            <input type="hidden" name="action" value="edit">
                            <input type="submit" value="Edit">
                        </form>
                        <form method="POST" id="bottom-edit-right-form" action="?view=<?php echo $_GET["view"]; ?>&id=<?php echo $_GET["id"]; ?>">
                            <input type="hidden" name="view" value=<?php echo $_GET["view"] ?>>
                            <input type="hidden" name="id" value=<?php echo $_GET["id"] ?>>
                            <input type="hidden" name="action" value="delete">
                            <input id="delete-confirmation" type="text" placeholder="confirm_delete" oninput="onDeleteConfirmationChange(this.value);">
                            <input id="object-delete-button" type="submit" value="Delete" disabled>
                        </form>
                    </div>
                <?php }
            }else{
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
                    <input id="page-input" type="number" name="page" min="1" max="<?php echo $pageCount ?>" placeholder="Page" required>
                    <button id="page-submit-button" type="submit">Go</button>
                </form>
            </footer>
        <?php } ?>
    </body>
</html>