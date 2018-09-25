<?php

require_once( __DIR__ . '/Utils/Autoloader.php' );

use SwitchTv\Challenges\MovieRecommendations\MovieRecommender;

echo MovieRecommender::getRecommendations( $argv );
echo "\n";
