<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PIKAA - Song Info</title>
    <meta name="description" content="A site that lists songs from various media.">
    <meta name="keywords" content="songs, music, media, listing">
    <meta name="title" content="PIKAA - Song Info">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="/styles.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <!-- Header Logo -->
    <a href="/" class="logo-link">
        <img src="/media/pikaa.png" alt="PIKAA Logo" id="logo">
    </a>
	<?php
    require $_SERVER['DOCUMENT_ROOT']."/dbInfo.php";
    $connection = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);
    $currentPage = 1;
    if(isset($_GET["page"])) $currentPage = max($_GET["page"], 1);
    $pageCount = 0;
    if($connection->connect_error){
        die("Error: Connection failed. ".$connection->connect_error);
    }else{
        $query = "SELECT `SONG_ID`, `SONG_TITLE`, `SONG_GENRE`, `SONG_ARTIST`, `SONG_COVER_URL`, `SONG_LYRICS` FROM `song`";
        $searchKeywords = [];
        if(isset($_GET["search"])){
            $searchKeywords = preg_split('/\s+/', trim($_GET["search"]));;
            $notFirst = false;
            $query .= " WHERE ";
            foreach($searchKeywords as $keyword){
                $searchWildcard = "%".str_replace("'", "''", $keyword)."%";
                if($notFirst) $query .= " OR";
                $query .= "`SONG_TITLE` LIKE '$searchWildcard' OR `SONG_GENRE` LIKE '$searchWildcard' OR `SONG_ARTIST` LIKE '$searchWildcard' OR `SONG_LYRICS` LIKE '$searchWildcard'";
                $notFirst = true;
            }
        }
        $query .= ";";
    	$result = $connection->query($query);
    	if(!$result){
        	die("Error: Select query failed. ".$connection->error);
    	}
    	else{
            $songCount = $result->num_rows;
    		$fetchedData = $result->fetch_all();
            $pageCount = ceil($songCount/6);
	?>

    <!-- Search Bars Section -->
    <div class="search-section">
        <?php if(isset($_GET["search"])) { ?>
        <div id="search-keywords-container">
            <p>Search Keywords: <?php foreach($searchKeywords as $key => $keyword){ echo "<span class=\"search-result\">$keyword</span>"; if(isset($searchKeywords[$key+1])) echo " "; } ?></p><a href="index">Reset</a>
        </div>
        <?php } ?>
        <form class="bar-container center-bar" method="GET" action="index">
            <input id="search-bar" name="search" type="text" placeholder="Search songs by keywords..." required>
            <input class="button-bar" type="submit" value="Search">
        </form>
    </div>
    <h3 class="section-title">OUR ULTIMATE SONG CHOICES</h3>
    <?php function createSongCard($id, $img, $title, $descriptions, $searchKeys = []){ ?>
    <div class="song-card">
        <a href="/songs/info?id=<?php echo $id; ?>">
            <img src="<?php echo $img ?>" alt="Album Art">
            <?php
            foreach($searchKeys as $search){
                $search = preg_quote($search, '/');
                $title = preg_replace("/($search)/i", "<span class=\"search-result\">$1</span>", $title);
                foreach($descriptions as $key => $desc){
                    $descriptions[$key] = preg_replace("/($search)/i", "<span class=\"search-result\">$1</span>", $desc);
                }
            }
            ?>
            <h4><?php echo $title; ?></h4>
            <p>
            <?php foreach($descriptions as $k => $description){ ?>
            <?php echo $description; if(isset($descriptions[$k+1])) if($descriptions[$k+1] != "" && !ctype_space($descriptions[$k+1])) echo "<br>" ?>
            <?php } ?>
            </p>
        </a>
    </div>
    <?php } ?>
    <!-- Song Grid 1 -->
    <div class="song-grid">
        <?php
        for($i = ($currentPage-1)*6; $i < min($songCount, ($currentPage-1)*6 + 3); $i++){
            $searchContainsLyrics = false;
            $displayLyricsOffset = 0;
            $shownLyricsLength = 20;
            if(isset($_GET["search"])){
                foreach($searchKeywords as $searchKey){
                    if(str_contains(strtolower($fetchedData[$i][5]), strtolower($searchKey))){
                        $searchContainsLyrics = true;
                        $displayLyricsOffset = max(stripos(strtolower($fetchedData[$i][5]), strtolower($searchKey))-($shownLyricsLength/2), 0);
                        break;
                    }
                }
            }
            createSongCard($fetchedData[$i][0], $fetchedData[$i][4], $fetchedData[$i][1], [ $fetchedData[$i][3], $fetchedData[$i][2], $searchContainsLyrics ? (($displayLyricsOffset > 0 ? "..." : "").substr($fetchedData[$i][5], $displayLyricsOffset, min($shownLyricsLength, strlen($fetchedData[$i][5]))).($displayLyricsOffset+$shownLyricsLength < strlen($fetchedData[$i][5]) ? "..." : "")) : "" ], $searchKeywords);
        }
        ?>
    </div>

    <!-- Song Grid 2 -->
    <div class="song-grid">
        <?php
        for($i = ($currentPage-1)*6 + 3; $i < min($songCount, ($currentPage-1)*6 + 6); $i++){
            createSongCard($fetchedData[$i][0], $fetchedData[$i][4], $fetchedData[$i][1], [ $fetchedData[$i][3], $fetchedData[$i][2] ], $searchKeywords);
        }
        ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <span class="current-page-info">Current Page: <?php echo $currentPage; ?></span>
        <div class="pagination-controls">
            <?php for($x = 1; $x <= $pageCount; $x++){ ?>
            <a href="index?<?php if(isset($_GET["search"])) echo "search=".$_GET["search"]."&"; ?>page=<?php echo $x; ?>"><?php echo $x; ?></a>
            <?php } ?>
            <form method="GET" action="index">
                <?php if(isset($_GET["search"])){ ?>
                <input type="hidden" name="search" value="<?php echo $_GET["search"]; ?>">
                <?php } ?>
                <input type="number" placeholder="Page" min="1" max="<?php echo $pageCount; ?>" name="page" required>
                <input type="submit" id="page-selection-button" value="Go">
            </form>
        </div>
    </div>
    <?php
        }
        $connection->close();
    }
    ?>

    <footer class="footer">
        <p>© 2025 PIKAA. All rights reserved.</p>
        <p class="powered">⚙️ Powered by <a href="https://github.com">github.com</a></p>
    </footer>
</body>
</html>