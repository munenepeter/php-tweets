<?php
use Noweh\TwitterApi\Client;

$settings = [];
$settings['account_id'] = ACCOUNT_ID;
$settings['access_token'] = ACCESS_TOKEN;
$settings['access_token_secret'] = ACCESS_TOKEN_SECRET;
$settings['consumer_key'] = CONSUMER_KEY;
$settings['consumer_secret'] = CONSUMER_SECRET;
$settings['bearer_token'] = BEARER_TOKEN;




function getTwitterUsername(string $url) : string {
    $urlParts = parse_url($url);

    // Check if the URL is not from Twitter or doesn't contain a path
    if (!isset($urlParts['host']) || $urlParts['host'] != 'twitter.com' || !isset($urlParts['path'])) {
        echo "URL: Invalid Twitter URL";
        return '';
    }

    $pathSegments = explode('/', trim($urlParts['path'], '/'));

    // Check if there's no username in the path
    if (!isset($pathSegments[0])) {
        echo "URL: Invalid Twitter URL - No username found";
        return '';
    }

    // Extract and return the username
    return $pathSegments[0];
}



function saveTweets($dbConnection, $username, $description, $postDate, $mediaPath, $mediaType) : bool {
    $sql = "INSERT INTO twitter_posts (`url`,`username`, `description`, `date`, `media_path`, `media_type`) 
    VALUES (:url, :username, :description, :date, :mediaPath, :mediaType)";

    // Prepare and execute the SQL statement
    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':url', 'https://twitter.com/'.$username);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':postDate', $postDate);
    $stmt->bindParam(':mediaPath', $mediaPath);
    $stmt->bindParam(':mediaType', $mediaType);

   return $stmt->execute();
  
}

function saveTweetMedia(string $path, string $mediaUrl = "") {
    if ($mediaUrl === "") {
        return false;
    }
    
    if(file_put_contents($path, file_get_contents($mediaUrl)) === false){
        echo "Media: Could not save media";
        return;
    }
   
    return $mediaPath;
}