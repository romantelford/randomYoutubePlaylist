<!DOCYTPE html>
<html>
	<head>
		<meta charset="utf-8"/>
				<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
		<link type="text/css" rel="stylesheet" href="stylesheet.css">

	</head>
	<body>

		<div id="container">
			<div id="header">
				<h1>Random Playlist</h1>
			</div>

			<div id="player"></div>

			<div id="cinemaMode"></div>
			<div id="rightPlayer"></div>

			<div id="footer">

				<?php
				if (file_exists("playlist.txt") & file_exists("playlistShuffled.txt")) {
					?> 
					<form name='shufflePlaylist' method='post' action='shufflePlaylist.php'>
					<input type="submit" name="submit" value="Shuffle Current Playlist" class="btn btn-primary" id="shuffle-current" disabled="false">
					</form> 
					<?php
				}

				else {
					?>
					<script>
						const shuffleCurrent = document.getElementById('shuffle-current');
						shuffleCurrent.disabled=true;
					</script>
					<form name='getPlaylist' method='post' action='getPlayList.php'>
					Playlist Youtube ID: <input type="text" name="playListLink" id="playListLink" placeholder="Enter Youtube playlist link" ><br />
					<input type="submit" name="submit" class="btn btn-primary" value="Shuffle Playlist">
					</form>

					<?php
				}
				?>

			</div>

			<!-- <div id="footer">Footer info</div> -->
		</div>

	<?php

	if (file_exists("playlist.txt") & file_exists("playlistShuffled.txt")) {

		$playlistFile = file_get_contents("playlistShuffled.txt"); // gets the playlist.txt
		$playlist = unserialize($playlistFile); // unserializes back into an array
	?>
	 <!-- Where the youtube player loads -->

		<script type="text/javascript">
		var playlist = JSON.parse('<?php echo JSON_encode($playlist);?>'); //puts the playlist into an object Javascript can understand

	      // 2. This code loads the IFrame Player API code asynchronously.
	      var n = 0;
	      var tag = document.createElement('script');

	      tag.src = "https://www.youtube.com/iframe_api";
	      var firstScriptTag = document.getElementsByTagName('script')[0];
	      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

	      // 3. This function creates an <iframe> (and YouTube player)
	      //    after the API code downloads.
	      var player;
	      function onYouTubeIframeAPIReady() {
	        player = new YT.Player('player', {
	          height: '390',
	          width: '640',
	          videoId: 'M7lc1UVf-VE',
	          events: {
	            'onReady': onPlayerReady,
	            'onStateChange': onPlayerStateChange,
	            'onError': next
	          }
	        });
	      }

	      // 4. The API will call this function when the video player is ready.
	      function onPlayerReady(event) {
	      	player.loadVideoById(playlist[0]);
	        event.target.playVideo();
	      }

	      function playNewVideo(id) {
	      	player.loadVideoById(playlist[id]);
	      	event.target.playVideo();
	      }

		   function onPlayerStateChange(event) {        
		        if(event.data === 0) {
		        n++; 
		          playNewVideo(n);
		        }
			}

	    	function next() {
	    		n++;
	    		playNewVideo(n);
	    	}

	    	function previous() {
	    		n--;
	    		playNewVideo(n);
	    	}

	      function stopVideo() {
	        player.stopVideo();
	      }

	      document.onkeydown = function(e) {
	      	switch (e.keyCode) {

	      		case 37:
	      		previous();
	      		break;

	      		case 39:
	      		next();
	      		break;
	      	}
	      };


	    </script>

	<?php
	}
	?>

	</body>
</html>