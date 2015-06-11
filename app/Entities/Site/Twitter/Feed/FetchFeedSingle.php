<?php 

namespace SocialCurator\Entities\Site\Twitter\Feed;

use SocialCurator\Feed\FeedBase;
use \GuzzleHttp\Client;
use \GuzzleHttp\Subscriber\Oauth\Oauth1;

/**
* Fetch a Single Post from the API Feed
*/
class FetchFeedSingle extends FeedBase 
{

	/**
	* The ID to fetch
	*/
	private $id;

	public function __construct($id)
	{
		parent::__construct();
		$this->id = $id;
		$this->setCredentials();
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
	* Connect to the search API
	*/
	private function search()
	{
		if ( !$this->id ) return; // Abort if an ID isn't provided
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
			$response = $client->get('statuses/show.json', [
				'auth' => 'oauth',
				'query' => [
					'id' => $this->id
				]
			]);
			$feed = json_decode($response->getBody());
			$this->feed = $feed;
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