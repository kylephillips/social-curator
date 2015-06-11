<?php 

namespace SocialCurator\Entities\Site\Youtube\Feed;

use SocialCurator\Entities\Site\Youtube\Feed\FetchFeedSearch;
use SocialCurator\Entities\Site\Youtube\Feed\FeedFormatterSearch;
use SocialCurator\Entities\Site\Youtube\Feed\FetchFeedSingle;
use SocialCurator\Entities\Site\Youtube\Feed\FeedFormatterSingle;

/**
* Fetch the Proper Feed and Format it for Import
*/
class Feed 
{

	/**
	* The Unformatted Feed
	* @var SocialCurator\Entities\Site\Youtube\Feed\FetchFeed
	*/
	private $unformatted_feed;

	/**
	* Feed Formatter
	* @var SocialCurator\Entities\Site\Youtube\Feed\FeedFormatter
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
		$this->unformatted_feed = new FetchFeedSearch;
		$this->feed_formatter = new FeedFormatterSearch;
		$this->format();
	}

	/**
	* Fetch a Single Instagram Post
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