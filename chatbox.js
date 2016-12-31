/* global $ */
/* Chatroom design taken from Gabriel Nava's tutorial:
https://code.tutsplus.com/tutorials/how-to-create-a-simple-web-based-chat-application--net-5931 */

 // jQuery Document
    $(document).ready(function(){
    	//If user wants to end session
    	$("#exit").click(function(){
    		var exit = confirm("Are you sure you want to end the session?");
    		if(exit) {
    		    window.location = 'chat.php?logout=true';
    		}		
    	});
    	loadLog();
    });

    //If user submits the form
	$("#submitmsg").click(function(){
	    var clientmsg = $("#usermsg").val();
		$.post("post.php", {text: clientmsg});				
		$("#usermsg").attr("value", "");
		loadLog();
		return false;
	});
	
	//Load the file containing the chat log
	function loadLog(){		
		var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height before the request
		$.ajax({
			url: "chatlog.html",
			cache: false,
			success: function(html){		
				$("#chatbox").html(html); //Insert chat log into the #chatbox div	
				
				//Auto-scroll			
				var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height after the request
				if(newscrollHeight > oldscrollHeight){
					$("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
				}				
		  	},
		});
	}
	
	setInterval(loadLog(), 2500);
	