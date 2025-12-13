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
<?php if(isset($_GET["view"]) && isset($_GET["action"])){ function createEditTable(array $data, bool $autofill = true, bool $requiredAll = false){ ?>
<form id="editor-container-form" method="POST" onsubmit="return saveConfirmation();" action="?<?php echo "view=".$_GET["view"].(isset($_GET["id"])?("&id=".$_GET["id"]):""); ?>">
    <input type="hidden" name="action" value="<?php echo $_GET["action"]; ?>">
    <input type="hidden" name="objectType" value=<?php echo $_GET["view"]; ?>>
    <?php if(isset($_GET["id"])){ ?><input type="hidden" name="id" value=<?php echo $_GET["id"]; ?>> <?php } ?>
    <table id="editor-table">
        <tbody>
            <?php foreach($data as $type => $value){ ?>
            <tr>
                <td class="editor-table-data-title"><?php echo $type; ?></td>
                <td><textarea class="editor-table-textarea" name="<?php echo $type; ?>" style="width: 100%; resize: none;" placeholder="<?php echo str_replace('"', "&quot;", str_replace("'", "&#39;", $value)); ?>" <?php if($requiredAll) echo "required"; ?>><?php if($autofill) echo str_replace('"', "&quot;", str_replace("'", "&#39;", $value)); ?></textarea></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</form>
<?php } }?>

<!DOCTYPE html>
<html>
    <head>
        <title>Admin Dashboard - PIKAA</title>
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
                $searchQuery = "SELECT ADMIN_SECRET FROM admin WHERE ADMIN_SECRET = '".str_replace("'", "''", $admin_secret)."'";
                $result = $connection->query($searchQuery);
                if($result->num_rows < 1){
                    $connection->close();
                    logOut();
                }
                else $current_secret = $admin_secret;
            }
        }
        $editSuccess = true;
        $editError = "";
        if(isset($_POST["action"])){
            $tableName = "";
            $query = "";
            if($_POST["action"] != "delete"){
                if(!isset($_POST["objectType"])){
                    $editSuccess = false;
                    $editError = "Data type not found!";
                }
                else switch($_POST["objectType"]){
                    case "songs":
                        $tableName = "song";
                        $id = $_POST["id"];
                        $title = $_POST["Title"];
                        $genre = $_POST["Genre"];
                        $artist = $_POST["Artist"];
                        $releaseYear = $_POST["Release_Year"];
                        $lyrics = $_POST["Lyrics"];
                        $coverUrl = $_POST["Cover_URL"];
                        $ytEmbedUrl = $_POST["Youtube_Embed_URL"];
                        $spotifyEmbedUrl = $_POST["Spotify_Embed_URL"];
                        switch($_POST["action"]){
                            case "edit":
                                $searchQuery = "SELECT `SONG_TITLE`, `SONG_GENRE`, `SONG_ARTIST`, `SONG_RELEASE_YEAR`, `SONG_LYRICS`, `SONG_COVER_URL`, `SONG_VIDEO_URL`, `SONG_MUSICS_URL` FROM `$tableName` WHERE `SONG_ID`=$id;";
                                $queryResult = $connection->query($searchQuery);
                                $prevData = $queryResult->fetch_all()[0];
                                $data = [
                                    "SONG_TITLE" => $title != "" ? $title : $prevData[0],
                                    "SONG_GENRE" => $genre != "" ? $genre : $prevData[1],
                                    "SONG_ARTIST" => $artist != "" ? $artist : $prevData[2],
                                    "SONG_RELEASE_YEAR" => $releaseYear != "" ? $releaseYear : $prevData[3],
                                    "SONG_LYRICS" => $lyrics != "" ? $lyrics : $prevData[4],
                                    "SONG_COVER_URL" => $coverUrl != "" ? $coverUrl : $prevData[5],
                                    "SONG_VIDEO_URL" => $ytEmbedUrl != "" ? $ytEmbedUrl : $prevData[6],
                                    "SONG_MUSICS_URL" => $spotifyEmbedUrl != "" ? $spotifyEmbedUrl : $prevData[7]
                                ];
                                $query = "UPDATE `$tableName` SET";
                                $updateBefore = false;
                                foreach($data as $key => $val){
                                    if($updateBefore) $query .= ",";
                                    if(ctype_space($val)) continue;
                                    $query .= " `".$key."` = '".str_replace("'", "''", $val)."'";
                                    $updateBefore = true;
                                }
                                $query .= " WHERE SONG_ID = ".$_POST["id"].";";
                                break;
                            case "create":
                                $data = [
                                    "SONG_TITLE" => $title,
                                    "SONG_GENRE" => $genre,
                                    "SONG_ARTIST" => $artist,
                                    "SONG_RELEASE_YEAR" => $releaseYear,
                                    "SONG_LYRICS" => $lyrics,
                                    "SONG_COVER_URL" => $coverUrl,
                                    "SONG_VIDEO_URL" => $ytEmbedUrl,
                                    "SONG_MUSICS_URL" => $spotifyEmbedUrl
                                ];
                                $query = "INSERT INTO `$tableName` (";
                                $addedBefore = false;
                                foreach($data as $key => $val){
                                    if($addedBefore) $query .= ",";
                                    $query .= " `".$key."`";
                                    $addedBefore = true;
                                }
                                $addedBefore = false;
                                $query .= ") VALUES (";
                                foreach($data as $key => $val){
                                    if($addedBefore) $query .= ",";
                                    $query .= " '".str_replace("'", "''", $val)."'";
                                    $addedBefore = true;
                                }
                                $query .= ");";
                                break;
                            default:
                                $editSuccess = false;
                                $editError = "Action not found";
                                break;
                        }
                        break;
                    case "secrets":
                        $tableName = "admin";
                        $newSecret = $_POST["Secret_Key"];
                        switch($_POST["action"]){
                            case "edit":
                                $searchQuery = "SELECT ADMIN_SECRET FROM `$tableName` WHERE SECRET_ID = ".$_POST["id"].";";
                                $result = $connection->query($searchQuery);
                                if($result->num_rows < 1){
                                    $editSuccess = false;
                                    $editError = "Secret not found!";
                                    break;
                                }
                                $previousKey = $result->fetch_all()[0][0];
                                if($previousKey == $current_secret){
                                    $editSuccess = false;
                                    $editError = "Cannot edit current secret key!";
                                    break;
                                }
                                $query = "UPDATE `$tableName` SET `ADMIN_SECRET` = '".str_replace("'", "''", $newSecret != "" ? $newSecret : $previousKey)."' WHERE `SECRET_ID` = '".$_POST["id"]."';";
                                break;
                            case "create":
                                $searchQuery = "SELECT ADMIN_SECRET FROM `$tableName` WHERE ADMIN_SECRET = '".str_replace("'", "''", $newSecret)."';";
                                $result = $connection->query($searchQuery);
                                if($result->num_rows > 0){
                                    $editSuccess = false;
                                    $editError = "Secret key already existed!";
                                    break;
                                }
                                $query = "INSERT INTO `$tableName` (ADMIN_SECRET) VALUES ('".str_replace("'", "''", $newSecret)."');";
                                break;
                            default:
                                $editSuccess = false;
                                $editError = "Action not found";
                                break;
                        }
                        break;
                    case "feedbacks":
                        $tableName = "feedback";
                        switch($_POST["action"]){
                            case "edit":
                                $editSuccess = false;
                                $editError = "Cannot edit sent feedback messages!";
                                break;
                            case "create":
                                $data = [
                                    "SENDER_NAME" => str_replace("'", "''", $_POST["Sender_Name"]),
                                    "SENDER_GENDER" => str_replace("'", "''", $_POST["Gender"]),
                                    "SENDER_EMAIL" => str_replace("'", "''", $_POST["Email"]),
                                    "FEEDBACK_TYPE" => str_replace("'", "''", $_POST["Feedback_Type"]),
                                    "FEEDBACK_TEXT" => str_replace("'", "''", $_POST["Feedback_Messages"]),
                                    "FEEDBACK_TIME" => (new DateTime('now', new DateTimeZone("Asia/Kuala_Lumpur")))->format("h:i:s A d/m/Y")
                                ];
                                $query = "INSERT INTO `$tableName` (";
                                $addedBefore = false;
                                foreach($data as $key => $val){
                                    if($addedBefore) $query .= ",";
                                    $query .= " `".$key."`";
                                    $addedBefore = true;
                                }
                                $addedBefore = false;
                                $query .= ") VALUES (";
                                foreach($data as $key => $val){
                                    if($addedBefore) $query .= ",";
                                    $query .= " '".str_replace("'", "''", $val)."'";
                                    $addedBefore = true;
                                }
                                $query .= ");";
                                break;
                            default:
                                $editSuccess = false;
                                $editError = "Action not found!";
                        }
                        break;
                        default:
                            $editSuccess = false;
                            $editError = "Data type not found!";
                            break;
                }
            }
            else{
                if(!isset($_POST["objectType"])){
                    $editSuccess = false;
                    $editError = "Data type not found!";
                }
                else if(!isset($_POST["id"])){
                    $editSuccess = false;
                    $editError = "Data ID not found!";
                }
                else switch($_POST["objectType"]){
                    case "songs":
                        $query = "DELETE FROM `song` WHERE `SONG_ID` = ".$_POST["id"].";";
                        break;
                    case "feedbacks":
                        $query = "DELETE FROM `feedback` WHERE `FEEDBACK_ID` = ".$_POST["id"].";";
                        break;
                    case "secrets":
                        $searchQuery = "SELECT ADMIN_SECRET FROM `admin` WHERE SECRET_ID = ".$_POST["id"].";";
                        $result = $connection->query($searchQuery);
                        if($result->num_rows < 1){
                            $editSuccess = false;
                            $editError = "Secret not found!";
                            break;
                        }
                        $previousKey = $result->fetch_all()[0][0];
                        if($previousKey == $current_secret){
                            $editSuccess = false;
                            $editError = "Cannot delete current secret key!";
                            break;
                        }
                        $query = "DELETE FROM `admin` WHERE `SECRET_ID` = ".$_POST["id"].";";
                        break;
                    default:
                        $editSuccess = false;
                        $editError = "Data type not found!";
                        break;
                }
            }
            if($editSuccess) $connection->query($query);
        }
        ?>
    </head>
    <body onload="initializeTextArea();">
        <header id="topbar">
            <div id="logo-area">
                <a href="/"><img id="logged-in-logo" src="/media/pikaa.png" alt="pikaa logo"></a>
            </div>
            <p><?php echo "Current secret key: <span style=\"background-color: green; padding: 0px 2px;\">".$current_secret."</span>" ?></p>
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
                <?php if(isset($_POST["action"])) if($_POST["action"] == "edit" || $_POST["action"] == "create"){ ?>
                    <p style="font-size:large;"><?php echo $editSuccess ? "Data has been successfully saved." : "Failed to save data. <br>Error: $editError" ?></p>
                <?php } ?>
                <?php if(isset($_POST["action"])) if($_POST["action"] == "delete"){ ?>
                    <p style="font-size:large;"><?php echo $editSuccess ? "Data has been successfully deleted." : "Failed to delete data. <br>Error: $editError" ?></p>
                <?php } ?>
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
                    <?php if(isset($_GET["search"])) { ?>
                    <div id="search-info-container">
                        <p>Searching: <span><?php echo $_GET["search"]; ?></span></p>
                        <a href="?view=<?php echo $_GET["view"] ?>">Reset</a>
                    </div>
                    <?php } ?>
                    <form method="GET" id="search-bar">
                        <input type="hidden" name="view" value="<?php echo $_GET["view"]; ?>">
                        <input type="text" name="search" placeholder="Search..." required>
                        <button type="submit">Search</button>
                    </form>
                <?php } ?>
            </div>
            <?php if(isset($_GET["view"])){ ?>
                <?php if(!isset($_GET["id"]) && !isset($_GET["action"])){ ?>
                    <div id="section-list-container">
                        <?php function addListRow(string $title, array $descriptions, string $rightInformation, int $id, array $searchKeywords = []){ ?>
                        <div class="list-item">
                            <div class="list-item-left">
                                <?php
                                foreach($searchKeywords as $search){
                                    $search = preg_quote($search, '/');
                                    $title = preg_replace("/($search)/i", "<span class=\"search-result\">$1</span>", $title);
                                    foreach($descriptions as $key => $desc)
                                        $descriptions[$key] = preg_replace("/($search)/i", "<span class=\"search-result\">$1</span>", $desc);
                                    $rightInformation = preg_replace("/($search)/i", "<span class=\"search-result\">$1</span>", $rightInformation);
                                } 
                                ?>
                                <h3><?php echo $title; ?></h3>
                                <p><?php foreach($descriptions as $key => $description){ echo $description; if(isset($descriptions[$key+1])) if($descriptions[$key+1] != "") echo "<br>"; }; ?></p>
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
                                $searchQuery = "";
                                if(!isset($_GET["search"])){
                                    $searchQuery = "SELECT SONG_TITLE, SONG_ARTIST, SONG_GENRE, SONG_ID from song;";
                                    $result = $connection->query($searchQuery);
                                    $pageCount = ceil($result->num_rows/$contentPerPage);
                                    $fetched = $result->fetch_all();
                                    for($i = ($page - 1) * $contentPerPage; $i < min($result->num_rows, $page*$contentPerPage); $i++){
                                        $row = $fetched[$i];
                                        $title = $row[0];
                                        $artist = $row[1];
                                        $genre = $row[2];
                                        $id = intval($row[3]);
                                        addListRow($title, [ $artist, "ID: ".$id ], $genre, $id);
                                    }
                                }else{
                                    $searchVal = explode(" ", $_GET["search"]);
                                    $searchQuery = "SELECT SONG_TITLE, SONG_ARTIST, SONG_GENRE, SONG_ID, SONG_LYRICS FROM song WHERE";
                                    $notFirst = false;
                                    foreach($searchVal as $search){
                                        $searchWildcard = "%".str_replace("'", "''", $search)."%";
                                        if($notFirst) $searchQuery .= " OR";
                                        $searchQuery .= " SONG_TITLE LIKE '$searchWildcard' OR SONG_ARTIST LIKE '$searchWildcard' OR SONG_GENRE LIKE '$searchWildcard' OR SONG_LYRICS LIKE '$searchWildcard' OR SONG_ID LIKE '$searchWildcard'";
                                        $notFirst = true;
                                    }
                                    $searchQuery .= ";";
                                    $result = $connection->query($searchQuery);
                                    $pageCount = ceil($result->num_rows/$contentPerPage);
                                    $fetched = $result->fetch_all();
                                    $shownLyricsLength = 50;
                                    for($i = ($page - 1) * $contentPerPage; $i < min($result->num_rows, $page*$contentPerPage); $i++){
                                        $row = $fetched[$i];
                                        $title = $row[0];
                                        $artist = $row[1];
                                        $genre = $row[2];
                                        $lyrics = $row[4];
                                        $id = intval($row[3]);
                                        $lyricsSearch = "";
                                        foreach($searchVal as $search){
                                            if(str_contains(strtolower($lyrics), strtolower($search))){
                                                $lyricsSearch = $search;
                                                break;
                                            }
                                        }
                                        $lyricsOffset = max(stripos($lyrics, $lyricsSearch)-25, 0);
                                        addListRow($title, [$artist, "Lyrics search: ".($lyricsOffset == 0 ? "" : "...").substr($lyrics, $lyricsOffset, $shownLyricsLength).($lyricsOffset+$shownLyricsLength < strlen($lyrics) ? "..." : ""), "ID: ".$id], $genre, $id, $searchVal);
                                    }
                                }
                                break;
                            case "feedbacks":
                                addCreateObjectRow("Create a new feedback for testing purposes", "Feedback messages are great to make sure that the website work perfectly as intended. ", "");
                                $feedbackDisplayLength = 75;
                                if(!isset($_GET["search"])){
                                    $searchQuery = "SELECT SENDER_NAME, FEEDBACK_TEXT, FEEDBACK_TIME, FEEDBACK_ID FROM feedback;";
                                    $result = $connection->query($searchQuery);
                                    $pageCount = ceil($result->num_rows/$contentPerPage);
                                    $fetched = $result->fetch_all();
                                    for($i = ($page - 1) * $contentPerPage; $i < min($result->num_rows, $page*$contentPerPage); $i++){
                                        $row = $fetched[$i];
                                        $senderName = $row[0];
                                        $text = $row[1];
                                        $time = $row[2];
                                        $id = intval($row[3]);
                                        if(strlen($text) > 75)
                                            $text = substr($text, 0, $feedbackDisplayLength)."...";
                                        addListRow($senderName, [ $text ], $time, $id);
                                    }
                                }else{
                                    $searchVal = explode(" ", $_GET["search"]);
                                    $searchQuery = "SELECT SENDER_NAME, SENDER_GENDER, SENDER_EMAIL, FEEDBACK_TYPE, FEEDBACK_TEXT, FEEDBACK_TIME, FEEDBACK_ID FROM feedback WHERE";
                                    $notFirst = false;
                                    foreach($searchVal as $search){
                                        $searchWildcard = "%".str_replace("'", "''", $search)."%";
                                        if($notFirst) $searchQuery .= " OR";
                                        $searchQuery .= " SENDER_NAME LIKE '$searchWildcard' OR SENDER_GENDER LIKE '$searchWildcard' OR SENDER_EMAIL LIKE '$searchWildcard' OR FEEDBACK_TYPE LIKE '$searchWildcard' OR FEEDBACK_TEXT LIKE '$searchWildcard' OR FEEDBACK_TIME LIKE '$searchWildcard'";
                                        $notFirst = true;
                                    }
                                    $searchQuery .= ";";
                                    $result = $connection->query($searchQuery);
                                    $pageCount = ceil($result->num_rows/$contentPerPage);
                                    $fetched = $result->fetch_all();
                                    for($i = ($page - 1) * $contentPerPage; $i < min($result->num_rows, $page*$contentPerPage); $i++){
                                        $row = $fetched[$i];
                                        $senderName = $row[0];
                                        $gender = $row[1];
                                        $email = $row[2];
                                        $type = $row[3];
                                        $text = $row[4];
                                        $time = $row[5];
                                        $id = intval($row[6]);
                                        $textSearch = "";
                                        $containEmail = false;
                                        $containGender = false;
                                        $containType = false;
                                        foreach($searchVal as $search){
                                            if(str_contains(strtolower($email), strtolower($search))) $containEmail = true;
                                            if(str_contains(strtolower($gender), strtolower($search))) $containGender = true;
                                            if(str_contains(strtolower($type), strtolower($search))) $containType = true;
                                            if(str_contains(strtolower($text), strtolower($search)) && $textSearch == ""){
                                                $textSearch = $search;
                                            }
                                        }
                                        
                                        $textOffset = max(stripos($text, $textSearch)-25, 0);
                                        addListRow($senderName, [ "Feedback search: ".($textOffset > 0 ? "..." : "").substr($text, $textOffset, $feedbackDisplayLength).($textOffset + $feedbackDisplayLength < strlen($text) ? "..." : ""), $containEmail ? "Email search: ".$email : "", $containGender ? "Gender search: ".$gender : "", $containType ? "Feedback type: ".$type : "" ], $time, $id, $searchVal);
                                    }
                                }
                                break;
                            case "secrets":
                                addCreateObjectRow("Create a new secret key", "Secret keys are used to open the admin dashboard. ", "");
                                if(!isset($_GET["search"])){
                                    $searchQuery = "SELECT SECRET_ID, ADMIN_SECRET FROM admin;";
                                    $result = $connection->query($searchQuery);
                                    $pageCount = ceil($result->num_rows/$contentPerPage);
                                    $fetched = $result->fetch_all();
                                    for($i = ($page - 1) * $contentPerPage; $i < min($result->num_rows, $page*$contentPerPage); $i++){
                                        $row = $fetched[$i];
                                        $secretKey = $row[1];
                                        $id = intval($row[0]);
                                        addListRow($id, [ $secretKey ], "", $id);
                                    }
                                } else{
                                    $searchVal = explode(" ", $_GET["search"]);
                                    $searchQuery = "SELECT SECRET_ID, ADMIN_SECRET FROM admin WHERE";
                                    $notFirst = false;
                                    foreach($searchVal as $search){
                                        $searchWildcard = "%".str_replace("'", "''", $search)."%";
                                        if($notFirst) $searchQuery .= " OR";
                                        $searchQuery .= " SECRET_ID LIKE '$searchWildcard' OR ADMIN_SECRET LIKE '$searchWildcard'";
                                        $notFirst = true;
                                    }
                                    $searchQuery .= ";";
                                    $result = $connection->query($searchQuery);
                                    $pageCount = ceil($result->num_rows/$contentPerPage);
                                    $fetched = $result->fetch_all();
                                    for($i = ($page - 1) * $contentPerPage; $i < min($result->num_rows, $page*$contentPerPage); $i++){
                                        $row = $fetched[$i];
                                        $secretKey = $row[1];
                                        $idDisplay = $row[0];
                                        $id = intval($row[0]);
                                        addListRow($idDisplay, [ $secretKey ], "", $id, $searchVal);
                                    }
                                }
                                break;
                        }
                        ?>
                    </div>
                <?php } else if(isset($_GET["id"])) { ?>
                    <?php
                    $found = false;
                    $data = [];
                    switch($_GET["view"]){
                        case "songs":
                            $searchQuery = "SELECT SONG_TITLE, SONG_GENRE, SONG_ARTIST, SONG_RELEASE_YEAR, SONG_LYRICS, SONG_COVER_URL, SONG_VIDEO_URL, SONG_MUSICS_URL FROM song WHERE SONG_ID=".$_GET["id"].";";
                            $result = $connection->query($searchQuery);
                            if($result->num_rows < 0) break;
                            $found = true;
                            $fetchedData = $result->fetch_all()[0];
                            if(!isset($_GET["action"])){
                                $fetchedData = array_map(fn($val): string => nl2br($val), $fetchedData);
                            }
                            $data = [
                                "Title"=>$fetchedData[0],
                                "Genre"=>$fetchedData[1],
                                "Artist"=>$fetchedData[2],
                                "Release Year"=>$fetchedData[3],
                                "Lyrics"=>$fetchedData[4],
                                "Cover URL"=> !isset($_GET["action"]) ? $fetchedData[5]."<br><br><img alt=\"song cover\" style=\"width: 300px;\" src=\"".$fetchedData[5]."\">" : ($_GET["action"] == "edit"  ? $fetchedData[5] : "[ Unknown Action! ]") ,
                                "Youtube Embed URL"=>!isset($_GET["action"]) ? $fetchedData[6]."<br><br><iframe src=\"".$fetchedData[6]."\" title=\"Embed Youtube Video\" style=\"width: 300px;\"></iframe>" : ($_GET["action"] == "edit" ? $fetchedData[6] : "[ Unknown Action! ]"),
                                "Spotify Embed URL"=>!isset($_GET["action"]) ? $fetchedData[7]."<br><br><iframe src=\"".$fetchedData[7]."\" title=\"Embed Spotify Music\" style=\"width: 300px; height: 80px;\"></iframe>" : ($_GET["action"] == "edit" ? $fetchedData[7] : "[ Unknown Action! ]")
                            ];
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
                                "Feedback Messages"=>$fetchedData[4],
                                "Feedback Time"=>$fetchedData[5]
                            ];
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
                            break;
                    }
                    if(!isset($_GET["action"])) createDisplayTable($data);
                    else if($_GET["action"] == "edit") createEditTable($data);
                    ?>
                    <div id="bottom-edit-actionbar">
                        <?php if($_GET["view"] != "feedbacks"){ ?>
                        <?php if(!isset($_GET["action"])){ ?>
                        <form method="GET" id="bottom-edit-left-form">
                            <input type="hidden" name="view" value=<?php echo $_GET["view"] ?>>
                            <input type="hidden" name="id" value=<?php echo $_GET["id"] ?>>
                            <input type="hidden" name="action" value="edit">
                            <input type="submit" value="Edit">
                        </form>
                        <?php } else if($_GET["action"] == "edit"){ ?>
                        <form>
                            <input form="editor-container-form" type="submit" value="Save">
                        </form>
                        <?php } ?>
                        <?php } ?>
                        <form method="POST" id="bottom-edit-right-form" onsubmit="return deleteConfirmation();" action="?view=<?php echo $_GET["view"]; ?>">
                            <input type="hidden" name="objectType" value=<?php echo $_GET["view"] ?>>
                            <input type="hidden" name="id" value=<?php echo $_GET["id"] ?>>
                            <input type="hidden" name="action" value="delete">
                            <input id="delete-confirmation" type="text" placeholder="confirm_delete" oninput="onDeleteConfirmationChange(this.value);">
                            <input id="object-delete-button" type="submit" value="Delete" disabled>
                        </form>
                    </div>
                <?php } else if(isset($_GET["action"])) if($_GET["action"] == "create") { ?>
                <?php
                $data = [];
                switch($_GET["view"]){
                    case "songs":
                        $data = [
                            "Title" => "The title of the song",
                            "Genre" => "The genre(s) of the song",
                            "Artist" => "The artist(s) of the song",
                            "Release Year" => "The year the song was released",
                            "Lyrics" => "The lyrics of the song",
                            "Cover URL" => "A link to the cover image of the song",
                            "Youtube Embed URL" => "A link to the Youtube embed for the iframe element",
                            "Spotify Embed URL" => "A link to the Spotify embed for the iframe element"
                        ];
                        break;
                    case "feedbacks":
                        $data = [
                            "Sender Name" => "A name for the sender",
                            "Gender" => "The gender of the sender",
                            "Email" => "The email of the sender",
                            "Feedback Type" => "A type for these feedback messages",
                            "Feedback Messages" => "The messages of this feedback"
                        ];
                        break;
                    case "secrets":
                        $data = [
                            "Secret Key" => "A secret key used for the admin authorization"
                        ];
                        break;
                }
                createEditTable($data, false, true);
                ?>
                <div id="bottom-edit-actionbar">
                    <form>
                        <input form="editor-container-form" type="submit" value="Save">
                    </form>
                </div>
                <?php }
            }else{
            ?>
            <p>This admin dashboard is used to check all of the feedback submissions from users. Song entries can also be edited using this page. Same goes to the secret key entries. </p>
            <?php } ?>
        </section>
        <?php if(isset($_GET["view"])){ ?>
            <div id="footer-pagination">
                <?php if(!isset($_GET["id"])){ ?>
                <p>Current Page: <?php echo isset($_GET["page"]) ? $_GET["page"] : 1; ?></p>
                <?php } ?>
                <?php 
                for($i = 1; $i <= min($pageCount, 10); $i++){ 
                ?>
                    <a class="page-link" href="<?php echo "index?view=".$_GET["view"]; if(isset($_GET["search"])) echo "&search=".$_GET["search"]; echo "&page=".($i <= 5 ? $i : $pageCount-10+$i); ?>"><?php echo $i <= 5 ? $i : $pageCount-10+$i;; ?></a>
                <?php
                } 
                ?>
                <form method="GET">
                    <input type="hidden" name="view" value="<?php echo $_GET["view"]; ?>">
                    <?php if(isset($_GET["search"])){ ?>
                    <input type="hidden" name="search" value="<?php echo $_GET["search"] ?>">
                    <?php } ?>
                    <input id="page-input" type="number" name="page" min="1" max="<?php echo max(1, $pageCount) ?>" placeholder="Page" required>
                    <button id="page-submit-button" type="submit">Go</button>
                </form>
            </div>
        <?php } ?>
        <footer class="footer">
            <p>© 2025 PIKAA. All rights reserved.</p>
            <p class="powered">⚙️ Powered by <a href="https://github.com">github.com</a></p>
        </footer>
    </body>
</html>
<?php
$connection->close();
?>