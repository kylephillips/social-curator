<?php 

namespace SocialCurator\Entities\Site\Facebook\Feed;

use SocialCurator\Feed\FeedBase;
use \GuzzleHttp\Client;
use \GuzzleHttp\Subscriber\Oauth\Oauth1;

/**
* Fetch the API Feed
*/
class FetchFeed extends FeedBase 
{
	public function __construct()
	{
		parent::__construct();
		$this->setCredentials();
		$this->queryFeed();
	}

	/**
	* Set the API Credentials
	*/
	private function setCredentials()
	{
		$this->credentials['app_token'] = $this->settings_repo->getSiteSetting('facebook', 'app_token');
		$this->credentials['page_id'] = $this->settings_repo->getSiteSetting('facebook', 'page_id');
	}

	/**
	* Get the Page Feed
	*/
	private function queryFeed()
	{
		$api_endpoint = $this->supported_sites->getKey('facebook', 'api_endpoint');		
		$client = new Client(['base_url' => $api_endpoint]);
		try {
			$response = $client->get($this->credentials['page_id'] . '/feed', [
				'query' => [
					'access_token' => $this->credentials['app_token']
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