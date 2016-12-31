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

<!-- Displays reservations made by current user -->
<!DOCTYPE html>
    <html>
        <head>
            <title>My Reservations</title>
            <link href="style.css" type="text/css" rel="stylesheet" />
            <script src="confirm.js" type="text/javascript"></script>
        </head>
        <body>
            <?php include "window.php"; ?>
            <h3>Here are your reservations!</h3>
            <label>Click to confirm one.</label>
            <div class="center">
                <?php
                    // Delete reservations made after 2PM at the ADC (closes at 2)
                    $clearCafAfter2 = "DELETE FROM reservations WHERE location_id=1
                        AND (timeslot_id=4 OR timeslot_id=7)";
                    $deleteReservations = mysqli_query($db, $clearCafAfter2);
                    
                    // Get user reservations: location (name & graphic), time, and confirmation
                    $getReservations = "SELECT reservations.id, reservations.location_id, 
                        reservations.timeslot_id, reservations.confirmed, locations.name, 
                        locations.graphic, timeslots.slot FROM reservations JOIN locations 
                        ON reservations.location_id=locations.id JOIN timeslots ON 
                        reservations.timeslot_id=timeslots.id WHERE user_id='$user_id'
                        ORDER BY locations.name ASC";
                    $reservations = mysqli_query($db, $getReservations);
                    $lastRestaurant = $currentRestaurant = "";
                    
                    // Displays reservations by location
                    while ($row = mysqli_fetch_assoc($reservations)) {
                        $reservationID = $row['id'];
                        // First, get the location and its graphic
                        $currentRestaurant = $row['name'];
                        $currentRestID = $row['location_id'];
                        if ($currentRestaurant != $lastRestaurant) {
                            echo "<br/><br/><img src=\"" . $row['graphic'] . "\" alt=\"" . $row['name'] . 
                            "\" title=\"" . $row['name'] . "\" /><br/>";
                            $lastRestaurant = $currentRestaurant;
                        }
                        
                        // Next, get the reserved time and see how many people have reserved the same time
                        $currentSlotID = $row['timeslot_id'];
                        $resQuery = "SELECT id FROM reservations WHERE location_id='$currentRestID' 
                                    AND timeslot_id='$currentSlotID'";
                        $resResult = mysqli_query($db, $resQuery);
                        $numReservations = mysqli_num_rows($resResult);
                        
                        // Display "confirmable" link
                        if ($numReservations > 1) {
                            echo "<button id=\"$reservationID\" title=\"$numReservations people have reserved this time\"
                                onclick=\"confirmReservation($reservationID)\">" . $row['slot'] . "</button>&emsp;";
                        } else if ($numReservations == 1) {
                            echo "<button id=\"$reservationID\" title=\"Only you have reserved this time\"
                                onclick=\"confirmReservation($reservationID);\">" . $row['slot'] . "</button>&emsp;";
                        }
                    }
                ?>
            <br/><br/><br/><br/><br/>
            </div>
        </body>
    </html>
</DOCTYPE>