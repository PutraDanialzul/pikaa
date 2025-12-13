<!DOCTYPE html>
<html lang="en-US">
	<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PIKAA - The Song Listing Site!</title>
        <meta name="description" content="A site that lists songs from various media.">
        <meta name="keywords" content="songs, music, media, listing">
        <meta name="title" content="PIKAA - The Song Listing Site!">
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
        <link rel="stylesheet" href="styles.css">
	</head>
	<body>
		<header id="main-header">
			<div id="contact-menu" onclick=" window.location.href = '/feedback';">
				Contact Us
			</div>
			<div id="credits-menu" onclick=" window.location.href = '/credits';">
				Credits
			</div>
		</header>
		<a href="/" id="main-logo-container">
			<img src="/media/pikaa.png" alt="PIKAA logo">
		</a>
		<a href="/songs/index" id="go-to-list-button">Choose a song</a>
		<?php
        require $_SERVER['DOCUMENT_ROOT']."/dbInfo.php";
        $connection = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);
        if($connection->connect_error){
            die("Error: Connection failed. ".$connection->connect_error);
        }else{
        	$query = "SELECT `SONG_COVER_URL`, `SONG_TITLE`, `SONG_ID` FROM `song` LIMIT  6;";
        	$result = $connection->query($query);
        	if(!$result){
            	die("Error: Select query failed. ".$connection->error);
        	}
        	else if($result->num_rows > 0){
        		$fetchedData = $result->fetch_all();
		?>
		<?php function addSongContainer($imgSource, $songTitle, $id){ ?>
			<a href="/songs/info?id=<?php echo $id; ?>" class="song-container">
				<img src="<?php echo $imgSource; ?>" alt="<?php echo $songTitle; ?>">
			</a>
		<?php } ?>
		<div class="song-list-row">
			<?php
			for($x = 0; $x < min(3, $result->num_rows); $x++){
				addSongContainer($fetchedData[$x][0], $fetchedData[$x][1], $fetchedData[$x][2]);
			}
			?>
		</div>
		<?php
		if($result->num_rows > 3){
		?>
		<div class="song-list-row">
			<?php
			for($x = 3; $x < min(6, $result->num_rows); $x++){
				addSongContainer($fetchedData[$x][0], $fetchedData[$x][1], $fetchedData[$x][2]);
			}
			?>
		</div>
		<?php } ?>
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