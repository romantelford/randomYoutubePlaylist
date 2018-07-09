 <?php 

    if(!empty($_POST['playListLink'])) {
        $playListLink = $_POST['playListLink'];
    }

    $artists = array ();

	$api_key = INSERT_API_KEY
    $playlist_id =  $playListLink; 
    $api_url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=50&playlistId='. $playlist_id . '&key=' . $api_key;  
    $playlist = json_decode(file_get_contents($api_url));
    $totalResults = $playlist->pageInfo->totalResults;
    $pageToken = 'NOT_SET';

    // Loops through the total amount of videos in the playlist.
    for ($i=1; $i<$totalResults; $i+50) {

    	// Sets the pageToken to a default so it pulls the first 50 videos out first.
    	if ($pageToken == 'NOT_SET') {
    		$api_url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=50&playlistId='. $playlist_id . '&key=' . $api_key;       
    		$playlist = json_decode(file_get_contents($api_url));
    		// Sets the pageToken to the correct token for future iterations
    		$pageToken = $playlist->nextPageToken;
    	}

    	// When there is a pageToken gathered from the API call then use that page API call.
    	elseif (!empty($playlist->nextPageToken)) {
    		$api_url = 'https://www.googleapis.com/youtube/v3/playlistItems?pageToken='. $pageToken . '&part=snippet&maxResults=50&playlistId='. $playlist_id . '&key=' . $api_key;
    		$playlist = json_decode(file_get_contents($api_url));
    		if (!empty($playlist->nextPageToken)) {
    			$pageToken = $playlist->nextPageToken;
    		}
    	}
    	// Once there is no pageToken set as default and no NextPageToken, break
    	else  {
    		break;
    	}

        //Foreach item take the title and videoId
    	foreach($playlist->items as $item) {
    		$str = $item->snippet->title;
    		$id = $item->snippet->resourceId->videoId;

            // Substring the 'artist' name and use that as the array name
    		$substring = substr($str, 0, 5);
    		$substring = strtolower($substring);

            // Put the song in the correct array
    		$artists[$substring][]=$id;

    	}
	}

    $playlistFile = serialize($artists);
    file_put_contents("playlist.txt", $playlistFile);

    require_once('shufflePlayList.php');
?>