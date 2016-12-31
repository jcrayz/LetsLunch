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
    $resID = $_GET['resID'];
    
    // Confirm selected reservation using prepared statement
    $confirmStmt = $db->prepare("UPDATE reservations SET confirmed='1' WHERE id='" . 
        $resID . "' AND user_id='". $user_id . "'");
    $confirmStmt->execute();
    
    if ($confirmStmt->errno) {
       // Something went wrong
       header("Location: reservations.php");
    } else {
       // Success!
       
       // Delete other reservations
       $delQuery = "DELETE FROM reservations WHERE user_id=\"" . $user_id . 
            "\" AND confirmed != \"1\"";
       $delResult = mysqli_query($db, $delQuery);
       header("Location: chat.php");
    }
    $confirmStmt->close();

?>