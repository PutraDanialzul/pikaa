<?php
function logOut(){
    session_destroy();
    echo "<script>window.location.href = \"login\";</script>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Admin Dashboard - PIKAA</title>
        <link rel="stylesheet" href="/styles.css">
        <link rel="stylesheet" href="styles.css">
        <?php
        session_start();
        if(!isset($_SESSION["secret"])) logOut();
        ?>
    </head>

</html>