<?php

use App\Core\Config;

require "vendor/autoload.php";
require "functions.php";

$config = Config::load();


//change TimeZone
date_default_timezone_set($config['app'][timezone]); 

//set up the database connection
$database = new QueryBuilder(Connection::make($config['db'])));