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
        

?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Test Page!</title>
        </head>
        <body>
            <?php
                
            ?>
        </body>
    </html>