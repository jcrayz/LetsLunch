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
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>My Reservations</title>
            <link href="style.css" type="text/css" rel="stylesheet" />
        </head>
        <body>
            <?php include "window.php"; ?>
            <h3>Here are your reservations!</h3>
            <div class="center">
                <?php
                    $query = "SELECT reservations.location_id, reservations.timeslot_id, reservations.confirmed,
                    locations.name, locations.graphic, timeslots.slot FROM reservations JOIN locations ON reservations.location_id=locations.id
                    JOIN timeslots ON reservations.timeslot_id=timeslots.id WHERE user_id='$user_id' ORDER BY locations.name ASC";
                    $result = mysqli_query($db, $query);
                    $lastRest = $currentRest = "";
                    while ($row = mysqli_fetch_assoc($result)) {
                        $currentRest = $row['name'];
                        $currentRestID = $row['location_id'];
                        if ($currentRest != $lastRest) {
                            echo "<br/><br/><img src=\"" . $row['graphic'] . "\" alt=\"" . $row['name'] . "\" title=\"" . $row['name'] . "\" /><br/>";
                            $lastRest = $currentRest;
                        }
                        $currentSlotID = $row['timeslot_id'];
                        $resQuery = "SELECT id FROM reservations WHERE location_id='$currentRestID' AND timeslot_id='$currentSlotID'";
                        $resResult = mysqli_query($db, $resQuery);
                        $numReservations = mysqli_num_rows($resResult);
                        echo "<label title=\"$numReservations people have reserved this time\">" . $row['slot'] . "</label>&emsp;";
                    }
                ?>
            </div>
        </body>
    </html>
</DOCTYPE>