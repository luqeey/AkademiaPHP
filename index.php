<?php
define('JSON_FILE_PATH', 'all.json');
define('STUDENTS_PATH', 'students.json');
define('ARRIVALS_PATH', 'arrivals.json');

session_start();
$h = date("H");

date_default_timezone_set('Europe/Bratislava');

function addToHistory($entry, $name) {
    if (!isset($_SESSION['history'])) {
        $_SESSION['history'] = [];
    }
    $entryWithStudent = [
        'time' => $entry,
        'name' => $name
    ];
    array_push($_SESSION['history'], $entryWithStudent);
}

class AllInfos {
    private static function isDelayOccurred()
    {
        $now = new DateTime();
        $currentHour = (int)$now->format('H');
        return $currentHour >= 8;
    }

    public static function saveSessionToJson($name)
    {
        $now = new DateTime();
        $currentHour = (int)$now->format('H');

        if ($currentHour >= 20 && $currentHour <= 24) {
            die("Nepodarilo sa zapisat cas pretoze je neplatny.");
        }

        $entry = ['time' => date('d-m-Y, H:i:s'), 'name' => $name];
        if (self::isDelayOccurred()) {
            $entry['status'] = 'meskanie';
        }

        $jsonContent = [];
        if (file_exists(JSON_FILE_PATH)) {
            $jsonContent = json_decode(file_get_contents(JSON_FILE_PATH), true);
        }

        $jsonContent[] = $entry;
        file_put_contents(JSON_FILE_PATH, json_encode($jsonContent, JSON_PRETTY_PRINT));
    }
}

class Students {
    public static function saveStudent($name)
    {
        $jsonStudents = [];
        if (file_exists(STUDENTS_PATH)) {
            $jsonStudents = json_decode(file_get_contents(STUDENTS_PATH), true);
        }

        $orderNumber = count($jsonStudents) + 1;

        $jsonStudents[] = ['order' => $orderNumber, 'name' => $name];
        file_put_contents(STUDENTS_PATH, json_encode($jsonStudents, JSON_PRETTY_PRINT));
    }
}

class Arrivals {
    private static function isDelayOccurred()
    {
        $now = new DateTime();
        $currentHour = (int)$now->format('H');
        return $currentHour >= 8 && $currentHour <= 20;
    }

    private static function getDelayStatus()
    {
        return self::isDelayOccurred() ? 'meskanie' : null;
    }

    public static function saveArrival()
    {
        $arrivals = [];
        if (file_exists(ARRIVALS_PATH)) {
            $arrivals = json_decode(file_get_contents(ARRIVALS_PATH), true);
        }

        $delayStatus = self::getDelayStatus();

        $arrivals[] = [
            'time' => date('d-m-Y, H:i:s'),
            'status' => $delayStatus
        ];

        file_put_contents(ARRIVALS_PATH, json_encode($arrivals, JSON_PRETTY_PRINT));
    }
}

function getLogs() {
    if (file_exists(JSON_FILE_PATH)) {
        $jsonContent = json_decode(file_get_contents(JSON_FILE_PATH), true);

        foreach ($jsonContent as $entry) {
            echo $entry['time'];

            if (isset($entry['status'])) {
                echo " - " . $entry['status'];
            }
            if (isset($entry['name'])) {
                echo " - MENO: " . $entry['name'];
            }
            echo "<br>";
        }
    }
}

if (isset($_GET['addEntry'])) {
    if (!empty($_GET['addName'])) {
        $studentName = $_GET['addName'];
        addToHistory(date('d-m-Y, H:i:s'), $studentName);
        AllInfos::saveSessionToJson($studentName);
        Students::saveStudent($studentName);
        Arrivals::saveArrival();
    } else {
        echo "Prosim zadaj svoje meno";
    }
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
<form method="get">
    <input type="submit" name="addEntry" value="Add Entry">
    <input type="text" name="addName" placeholder="Meno studenta" required>
    <?php
    date_default_timezone_set('CET');
    echo "Current Date and Time: " . date('d-m-Y, H:i:s');
    echo "</br>";
    getLogs();
    ?>
</form>

</body>
</html>
