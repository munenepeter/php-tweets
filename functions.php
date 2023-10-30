<?php

function getDatabase($host,$dbname,$username,$password): \PDO {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

function getTwitterUsername($url) : string|void {
    $urlParts = parse_url($url);

    // Check if the URL is not from Twitter or doesn't contain a path
    if (!isset($urlParts['host']) || $urlParts['host'] != 'twitter.com' || !isset($urlParts['path'])) {
        echo "Invalid Twitter URL";
        return;
    }

    $pathSegments = explode('/', trim($urlParts['path'], '/'));

    // Check if there's no username in the path
    if (!isset($pathSegments[1])) {
        echo "Invalid Twitter URL - No username found";
        return;
    }

    // Extract and return the username
    $username = $pathSegments[1];
    return $username;
}


function getUserTweets($username) : array {
    // Set up Twitter API OAuth 1.0a authentication
    // You need to use your Twitter Developer credentials here

    // Create an OAuth 1.0a client and make the API request
    $client = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
    $tweets = $client->get('statuses/user_timeline', ['screen_name' => $username]);

    // Check for errors in the API response
    if (!empty($client->getLastHttpCode()) && $client->getLastHttpCode() !== 200) {
        // Handle API error
        return [];
    }

    return $tweets;
}

function saveTweets($dbConnection, $username, $description, $postDate, $mediaPath, $mediaType) : bool {
    $sql = "INSERT INTO twitter_posts (username, description, post_date, media_path, media_type) 
    VALUES (:username, :description, :postDate, :mediaPath, :mediaType)";

    // Prepare and execute the SQL statement
    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':postDate', $postDate);
    $stmt->bindParam(':mediaPath', $mediaPath);
    $stmt->bindParam(':mediaType', $mediaType);

   return $stmt->execute();
  
}

function saveTweetMedia(string $mediaUrl): string {
    $mediaPath = 'media/' . basename($mediaUrl);
    file_put_contents($mediaPath, file_get_contents($mediaUrl));

    return $mediaPath;
}