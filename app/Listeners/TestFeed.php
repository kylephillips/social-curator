<?php

namespace SocialCurator\Listeners;

use SocialCurator\Config\SupportedSites;

/**
* Fetch a Test Feed
*/
class TestFeed extends ListenerBase
{
	/**
	* Supported Sites
	* @var SocialCurator\Config\SupportedSites
	*/
	private $supported_sites;

	/**
	* Site to fetch feed from
	* @var string
	*/
	private $site;

	/**
	* Type of Feed to Fetch
	* @var string
	*/
	private $feed_type;

	/**
	* Format of Feed (raw/formatted)
	* @var string
	*/
	private $feed_format;

	/**
	* Feed Class
	* @var object
	*/
	private $feed_class;

	/**
	* Post ID to Get
	* @var object
	*/
	private $post_id;

	/**
	* The Feed to return
	* @var array
	*/
	private $feed;

	public function __construct()
	{
		parent::__construct();
		$this->supported_sites = new SupportedSites;
		$this->setOptions();
		$this->setFeedClass();
		$this->fetchFeed();
		return $this->sendSuccess();
	}

	/**
	* Set the feed options
	*/
	private function setOptions()
	{
		$this->site = ( isset($_POST['site']) ) ? sanitize_text_field($_POST['site']) : null;
		$this->feed_type = ( isset($_POST['type']) && $_POST['type'] !== 'search' ) ? sanitize_text_field($_POST['type']) : 'search';
		$this->post_id = ( isset($_POST['id']) ) ? sanitize_text_field($_POST['id']) : null;
		$this->feed_format = ( isset($_POST['format']) && $_POST['format'] !== 'unformatted' ) ? sanitize_text_field($_POST['format']) : 'unformatted';
	}

	/**
	* Set the feed class
	*/
	private function setFeedClass()
	{
		$feed_class = $this->supported_sites->getKey($this->site, 'namespace');
		if ( $this->feed_type == 'search' ) $feed_class .= ( $this->feed_format == 'unformatted' ) ? '\Feed\FetchFeedSearch' : '\Feed\Feed';
		if ( $this->feed_type == 'single' ) $feed_class .= ( $this->feed_format == 'unformatted' ) ? '\Feed\FetchFeedSingle' : '\Feed\Feed';
		if ( !class_exists($feed_class) ) return $this->sendError(__('Feed Not Found', 'socialcurator'));
		$this->feed_class = $feed_class;
	}

	/**
	* Fetch the Feed
	*/
	private function fetchFeed()
	{
		if ( $this->feed_type == 'search' ){
			$feed = new $this->feed_class;
		} else {
			if ( !$this->post_id ) return $this->sendError(__('A post ID is required for single feeds.', 'socialcurator'));
			try {
				$feed = ( $this->feed_format == 'unformatted' ) ? new $this->feed_class($this->post_id) : new $this->feed_class('single', $this->post_id);
			} catch ( \Exception $e ){
				return $this->sendError($e->getMessage());
			}
		}

		try {
			$this->feed = $feed->getFeed();
		} catch ( \Exception $e ){
			return $this->sendError($e->getMessage());
		}
	}

	/**
	* Send Success
	*/
	protected function sendSuccess($message = null)
	{
		return wp_send_json(array(
			'status' => 'success',
			'feed' => $this->feed
		));
	}
}