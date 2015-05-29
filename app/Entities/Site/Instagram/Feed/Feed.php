<?php namespace SocialCurator\Entities\Site\Instagram\Feed;

use SocialCurator\Entities\Site\Instagram\Feed\FetchFeed;
use SocialCurator\Entities\Site\Instagram\Feed\FeedFormatter;

/**
* Formatted Feed, ready for import
*/
class Feed {

	/**
	* The Unformatted Feed
	* @var SocialCurator\Entities\Site\Twitter\Feed\FetchFeed
	*/
	private $unformatted_feed;

	/**
	* Feed Formatter
	* @var SocialCurator\Entities\Site\Twitter\Feed\FeedFormatter
	*/
	private $feed_formatter;

	/**
	* Formatted Feed
	* @var array
	*/
	private $formatted_feed;

	public function __construct()
	{
		$this->unformatted_feed = new FetchFeed;
		$this->feed_formatter = new FeedFormatter;
		$this->format();
	}

	/**
	* Format the Feed
	*/
	private function format()
	{
		$this->formatted_feed = $this->feed_formatter->format($this->unformatted_feed->getFeed());
	}

	/**
	* Get the Formatted Feed
	*/
	public function getFeed()
	{
		return $this->formatted_feed;
	}

}