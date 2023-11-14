<?php

require "src/bootstrap.php";


// Get the username from the URL parameter
$url = $_GET['url'];

//get username from url
$username = getTwitterUsername($url);

$tweets = Tweet::getTweets();

echo "Viewing {$username}'s tweets as seen from X";

echo json_encode($tweets);

echo "saving {$username}'s tweets to the db and downloading associated media";

for ($i=0; $i < count($tweets) ; $i++) { 
   echo "saving {$i} of " . count($tweets);
   Tweet::saveTweet($tweets[$i]);
}

echo "Done saving all of {$username}'s " . count($tweets) . " tweets";