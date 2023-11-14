<?php

use App\Core\Config;

require "vendor/autoload.php";
require "functions.php";

$config = Config::load();

$database = new QueryBuilder(Connection::make($config['db'])));