<?php

namespace App\Tweet;


class Tweet{

    private $twitterApiClient = null;

    public function __construct($client) {
        $this->$twitterApiClient = $client;
    }

    public function getTweets(string $username): array {
        //only thing i can do for now (free plan)
        $myTweets = getTwitterClient()->userMeLookup()->performRequest();
        //if paid access(basic)
        /*
        $userID = getUserID($username);
        $usersTweets = getTwitterClient()->timeline()->getReverseChronological()->performRequest();
        */
        return $myTweets;
    }
    private function getUserID(string $username) :string {
        $userQueryByName = getTwitterClient()->userLookup()
        ->findByIdOrUsername($username, \Noweh\TwitterApi\UserLookup::MODES['USERNAME'])
        ->performRequest();
           
        return $userQueryByName->data->id;
    }
    public function saveTweets(array $data) : bool {
        
    } 
    private function saveTweetMedia() {
        
    }
}