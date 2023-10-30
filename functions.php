<?php
use Abraham\TwitterOAuth\TwitterOAuth;

function getDatabase($host,$dbname,$username,$password): \PDO {
    try {
        // $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // return $pdo;

        return new \PDO("sqlite:db.sqlite");
    } catch (PDOException $e) {
        die("Database: connection failed: " . $e->getMessage());
    }
}

function getTwitterUsername($url) : string {
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


function getUserTweets($username) : array {
    // Set up Twitter API OAuth 1.0a authentication
    // You need to use your Twitter Developer credentials here

    // Create an OAuth 1.0a client and make the API request
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
   
    $tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$username."&count=30");


   
    

    print_r($tweets);



    // Check for errors in the API response
    if (!empty($connection->getLastHttpCode()) && $connection->getLastHttpCode() !== 200) {
        http_response_code($connection->getLastHttpCode());
        echo "Tweets: Could not get tweets at this time, E" . $connection->getLastHttpCode();
        return [];
    }
    http_response_code(200);
    return $tweets;
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

function saveTweetMedia(string $path, string $mediaUrl) {
    if ($mediaUrl === "") {
        return;
    }
    
    if(file_put_contents($path, file_get_contents($mediaUrl)) === false){
        echo "Media: Could not save media";
        return;
    }
   
    return $mediaPath;
}