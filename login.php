<?php
    session_start();
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Let's Lunch!</title>
            <link href="style.css" type="text/css" rel="stylesheet" />
        </head>
        <body>
            <?php
            // Set up db connection
            $servername = getenv('IP');
            $username = getenv('C9_USER');
            $password = "";
            $database = "lunch";
            $dbport = 3306;
            // Create connection
            $db = new mysqli($servername, $username, $password, $database,
            $dbport);
            // Check connection)
            if ($db->connect_error) {
                die("Connection failed: " . $db->connect_error);
            }
            
            // Check login
            if (isset($_POST["loginButton"])) {
                $query = "SELECT * FROM users";
                $result = mysqli_query($db, $query);
                $validLogin = false;
                while ($row = mysqli_fetch_assoc($result)) {
                    if (($row['username']==$_POST["username"]) && $row['password']==$_POST["password"]) {
                        $validLogin = true;
                        $_SESSION["username"] = $row['username'];
                        $_SESSION["firstname"] = $row['firstname'];
                        $_SESSION["user_id"] = $row['id'];
                    }
                }
                if ($validLogin) {
                    header("Location: login_redirect.php");
                } else {
                    echo "<br/><span class=\"errorMSG\">Invalid Login Credentials</span><br/>";
                }
            }
            ?>
            <h3>Log in and</h3>
            <h2>Let's Lunch!</h2>
            <form action="login.php" method="post">
                <div>
                    <input type="text" name="username" placeholder="Username" maxlength=10 /><br/><br/>
                    <input type="password" name="password" placeholder="Password" maxlength=15 /><br/><br/>
                </div>
                    <input type="submit" name="loginButton" value="Login" />
            </form>
            <span class="regNote">Don't have an account? Click <a href="register.php">here</a> to register.</span>
        </body>
    </html>