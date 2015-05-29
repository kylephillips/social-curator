<?php namespace SocialCurator\Entities\Site\Instagram\Feed;

use SocialCurator\Feed\FeedBase;
use \GuzzleHttp\Client;

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
		$this->credentials['client_id'] = $this->settings_repo->getSiteSetting('instagram', 'client_id');
		$this->credentials['client_secret'] = $this->settings_repo->getSiteSetting('instagram', 'client_secret');
		$this->credentials['auth_token'] = $this->settings_repo->getSiteSetting('instagram', 'auth_token');
	}

	/**
	* Set the Search Term
	*/
	private function setSearchTerm()
	{
		$term = $this->settings_repo->getSiteSetting('instagram', 'search_term');
		$this->search_term =  ( $term ) ? $term : null;
	}

	/**
	* Connect to the search API
	*/
	private function search()
	{
		if ( !$this->search_term ) return; // Abort if a search term isn't provided
		$api_endpoint = $this->supported_sites->getKey('instagram', 'api_endpoint');
		$client = new Client(['base_url' => $api_endpoint]);
		try {
			$response = $client->get('tags/' . $this->search_term . '/media/recent', [
				'query' => [
					'access_token' => $this->credentials['auth_token']
				]
			]);
			$feed = json_decode($response->getBody());
			$this->feed = $feed->data;
		} catch (\Exception $e){
			return false;
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