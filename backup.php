<!doctype html>
<html lang="sv">
<head>
	<meta charset="utf-8">
	<title>Magic Mirror</title>
	<meta name="description" content="The Magic Mirror">
	<meta http-equiv="refresh" content="1800" /> <!-- Updates the whole page every 30 minutes (each 1800 second) -->
	<link rel="stylesheet" href="new_style.css">
	<link href='http://fonts.googleapis.com/css?family=Roboto:300' rel='stylesheet' type='text/css'>
	<!-- Getting the current date and time and updates them every second -->
		<script language="JavaScript"> 
			setInterval(function() { 
				var currentTime = new Date ( );
				var currentHours = currentTime.getHours ( );   
				var currentMinutes = currentTime.getMinutes ( );
				var currentMinutesleadingzero = currentMinutes > 9 ? currentMinutes : '0' + currentMinutes; // If the number is 9 or below we add a 0 before the number.
				var currentDate = currentTime.getDate ( );
	
					var weekday = new Array(7);
					weekday[0] = "Sunday";
					weekday[1] = "Monday";
					weekday[2] = "Tuesday";
					weekday[3] = "Wednesday";
					weekday[4] = "Thursday";
					weekday[5] = "Friday";
					weekday[6] = "Saturday";
				var currentDay = weekday[currentTime.getDay()]; 
	
					var actualmonth = new Array(12);
					actualmonth[0] = "January";
					actualmonth[1] = "February";
					actualmonth[2] = "March";
					actualmonth[3] = "April";
					actualmonth[4] = "May";
					actualmonth[5] = "June";
					actualmonth[6] = "July";
					actualmonth[7] = "August";
					actualmonth[8] = "September";
					actualmonth[9] = "October";
					actualmonth[10] = "November";
					actualmonth[11] = "December";
				var currentMonth = actualmonth[currentTime.getMonth ()];

    var currentTimeString = "<h1>" + currentHours + ":" + currentMinutesleadingzero + "</h1><h2>" + currentDay + " " + currentDate + " " + currentMonth + "</h2>";
    document.getElementById("clock").innerHTML = currentTimeString;
}, 1000);
	</script>
</head>
<body onLoad = "start()">
<div id="wrapper">

		<div id = "top">
			<div id="clock" style = "text-align:left"></div> <!-- Including the date/time-script -->
			<div class="weather_container" style = "text-align:right"> <!--displays weather-->
				<?php // Code for getting the RSS-weather feed
				$rss = new DOMDocument();
				$rss->load('https://rss.accuweather.com/rss/liveweather_rss.asp?locCode=11210'); // Specify the address to the feed
				$feed = array();
					foreach ($rss->getElementsByTagName('item') as $node) {
						$item = array (
						'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
						'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
						);
					array_push($feed, $item);
					}
	   
			$limit = 1; // Number of posts to be displayed
				for($x=0;$x<$limit;$x++) {
					$title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
					$description = $feed[$x]['desc'];
					echo '<h1>'.mb_substr($description,26,6).'</h1>';
				}
			?>
			<!--Location-->
				<h2> Brooklyn, NY</h2>
			</div>
		</div>
			<!--<div id = "topmost_wrapper">
				<h2 style = "margin-top: 10px; text-align:left">...</h2> 
				<h2 style = "margin-top: 10px">...</h2>
			</div>-->
		
	<div id = "content">
		<!--<iframe id = "spotify" class = "visible" src="https://open.spotify.com/embed/playlist/23e3zk8m14RuLhQbaOBYZm" width="100%" height="200px" frameBorder="0" autoplay=1 allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></iframe>-->
		<div id = "reminder" class = "standard">
				<?php
				  //This is what reads the excel file
				  include "SimpleXLSX.php";

				  echo '<div>';
				  echo '<h3 style = "text-align:center;text-decoration:underline">To Do List</h3><pre>';

				  if ( $xlsx = SimpleXLSX::parse('ReadEvents.xlsx') ) {
				    echo '<ul style=  "display:table; margin: 0 auto;">';
				    $i = 0;

				    foreach ($xlsx->rows() as $elt) {
				      if ($i == 0) {
				        
				      } else {

				        echo "<li id = \"reminder-item\" style = \"margin:10px 0\"> <span> [" . $elt[0] . "]:</span> <span>" . $elt[1] . "</span></li>";
				      }      

				      $i++;
				    }
				    echo "</ul>";
				    echo '</div>';
				  } else {
				    echo SimpleXLSX::parseError();
				  }
				?>
		</div>
		 <!--upper left start -->
		<div id = "news" class = "visible" style = "margin-top:20px">
			<?php // Code for getting the RSS-news-feed
				$rss = new DOMDocument();
				$rss->load('https://news.google.com/rss'); // Specify the address to the feed
				$feed = array();
					foreach ($rss->getElementsByTagName('item') as $node) {
						$item = array (
						'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
						'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
						'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
						);
					array_push($feed, $item);
					}
	   
			$limit = 3; // Number of posts to be displayed
				for($x=0;$x<$limit;$x++) {
					$title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
					$description = $feed[$x]['desc'];
					$date = date('j F', strtotime($feed[$x]['date']));
					echo '<h2 class="smaller">'.$title.'</h2>';
					echo '<p class="date">'.$date.'</p>';
					echo '<p>'.strip_tags($description, '<p><b>').'</p><h2>_____</h2>';
				}
			?>
		</div> <!--upper-right end-->
	</div>

	<div id="bottom" style = "transform:translateY(13vh); position:absolute">
		<!--<h2>JavaScript Speech to Text</h2>-->
        <div><img src = "microphone.png" style= "width:10px; height:20px"><span id = "result">0</span></div>
        <div id="output" class="hide" style = "size:20px"></div>

        <script type="application/javascript">
        	let speech = new SpeechSynthesisUtterance();
			speech.lang = "en";
			/*speech.voice = openfile("joey.mp3")
			function openfile(file) { window.location = file; }*/
            
            function start(){
                var r = document.getElementById("result");
            if("webkitSpeechRecognition" in window){
                var speechRecognizer = new webkitSpeechRecognition();
                speechRecognizer.continuous = true;
                speechRecognizer.interimResults = true;
                speechRecognizer.lang = "en-US";
                speechRecognizer.start();
                /*recognition.onresult = (event) => {
      				console.log('transscript: ', event.results[event.results.length -1][0].transcript);
    				}*/
                var finalTranscripts = "";
                speechRecognizer.onresult = function(event){
                    var interimTranscripts = "";
                    for(var i=event.resultIndex; i<event.results.length; i++){
                        var transcript = event.results[i][0].transcript;
                        transcript.replace("\n", "<br>");
                        
                        if(event.results[i].isFinal){
                            finalTranscripts += transcript;
                        }
                        else{
                            interimTranscripts += transcript;
                        }
                

                        news = document.getElementById("news");
                        reminder = document.getElementById("reminder")
                        interimTranscripts = interimTranscripts.toLowerCase();
                        finalTrasncripts = finalTranscripts.toLowerCase();
                        console.log("interimTrasncripts " + interimTranscripts)
                        console.log(finalTranscripts)

                        function playSound(url) {
						  const audio = new Audio(url);
						  audio.play();
						}
							

                        if(interimTranscripts == " turn off news")
                        		playSound ("swiftly-610.mp3")
                        		news.className = 'standard';
                        if(interimTranscripts == " turn on news")
                        	{
                        		news.className = 'visible';
                        		reminder.className = "standard";
                        		//document.getElementsByClassName("news").classList.remove('transition-forward');
                        	}
	                    if(interimTranscripts == " turn off schedule")
                        	reminder.className = "standard";
                        if(interimTranscripts == " turn on schedule")
                        	{
	                    		reminder.className = 'visible'
	                    		news.className = 'standard';
	                    	}
	                    if(interimTranscripts == " turn off flash")
                        	document.body.style.border = "none"
                        if(interimTranscripts == " flash")
                        	document.body.style.border = "solid white 20px"

                        wrapper = document.getElementById("wrapper") 

	                    if(interimTranscripts == " mirror")
	                    {
	                    	wrapper.className = "standard"
	                    }
	                    if(interimTranscripts == " smart mirror")
	                    {
	                    	wrapper.className = "visible"
	                    }
	              		
	                    if(finalTranscripts == " read news")
	                    {
	                    	console.log("Ticked")
	                    	speak()
	                    }

                        r.innerHTML = '<span style="color: #999;">' + interimTranscripts + '</span>';
                        finalTranscripts = ""
                    }
                };
        //failed attempt to make text-to-speech cause Chromium
                function speak()
				      {
				        let elements = document.getElementById("news").getElementsByClassName("smaller")	 
				        var text= ""
				        for(var i=0; i<elements.length; i++)
				          {
				            text = text.concat(elements[i].innerHTML + "............................................"); 
				          }
				        speech.text = text;
				        console.log(speech.text)
				        const ut = new SpeechSynthesisUtterance('No warning should arise');
				        window.speechSynthesis.speak(speech);
				        //speech.cancel();  
				      }
				      
                speechRecognizer.onerror = function(event){
                };
                speechRecognizer.onend = function (event)
                {
                	console.log("Speech recognition service disconnected");
                	start();
                };
            }
            else{
                r.innerHTML = "Your browser does not support that.";
            }
            }
        </script>
		<h3>
		<?php // Depending on the hour of the day a different message is displayed.
			$now = date('H');
				if (($now > 06) and ($now < 10)) echo 'Good morning!';
				else if (($now >= 10) and ($now < 12)) echo 'Have a nice day!';
				else if (($now >= 12) and ($now < 14)) echo 'Time for lunch!';
				else if (($now >= 14) and ($now < 17)) echo 'Come and see!';
				else if (($now >= 17) and ($now < 20)) echo 'Time to start to think about dinner?';
				else if (($now >= 20) and ($now < 22)) echo 'Have a nice evening!';
				else if (($now >= 22) and ($now < 23)) echo 'Sleep tight, see you tomorrow!';
				else if (($now >= 00) and ($now < 06)) echo 'Shh, sleeping...';
			?>
		</h3>
	</div>
	</div>
 <!-- current wrapper end-->
</body>
</html>


		
		