<?php

namespace App\Tweet;


class Tweet{

    private static $twitterApiClient = null;

    public function __construct($client) {
        $this->$twitterApiClient = $client;
    }

    public static function getTweets(string $username): array {
        //only thing i can do for now (free plan)
        $myTweets = getTwitterClient()->userMeLookup()->performRequest();
        //if paid access(basic)
        /*
        $userID = getUserID($username);
        $usersTweets = getTwitterClient()->timeline()->getReverseChronological()->performRequest();
        */
        return $myTweets;
    }
    private static function getUserID(string $username) :string {
        $userQueryByName = getTwitterClient()->userLookup()
        ->findByIdOrUsername($username, \Noweh\TwitterApi\UserLookup::MODES['USERNAME'])
        ->performRequest();
           
        return $userQueryByName->data->id;
    }
    public static function saveTweets(array $data) : bool {
        
    } 

    public static function getSavedTweets(): array {
        return $database->selectAll();
    }
}