<?php
    session_start();
    
    $servername = getenv('IP');
    $user = getenv('C9_USER');
    $password = "";
    $database = "lunch";
    $dbport = 3306;
    // Create connection
    $db = new mysqli($servername, $user, $password, $database,
    $dbport);
    // Check connection)
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    
    $user_id = $_SESSION['user_id'];
    
    // The weekday() function determines the day of the week (Mon-Fri) and
    // the corresponding time slots for lunch
    function weekday(&$slots, &$even_odd) {
        $today = getdate();
        $day = $today["weekday"];
        print "<div class=\"date\">" . $day . "</div><br/>";
        if ($day == "Monday" || $day == "Wednesday" || $day == "Friday" || $day == "Sunday") {
            array_push($slots, "10:45 - 11:45", "12:00 - 1:00", "1:15 - 2:15", "2:30 - 3:30");
            $even_odd = 1; //Odd days of the week
        } else if ($day == "Tuesday" || $day == "Thursday" || $day == "Saturday") {
            array_push($slots, "10:30 - 12:00", "12:15 - 1:45", "2:00 - 3:30");
            $even_odd = 0; //Even days of the week
        }
    }
    
    // The printSlots() function prints out checkbox options for lunch
    // timeslots, according to the values determined by weekday() func
    function printSlots($db, $even_odd) {
        $query = "SELECT id, slot FROM timeslots WHERE even_odd=\"" . $even_odd . "\"";
        $result = mysqli_query($db, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<label><input type=\"checkbox\" name=\"times[]\" value=\"" . $row['id'] . "\">" . $row['slot'] . "</label><br/>";
        }
    }
    
    // The printLocs() function prints the available dining areas
    function printLocs($db) {
        $query = "SELECT id, graphic, name FROM locations";
        $result = mysqli_query($db, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<label><input type=\"checkbox\" name=\"locations[]\" value=\"" . $row['id'] . "\">";
            echo "<img src=\"" . $row['graphic'] . "\" alt=\"" . $row['name'] . "\" title=\"" . $row['name'] . "\" /></label> &emsp;";
        }
    }
    
    // Thec checkForReservation() function redirects the user to
    // the reservations page if they have made reservations
    function checkForReservation($db, $user_id) {
        // Check if the user has already made their reservations
        $resQuery = "SELECT * FROM reservations WHERE user_id='$user_id' LIMIT 1";
        $resResult = mysqli_query($db, $resQuery);
        $hasRes = false;
        while ($row = mysqli_fetch_assoc($resResult)) {
            $hasRes = true;
            // Check if the user has already made a commitment
        }
        
        if ($hasRes) {
            header("Location: reservations.php");
        }
    }
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Menu</title>
            <link href="style.css" type="text/css" rel="stylesheet" />
        </head>
        <body>
            <?php
                include "window.php";
                $even_odd = 0;
                $slots = array();
                weekday($slots, $even_odd);
                checkForReservation($db, $user_id);
                
                date_default_timezone_set("America/Phoenix");       // SET time_zone='-7:00';
                
                if (isset($_POST["res"])) {
                    if (isset($_POST["locations"]) && isset($_POST["times"])) {
                        //$_SESSION["times"] = $_POST["times"];
                        //$_SESSION["locs"] = $_POST["locations"];
                        
                        $location = $_POST["locations"];
                        $timeslot = $_POST["times"];
                        $user_id = $_SESSION['user_id'];
                        foreach ($location as $loc) {
                            foreach ($timeslot as $time) {
                                date_default_timezone_set("America/Phoenix");
                                $setTime = "SET time_zone=\"-07:00\"";
                                $timeResult = mysqli_query($db, $setTime);
                                $query = "INSERT INTO reservations VALUES (null, '$user_id', '$loc', '$time', 0, null)";
                                $result = mysqli_query($db, $query);
                            }
                        }
                        header("Location: reservations.php");
                    } else {
                    if (!isset($_POST["locations"])) {
                        echo "<br/><span class=\"errorMSG\">Please select one or more locations.</span><br/>";
                    }
                    if (!isset($_POST["times"])) {
                        echo "<br/><span class=\"errorMSG\">Please select one or more time slots.</span><br/><br/>";
                    }
                    }
                }
            ?>
            <!--<button>Lunch</button><button>Dinner</button><br/>-->
            <form action="menu.php" method="post">
                <div class="location">
                    <h2>Where would you like to eat today?</h2>
                    <?php printLocs($db); ?>
                </div>
                <div class="resTimes">
                    <h2>When are you available?</h2>
                    <?php printSlots($db, $even_odd); ?>
                </div>
                <input type="submit" name="res" value="Reserve!">
            </form>
        </body>
    </html>