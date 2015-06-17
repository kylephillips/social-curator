<?php 

namespace SocialCurator\Entities\Site\Instagram\Feed;

use SocialCurator\Feed\FeedBase;
use \GuzzleHttp\Client;

/**
* Fetch a Single Instagram Post from the API
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
		$this->credentials['client_id'] = $this->settings_repo->getSiteSetting('instagram', 'client_id');
		$this->credentials['client_secret'] = $this->settings_repo->getSiteSetting('instagram', 'client_secret');
		$this->credentials['auth_token'] = $this->settings_repo->getSiteSetting('instagram', 'auth_token');
	}

	/**
	* Connect to the search API
	*/
	private function search()
	{
		if ( !$this->id ) {
			throw new \Exception(__('An ID is required.', 'SocialCurator'));
		}
		$api_endpoint = $this->supported_sites->getKey('instagram', 'api_endpoint');
		$client = new Client(['base_url' => $api_endpoint]);
		try {
			$response = $client->get('media/shortcode/' . $this->id, [
				'query' => [
					'access_token' => $this->credentials['auth_token']
				]
			]);
			$feed = json_decode($response->getBody());
			$this->feed = $feed->data;
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