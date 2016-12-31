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
    
    // The resetReservations() function checks that the reservations are
    // refreshed on a daily basis
    function resetReservations($db) {
        // Resets the reservation table if they are from the previous day.
        date_default_timezone_set("America/Phoenix");
        $setTime = "SET time_zone=\"-07:00\"";
        $timeResult = mysqli_query($db, $setTime);
                                
        $query = "SELECT * FROM reservations LIMIT 1";              // Grab the first entry
        $result = mysqli_query($db, $query);
        while ($firstEntry = mysqli_fetch_assoc($result)) {
            $firstTimestamp = strtotime($firstEntry['created']);    // Get first entry's timestamp
        }
        $oldDate = date('Y-m-d', $firstTimestamp);                  // Convert first entry's timestamp to date format
        $newDate = date('Y-m-d');                                   // Get today's date
        
        if ($oldDate != $newDate) {                                 // Deletes all reservations if they are not from today
            echo "Dates are not the same.";
            echo "Old date: $oldDate, New date: $newDate";
            $deleteQuery = "DELETE FROM reservations";
            $newResult = mysqli_query($db, $deleteQuery);
        }
    }
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Logging In</title>
        </head>
        <body>
            <?php
                // Start by resetting reservation list
                resetReservations($db);
                
                header("Location: menu.php");
            ?>
        </body>
    </html>