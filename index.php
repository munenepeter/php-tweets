<?php

require "vendor/autoload.php";
include_once "config.php";
require "functions.php";

// Get the username from the URL parameter
$url = $_GET['url'];

//get username from url
$username = getTwitterUsername($url);


$tweets = getUserTweets($username);

/*
SET UP DB CONNECTION
*/
$dbConnection = getDatabase(DB_HOST,DB_NAME,DB_USERNAME,DB_PASSWORD);

foreach ($tweets as $tweet) {
  
    $description = $tweet['text'];
    $postDate = $tweet['created_at'];

    if (isset($tweet['entities']['media'])) {
        $media = $tweet['entities']['media'][0];
        $mediaUrl = $media['media_url'];
        $mediaType = $media['type'];

        // Download the media file and store it on disk
        $mediaPath = saveTweetMedia($mediaUrl);
        //save tweet
        saveTweets($dbConnection, $username, $description, $postDate, $mediaPath, $mediaType);
       
    }
}

