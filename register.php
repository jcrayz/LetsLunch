<?php
    session_start();
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Register for Let's Lunch!</title>
            <link href="style.css" type="text/css" rel="stylesheet" />
        </head>
        <body>
            <?php
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
            if (isset($_POST["regButton"])) {
                $allGood = true;
                $fError = $lError = $idError = $uError = $pError = "";
                $firstName = $_POST["fName"];
                $lastName = $_POST["lName"];
                $CBUid = $_POST["CBUid"];
                $username = $_POST["username"];
                $password = $_POST["password"];
                if ($firstName=="") {
                    $fError = "First name required.";
                    $allGood = false;
                } else {
                    if (!preg_match("/^[a-zA-Z ]*$/", $firstName)) {
                        $fError = "Only letters and whitespace allowed.";
                        $allGood = false;
                    }
                }
                if ($lastName=="") {
                    $lError = "Last name required.";
                    $allGood = false;
                } else {
                    if (!preg_match("/^[a-zA-Z ]*$/", $lastName)) {
                        $lError = "Only letters and whitespace allowed.";
                        $allGood = false;
                    }
                }
                if ($CBUid=="") {
                    $idError = "CBU id number required.";
                    $allGood = false;
                } else {
                    if (!preg_match("/^[0-9]*$/", $CBUid)) {
                        $idError = "Only digits allowed.";
                        $allGood = false;
                    }
                }
                if ($username=="") {
                    $uError = "A username is required.";
                    $allGood = false;
                }
                if ($password=="") {
                    $pError = "Password required.";
                    $allGood = false;
                }
                
                if ($allGood) {
                    $sqlCommand = "INSERT INTO users VALUES (null, '" . $firstName . "', '" . $lastName . "', '" . $CBUid .
                    "', '" . $username . "', '" . $password . "', null)";
                    $result = mysqli_query($db, $sqlCommand);
                    
                    if (!$result) {
                        echo "<span class=\"errorMSG\">There was an error registering your account. Contact administration for support.</span><br/>";
                    } else {
                        header("Location: login.php");
                    }
                }
            }
            ?>
            <h3>Register for</h3><h2>Let's Lunch!</h2>
            <form action="register.php" method="post">
                <div class="register">
                    First Name:&nbsp;<input type="text" name="fName" maxlength="20" placeholder="ex. Hannah" />     <span class="errorMSG"><?= $fError; ?></span><br/>
                    Last Name:&nbsp;<input type="text" name="lName" maxlength="30" placeholder="ex. Brown" />       <span class="errorMSG"><?= $lError; ?></span><br/>
                    CBU id:&nbsp;<input type="text" name="CBUid" maxlength="6" placeholder="ex. 012345" />          <span class="errorMSG"><?= $idError; ?></span><br/>
                    Username:&nbsp;<input type="text" name="username" maxlength="10" placeholder="ex. HannahB" />   <span class="errorMSG"><?= $uError; ?></span><br/>
                    Password:&nbsp;<input type="password" name="password" maxlength="15" />                     <span class="errorMSG"><?= $pError; ?></span><br/><br/>
                    <input type="submit" name="regButton" value="Sign me up!" />
                </div>
            </form>
        </body>
    </html>