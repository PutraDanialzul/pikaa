<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIKAA - The Song Listing Site!</title>
    <meta name="description" content="Send a feedback to PIKAA.">
    <meta name="keywords" content="songs, music, media, listing">
    <meta name="title" content="Send Feedback - PIKAA">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="/feedback_styles.css">
</head>

<body>
    <?php function createSuccessBanner($comment){ ?>
    <div id="success-banner">
        <?php echo $comment; ?>
    </div>
    <?php } ?>
    <?php function createFailBanner($comment){ ?>
    <div id="fail-banner">
        <?php echo $comment; ?>
    </div>
    <?php } ?>
    <?php
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(!isset($_POST["name"])){
            createFailBanner("Failed to send feedback. Error: Sender's name not found!");
        }
        else if(!isset($_POST["gender"])){
            createFailBanner("Failed to send feedback. Error: Sender's gender not found!");
        }
        else if(!isset($_POST["email"])){
            createFailBanner("Failed to send feedback. Error: Sender's email not found!");
        }
        else if(!isset($_POST["type_of_feedback"])){
            createFailBanner("Failed to send feedback. Error: Feedback type not found!");
        }
        else if(!isset($_POST["feedback"])){
            createFailBanner("Failed to send feedback. Error: Feedback messages not found!");
        }
        else{
            $name = $_POST["name"];
            $gender = $_POST["gender"];
            $email = $_POST["email"];
            $type = $_POST["type_of_feedback"];
            $feedback = $_POST["feedback"];
            require $_SERVER['DOCUMENT_ROOT']."/dbInfo.php";
            $connection = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);
            if($connection->connect_error){
                createFailBanner("Failed to send feedback. Error: Connection failed. ".$connection->connect_error);
            }
            else{
                $dateTime = new DateTime();
                $dateTime->setTimeZone(new DateTimeZone("Asia/Kuala_Lumpur"));
                $time = $dateTime->format("h:i:s A d/m/o");
                $query = "INSERT INTO `feedback`(`SENDER_NAME`, `SENDER_GENDER`, `SENDER_EMAIL`, `FEEDBACK_TYPE`, `FEEDBACK_TEXT`, `FEEDBACK_TIME`) VALUES (\"$name\", \"$gender\", \"$email\", \"$type\", \"$feedback\", \"$time\");";
                if(!$connection->query($query))
                    createFailBanner("Failed to send feedback. Error: Database query failed. ".$connection->error);
                else
                    createSuccessBanner("Feedback successfully sent!");
                $connection->close();
            }
        }
    } ?>  
    <a href="/"><img src="/media/pikaa.jpg" alt="PIKAA LOGO" class="center-img" width="200"></a>

    <h1 class="title">SEND A FEEDBACK TO US :)</h1>
    <h3 class="subtitle"><i>Let us know if you have any songs recommendation or suggestion</i></h3>

    <form method="POST" class="feedback-form">
        
        <label for="name">Your Name: <span style="color:red;">*</span></label>
        <input type="text" id="name" name="name" placeholder="e.g., Isyraf" required>

        <label>Your Gender: <span style="color:red;">*</span></label>
        <div class="gender-options">
            <label><input type="radio" name="gender" value="male" required> Male</label>
            <label><input type="radio" name="gender" value="female" required> Female</label>
        </div>

        <label for="email">Your Email: <span style="color:red;">*</span></label>
        <input type="email" id="email" name="email" placeholder="e.g., isyraf@example.com" required>

        <fieldset class="card">
            <legend>Feedback Details</legend>

            <label>Type of Feedback: <span style="color:red;">*</span></label>
            <select name="type_of_feedback" required>
                <option value="suggestion">Suggestion</option>
                <option value="complaint">Complaint</option>
                <option value="enquiry">Enquiry</option>
            </select>

            <label for="feedback">Your Feedback: <span style="color:red;">*</span></label>
            <textarea id="feedback" name="feedback" rows="4" placeholder="type your text here..." required></textarea>
        </fieldset>

        <a href="/index.html#successSubmit">
            <input id="submitButton" type="submit" value="Submit">
        </a>

        <a href="/">
            <input id="backButton" type="button" value="Go Back">
        </a>
    </form>

</body>
</html>
