<?php

require "src/bootstrap.php";


// Get the username from the URL parameter
$url = $_GET['url'];

//get username from url
$username = getTwitterUsername($url);
$tweets = getUserTweets($username);

