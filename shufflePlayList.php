<?php

$playlistFile = file_get_contents("playlist.txt"); // gets the playlist.txt
$playlistMaster = unserialize($playlistFile); // unserializes back into an array

$artistHoldingCell = array (); // creates an array where the artists will be stored to not be called upon twice in a row
$playlistShuffled = array (); // the main output array that will be sent to youtube
$playlistMaster = array_values($playlistMaster); // changes from assosciatvie to indexed

$count = count($playlistMaster); // the count of the amount of artists
$countSongs = count($playlistMaster, COUNT_RECURSIVE); // the count of the amount of songs
$artistTime = 0; // a counter for the artists holding time

	for ($i=0; $i <$countSongs; $i++) { // for every song in the playlist

		if (empty($playlistMaster)) { // if the playlist is empty, do nothing
			break;
		}

		elseif ($artistTime % 3 == 0) {
			foreach ($artistHoldingCell as $artistArray) {
				array_push($playlistMaster, $artistArray);
				unset($artistArray);
			}

			$playlistMaster = array_values($playlistMaster); // resets the main array index
			$artistHoldingCell = array(); // maybe need to create the array again

		}
		//if theres still songs in the playlist
			$playlistMaster = array_values($playlistMaster);

			$randomArtist = array_rand($playlistMaster); // chooses a random key(artist) from the array
			$randomSong = rand(0, count($playlistMaster[$randomArtist])-1); // chooses a random song from that artist

			if($randomSong == -1) {
				$randomSong++;
			}

			array_push($playlistShuffled, $playlistMaster[$randomArtist][$randomSong]); // pushes that element to the shuffled array
			unset($playlistMaster[$randomArtist][$randomSong]); // deletes that song from the master array

				if(!empty($playlistMaster[$randomArtist])) { // if there are more songs from that artist
					$playlistMaster[$randomArtist] = array_values($playlistMaster[$randomArtist]); // resets that artists index	
					array_push($artistHoldingCell, $playlistMaster[$randomArtist]); // pushes the artist to the holding cell					
				}

			unset($playlistMaster[$randomArtist]); // deletes the artist from the main playlist
			$playlistMaster = array_values($playlistMaster); // resets the playlistMaster index

			$artistTime++;

	}; 

    $playlistFile = serialize($playlistShuffled);
    file_put_contents("playlistShuffled.txt", $playlistFile);

    header('Location: index.php');  
?>