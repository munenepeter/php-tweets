<?php

namespace App\Tweet;


class Tweet{

    private static $twitterApiClient = null;

    public function __construct($client) {
        $this->$twitterApiClient = $client;
    }

    private static function getRawTweets(string $username): array {
        //only thing i can do for now (free plan)
        $myTweets = getTwitterClient()->userMeLookup()->performRequest();
        //if paid access(basic)
        /*
        $userID = getUserID($username);
        $usersTweets = getTwitterClient()->timeline()->getReverseChronological()->performRequest();
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
        $userQueryByName = getTwitterClient()->userLookup()
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
        return $database->selectAll();
    }
}