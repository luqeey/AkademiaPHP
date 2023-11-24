<?php
define('TXT_FILE_PATH', 'session_data.txt');

session_start();
$h = date("H");
$delay = false;

date_default_timezone_set( 'CET' );

function isDelay($h, &$delay) {
    if ($h >= 8) {
        $delay = true;
    }
}


function addToHistory($entry) {
    if(!isset($_SESSION['history'])) {
        $_SESSION['history'] = [];
    }
    $_SESSION['history'][] = $entry;

}

function saveSessionToTxt($h, &$delay) {
    if($h >= 23 && $h <= 24) {
        die("Nepodarilo sa zapisat cas pretoze je neplatny.");
    }

    if($delay = true) {
        file_put_contents(TXT_FILE_PATH, date('d-m-Y, H:i:s') . " - meskanie\n", FILE_APPEND);
    } else {
        file_put_contents(TXT_FILE_PATH, date('d-m-Y, H:i:s') . "\n", FILE_APPEND);
    }
}

function getLogs() {

    if(file_exists(TXT_FILE_PATH)) {
        $txtContent = file_get_contents(TXT_FILE_PATH);
        echo nl2br("$txtContent\n");
    }
}

if (isset($_POST['addEntry'])) {
    addToHistory(date('d-m-Y, H:i:s'));
    saveSessionToTxt($h, $delay);
    isDelay($h, $delay);
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Akaemia</title>
</head>
<body>

<h1>Prichody</h1>
    <form method="post">
        <input type="submit" name="addEntry" value="Add Entry">
        <?php 
        date_default_timezone_set( 'CET' );
        echo "Current Date and Time: " . date('d-m-Y, H:i:s');
        echo "</br>";
        getLogs();
        ?>
    </form>

</body>
</html>