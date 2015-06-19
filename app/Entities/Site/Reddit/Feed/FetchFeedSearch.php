<?php 

namespace SocialCurator\Entities\Site\Reddit\Feed;

use SocialCurator\Feed\FeedBase;
use \GuzzleHttp\Client;
use \GuzzleHttp\Subscriber\Oauth\Oauth1;

/**
* Fetch the Search API Feed
*/
class FetchFeedSearch extends FeedBase 
{

	/**
	* Search Term for Querying API
	*/
	private $search_term;

	public function __construct()
	{
		parent::__construct();
		//$this->setCredentials();
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
		$term = $this->settings_repo->getSiteSetting('reddit', 'search_term');
		$this->search_term =  ( $term ) ? $term : null;
	}

	/**
	* Connect to the search API
	*/
	private function search()
	{
		if ( !$this->search_term ) {
			throw new \Exception(__('A search term is required.', 'SocialCurator'));
		}
		$api_endpoint = $this->supported_sites->getKey('reddit', 'api_endpoint');
		$client = new Client(['base_url' => $api_endpoint]);
		try {
			$response = $client->get('search.json', [
				'query' => [
					'q' => $this->search_term
				]
			]);
			$feed = json_decode($response->getBody());
			$this->feed = $feed->data->children;
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