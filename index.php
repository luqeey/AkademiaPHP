<?php
session_start();

function addToHistory($entry) {
    if(!isset($_SESSION['history'])) {
        $_SESSION['history'] = array();
    }
    array_push($_SESSION['history'], $entry);
}

if(isset($_POST['addEntry'])) {
    addToHistory(date('Y-m-d, H:i:s'));
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
        echo "Current Date and Time: " . date('Y-m-d, H:i:s');
            if(isset($_SESSION['history'])) {
                foreach($_SESSION['history'] as $entry) {
                    echo "<li>$entry</li>";
                }
            }
        ?>
        </ul>
    </form>

</body>
</html>