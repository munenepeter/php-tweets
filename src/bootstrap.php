<?php

use App\Tweet;
use App\Core\Config;
use App\Database\DB;
use App\Database\Connection;

require "vendor/autoload.php";
require "functions.php";

try {
    $config = Config::load();

    //change TimeZone
    date_default_timezone_set($config['app']['timezone']); 

    //set up the database connection
    $database = DB::getInstance(Connection::make($config['db']));

    $twitterClient = Tweet::getInstance(Noweh\TwitterApi\Client::class, $config['x']);

} catch (\Exception $e) {
    //Instead of catching the exception here we redirect the same to our main error handler
    abort($e->getMessage(), $e->getCode());
}