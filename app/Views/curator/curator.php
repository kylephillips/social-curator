<?php
// Twitter Feed
use SocialCurator\Entities\Site\Twitter\Feed\Feed as TwitterFeed;
$feed = new TwitterFeed;
var_dump($feed->getFeed());

// Instagram Feed
use SocialCurator\Entities\Site\Instagram\Feed\Feed as InstagramFeed;

$feed = new InstagramFeed;
var_dump($feed->getFeed());

?>
Curator Page