<?php


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