<!-- Chatroom design taken from Gabriel Nava's tutorial:
https://code.tutsplus.com/tutorials/how-to-create-a-simple-web-based-chat-application--net-5931 -->

<?php
    session_start();
    
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    
    $fp = fopen("chatlog.html", 'a');
    // Handle logout request
    if(isset($_GET['logout'])){ 
        // Simple exit message
        fwrite($fp, "<div class='msgln'><i>User ". $_SESSION['username'] .
        " has left the chat session.</i><br></div>");
        fclose($fp);
         
        session_destroy();
        // Return to login screen
        header("Location: login.php");
        
    } else {
        // Simple chatroom entry message
        fwrite($fp, "<div class='msgln'><i>User ". $_SESSION['username'] .
        " has entered the chatroom.</i><br></div>");
        fclose($fp);
    }
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Reservation Chatroom</title>
<link type="text/css" rel="stylesheet" href="chatstyle.css" />
</head>

<?php
// Authorize access (add more later based on user reservations)
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
} else {
?>
<div id="wrapper">
    <div id="menu">
        <p class="welcome">Welcome, <b><?php echo $username ?></b></p>
        <p class="logout"><a id="exit" href="#">Logout</a></p>
        <div style="clear:both"></div>
    </div>
     
    <div id="chatbox"><?php
        if(file_exists("chatlog.html") && filesize("chatlog.html") > 0){
        $handle = fopen("chatlog.html", "r");
        $contents = fread($handle, filesize("chatlog.html"));
        fclose($handle);
         
        echo $contents;
    }
    ?></div>
     
    <form name="message" action="">
        <input name="usermsg" type="text" id="usermsg" size="63" />
        <input name="submitmsg" type="submit"  id="submitmsg" value="Send" />
    </form>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="chatbox.js"></script>
<?php
}
?>
</body>

</html>