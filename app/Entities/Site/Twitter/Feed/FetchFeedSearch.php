<?php 

namespace SocialCurator\Entities\Site\Twitter\Feed;

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
		$this->setCredentials();
		$this->setSearchTerm();
		$this->search();
	}

	/**
	* Set the API Credentials
	*/
	private function setCredentials()
	{
		$this->credentials['api_key'] = $this->settings_repo->getSiteSetting('twitter', 'api_key');
		$this->credentials['api_secret'] = $this->settings_repo->getSiteSetting('twitter', 'api_secret');
		$this->credentials['access_token'] = $this->settings_repo->getSiteSetting('twitter', 'access_token');
		$this->credentials['access_token_secret'] = $this->settings_repo->getSiteSetting('twitter', 'access_token_secret');
	}

	/**
	* Set the Search Term
	*/
	private function setSearchTerm()
	{
		$term = $this->settings_repo->getSiteSetting('twitter', 'search_term');
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
		$api_endpoint = $this->supported_sites->getKey('twitter', 'api_endpoint');
		$client = new Client(['base_url' => $api_endpoint]);
		$oauth = new Oauth1([
			'consumer_key'		=> $this->credentials['api_key'],
			'consumer_secret'	=> $this->credentials['api_secret'],
			'token'				=> $this->credentials['access_token'],
			'token_secret'		=> $this->credentials['access_token_secret']
		]);
		$client->getEmitter()->attach($oauth);
		try {
			$response = $client->get('search/tweets.json', [
				'auth' => 'oauth',
				'query' => [
					'q' => $this->search_term
				]
			]);
			$feed = json_decode($response->getBody());
			$this->feed = $feed->statuses;
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