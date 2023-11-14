<?php

require "src/bootstrap.php";

use App\Tweet;


// Get the username from the URL parameter
$url = $_GET['url'];



try {
   //get username from url
   $username = getTwitterUsername($url);
   $tweets = Tweet::getTweets($username);

} catch (\Exception $e) {
   //Instead of catching the exception here we redirect the same to our main error handler
   abort($e->getMessage(), $e->getCode());
}


echo "Viewing {$username}'s tweets as seen from X";

echo json_encode($tweets);

echo "saving {$username}'s tweets to the db and downloading associated media";

for ($i=0; $i < count($tweets) ; $i++) { 
   echo "saving {$i} of " . count($tweets);
   Tweet::saveTweet($tweets[$i]);
}

echo "Done saving all of {$username}'s " . count($tweets) . " tweets";