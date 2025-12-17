<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PIKAA — INGAT</title>
  <link rel="stylesheet" href="info-styles.css">
  <link rel="stylesheet" href="/styles.css">
</head>


<body>

  <!-- Header -->
  <a href="/" class="header">
    <img src="/media/pikaa.png" alt="PIKAA Logo" class="logo">
  </a>

  <main class="container">
  <?php
      if(!isset($_GET["id"])){
        die("Error: ID not found!");
        return;
      }
      require $_SERVER['DOCUMENT_ROOT']."/dbInfo.php";
      $connection = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);
      if($connection->connect_error){
          die("Error: Connection failed. ".$connection->connect_error);
      }else{
        $id = intval($_GET["id"]);
        $query = "SELECT `SONG_TITLE`, `SONG_GENRE`, `SONG_ARTIST`, `SONG_RELEASE_YEAR`, `SONG_LYRICS`, `SONG_COVER_URL`, `SONG_VIDEO_URL`, `SONG_MUSICS_URL` FROM `song` WHERE `SONG_ID`=$id LIMIT 1;";
        $result = $connection->query($query);
        if(!$result){
            die("Error: Select query failed. ".$connection->error);
        }
        else if($result->num_rows > 0){
          $fetchedData = $result->fetch_all();
          $songData = $fetchedData[0];
          //var_dump($songData);
  ?>

    <!-- Top Section -->
    <section class="top-section">

      <!-- Album Cover -->
      <div class="cover">
        <img 
          src="<?php echo $songData[5]; ?>"
          alt="<?php echo $songData[0]."album cover"; ?>"?>
      </div>

      <!-- Song Details -->
      <div class="details">
        <h1><?php echo $songData[0]; ?></h1>
        <p><span>Genre</span><?php echo $songData[1]; ?></p>
        <p><span>Artist</span><?php echo $songData[2]; ?></p>
        <p><span>Released</span><?php echo $songData[3] ?></p>
      </div>

    </section>

    <!-- Bottom Section -->
    <section class="bottom-section">

      <!-- Lyrics -->
      <div class="lyrics">
        <h2>Lyrics</h2>
        <p>
          <?php echo nl2br($songData[4]); ?>
        </p>
      </div>

      <!-- Media -->
      <div class="media">
        <iframe
          src="<?php echo $songData[6]; ?>"
          title="YouTube player"
          allowfullscreen>
        </iframe>

        <iframe 
          src="<?php echo $songData[7]; ?>"
          title="Spotify player"
          allow="encrypted-media">
        </iframe>
      </div>

    </section>

  </main>

  <footer class="footer">
      <p>© 2025 PIKAA. All rights reserved.</p>
      <p class="powered">⚙️ Powered by <a href="https://github.com">github.com</a></p>
  </footer>
  <?php
    } else{
      echo "Error: Song not found!";
    }
    $connection->close();
  }
  ?>

</body>
</html>
