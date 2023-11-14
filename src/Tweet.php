<?php

namespace App\Tweet;

class Tweet {

    private static $twitterApiClient = null;s
    private static $cache_key = "tweets_cache";

    private function __construct($client, $credentials) {
        static::$twitterApiClient = new $client($credentials);
    }

    public static function getInstance($client, $credentials) {
        if (static::$twitterApiClient === null) {
            new self($client, $credentials);
        }

        return static::$twitterApiClient;
    }

    private static function getRawTweets(string $username): array {
        //only thing i can do for now (free plan)
        $myTweets = static::$twitterApiClient->userMeLookup()->performRequest();
        //if paid access(basic)
        /*
        $userID = getUserID($username);
        $usersTweets = static::$twitterApiClient->timeline()->getReverseChronological()->performRequest();
        */
        return $myTweets;
    }

    public static function getTweets(string $username) : array {

       $apiTweets = self::getRawTweets($username);

       $transformed = function ($tweet) use ($username) {

        $tweet['username'] = $username;

        if (!isset($tweet['entities']['media'])) {
            $tweet['media_path'] = false;
            $tweet['media_type'] = null;
        } 
        $media = $tweet['entities']['media'];
        $mediaUrl = $media['media_url'];

        $tweet['media_type'] = $media['type'];
        // Download the media file and store it on disk
        $path = __DIR__.'/../media/' . basename($mediaUrl);

        // Add the media path to the tweet
        $tweet['media_path'] = saveTweetMedia($path, $mediaUrl);

        return $tweet;
      };

       return array_map($transformed, $apiTweets);

    }
    private static function getUserID(string $username) :string {
        $userQueryByName = static::$twitterApiClient->userLookup()
                            ->findByIdOrUsername($username, \Noweh\TwitterApi\UserLookup::MODES['USERNAME'])
                            ->performRequest();
           
        return $userQueryByName->data->id;
    }
    public static function saveTweet(array $tweet) : bool {
        return $database->insert('twitter_posts',[
            'url' => $tweet['url'],
            'username' => $tweet['username'],
            'description' => $tweet['description'],
            'date' => $tweet['date'],
            'media_path' => $tweet['media_path'],
            'media_type' => $tweet['media_type'],
        ]);
    } 

    public static function getSavedTweets(): array {
         // Check if cached config exists
         $cachedTweets = Cache::get(self::$cache_key);
         if ($cachedTweets !== null) {
             return $cachedTweets;
         }
         // If not, load and parse the configuration file
         $savedTweets = $database->selectAll();
 
         // Cache the config for future use
         Cache::put(self::$cache_key, $savedTweets);
 
         return $savedTweets;
    }
}