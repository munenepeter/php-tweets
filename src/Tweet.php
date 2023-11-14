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

    public static function getTweets() : array {

       $apiTweets = self::getRawTweets();

       array_map();

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
            'description' => $tweet['description']
            'date' => $tweet['date']
            'media_path' => $tweet['media_path']
            'media_type' => $tweet['media_type']
        ]);
    } 

    public static function getSavedTweets(): array {
        return $database->selectAll();
    }
}