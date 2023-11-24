<?php
session_start();
$h = date("H");

date_default_timezone_set( 'CET' );

function isDelay($h) {
    $delay = false;
    if($h >= 8) {
        $delay = true;
    }
}


function addToHistory($entry) {
    if(!isset($_SESSION['history'])) {
        $_SESSION['history'] = array();
    }
    array_push($_SESSION['history'], $entry);

}

function saveSessionToTxt($h) {
    $txtFilePath = 'session_data.txt';
    if($h >= 20 && $h <= 24) {
        die("Nepodarilo sa zapisat cas pretoze je neplatny.");
    }

    if($delay = true) {
        file_put_contents($txtFilePath, date('d-m-Y, H:i:s') . " - meskanie\n", FILE_APPEND);
    } else {
        file_put_contents($txtFilePath, date('d-m-Y, H:i:s') . "\n", FILE_APPEND);
    }
}

function getLogs() {
    $txtFilePath = 'session_data.txt';

    if(file_exists($txtFilePath)) {
        $txtContent = file_get_contents($txtFilePath);
        echo nl2br("$txtContent\n");
    }
}

if (isset($_POST['addEntry'])) {
    addToHistory(date('d-m-Y, H:i:s'));
    saveSessionToTxt($h);
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
        <ul id="historyList">
        <?php 
        date_default_timezone_set( 'CET' );
        echo "Current Date and Time: " . date('d-m-Y, H:i:s');
        echo "</br>";
        getLogs();
        ?>
        </ul>
    </form>

</body>
</html>