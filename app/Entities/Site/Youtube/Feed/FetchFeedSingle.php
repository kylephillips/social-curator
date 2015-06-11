<?php 

namespace SocialCurator\Entities\Site\Youtube\Feed;

use SocialCurator\Feed\FeedBase;
use \GuzzleHttp\Client;
use \GuzzleHttp\Subscriber\Oauth\Oauth1;

/**
* Fetch the Search API Feed
*/
class FetchFeedSingle extends FeedBase 
{

	/**
	* Video ID to Get
	*/
	private $id;

	public function __construct($id)
	{
		parent::__construct();
		$this->setCredentials();
		$this->id = $id;
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
	* Connect to the search API
	*/
	private function search()
	{
		if ( !$this->id ) return; // Abort if a search term isn't provided
		$api_endpoint = $this->supported_sites->getKey('youtube', 'api_endpoint');
		$client = new Client(['base_url' => $api_endpoint]);
		try {
			$response = $client->get('videos', [
				'query' => [
					'part' => 'id,snippet',
					'id' => $this->id,
					'maxResults' => 1,
					'key' => $this->credentials['api_key']
				]
			]);
			$feed = json_decode($response->getBody());
			if ( !isset($feed->items[0]) ){
				throw new \Exception(__('Youtube video not found.', 'socialcurator'));
			}
			$this->feed = $feed->items[0];
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