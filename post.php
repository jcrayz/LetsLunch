<!-- Chatroom design taken from Gabriel Nava's tutorial:
https://code.tutsplus.com/tutorials/how-to-create-a-simple-web-based-chat-application--net-5931 -->

<?php
session_start();
if(isset($_SESSION['username'])){
    $text = $_POST['text'];
     
    $fp = fopen("chatlog.html", 'a');
    fwrite($fp, "<div class='msgln'>(".date("g:i A").") <b>".$_SESSION['username']."</b>: ".stripslashes(htmlspecialchars($text))."<br></div>");
    fclose($fp);
}
?>