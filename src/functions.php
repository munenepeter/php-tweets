<?php


function getTwitterUsername(string $url) : string {
    $urlParts = parse_url($url);

    // Check if the URL is not from Twitter or doesn't contain a path
    if (!isset($urlParts['host']) || $urlParts['host'] != 'twitter.com' || !isset($urlParts['path'])) {
        throw new \Exception("URL: Invalid Twitter URL", 500);
    }

    $pathSegments = explode('/', trim($urlParts['path'], '/'));

    // Check if there's no username in the path
    if (!isset($pathSegments[0])) {
        throw new \Exception("URL: Invalid Twitter URL - No username found", 500);
    }

    // Extract and return the username
    return $pathSegments[0];
}

function saveTweetMedia(string $path, string $mediaUrl = "") {
    if ($mediaUrl === "") {
        return false;
    }
    
    if(file_put_contents($path, file_get_contents($mediaUrl)) === false){   
        throw new \Exception("Media: Could not save media", 500);
    }
   
    return $mediaPath;
}

function abort($message, $code) {
    if ($code === 0 || is_string($code) || $code === "") {
        $code = 500;
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(500);
    }
    echo json_encode($message);
    exit;
}

