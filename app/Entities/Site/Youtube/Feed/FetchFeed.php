<?php namespace SocialCurator\Entities\Site\Youtube\Feed;

use SocialCurator\Feed\FeedBase;
use \GuzzleHttp\Client;
use \GuzzleHttp\Subscriber\Oauth\Oauth1;

/**
* Fetch the API Feed
*/
class FetchFeed extends FeedBase {

	/**
	* Search Term for Querying API
	*/
	private $search_term;

	public function __construct()
	{
		parent::__construct();
		$this->setCredentials();
		$this->setSearchTerm();
		$this->search();
	}

	/**
	* Set the API Credentials
	*/
	private function setCredentials()
	{
		$this->credentials['api_key'] = $this->settings_repo->getSiteSetting('youtube', 'api_key');
	}

	/**
	* Set the Search Term
	*/
	private function setSearchTerm()
	{
		$term = $this->settings_repo->getSiteSetting('youtube', 'search_term');
		$this->search_term =  ( $term ) ? $term : null;
	}

	/**
	* Connect to the search API
	*/
	private function search()
	{
		if ( !$this->search_term ) return; // Abort if a search term isn't provided
		$api_endpoint = $this->supported_sites->getKey('youtube', 'api_endpoint');
		$client = new Client(['base_url' => $api_endpoint]);
		try {
			$response = $client->get('search', [
				'query' => [
					'part' => 'id,snippet',
					'q' => $this->search_term,
					'key' => $this->credentials['api_key']
				]
			]);
			$feed = json_decode($response->getBody());
			$this->feed = $feed->items;
		} catch (\Exception $e){
			throw new \Exception($e->getMessage());
		}
	}

	/**
	* Get the Feed
	*/
	public function getFeed()
	{
		return $this->feed;
	}

}