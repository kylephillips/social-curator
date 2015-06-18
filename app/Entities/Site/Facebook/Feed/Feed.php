<?php 

namespace SocialCurator\Entities\Site\Facebook\Feed;

use SocialCurator\Entities\Site\Facebook\Feed\FetchFeed;
use SocialCurator\Entities\Site\Facebook\Feed\FeedFormatter;
use SocialCurator\Entities\Site\Facebook\Feed\FetchFeedSingle;
use SocialCurator\Entities\Site\Facebook\Feed\FeedFormatterSingle;

/**
* Fetch the Proper Feed and Format it for Import
*/
class Feed 
{

	/**
	* The Unformatted Feed
	*/
	private $unformatted_feed;

	/**
	* Feed Formatter
	*/
	private $feed_formatter;

	/**
	* Formatted Feed
	* @var array
	*/
	private $formatted_feed;

	/**
	* Type of Feed to fetch
	* @var string
	*/
	private $type;

	/**
	* Term to search (ID)
	* @var string
	*/
	private $query;

	public function __construct($type = 'search', $query = false)
	{
		$this->query = $query;
		$this->$type();
	}

	/**
	* Fetch a Search Feed
	*/
	private function search()
	{
		$this->unformatted_feed = new FetchFeed;
		$this->feed_formatter = new FeedFormatter;
		$this->format();
	}

	/**
	* Fetch a Single Facebook Post
	*/
	private function single()
	{
		$this->unformatted_feed = new FetchFeedSingle($this->query);
		$this->feed_formatter = new FeedFormatterSingle;
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