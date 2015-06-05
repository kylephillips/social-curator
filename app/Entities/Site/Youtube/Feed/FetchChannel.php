<?php namespace SocialCurator\Entities\Site\Youtube\Feed;

use SocialCurator\Feed\FeedBase;
use \GuzzleHttp\Client;

/**
* Fetch a Youtube Channel
*/
class FetchChannel extends FeedBase {

	/**
	* Channel Feed Item
	*/
	protected $feed;

	public function __construct()
	{
		parent::__construct();
		$this->setCredentials();
	}

	/**
	* Set the API Credentials
	*/
	private function setCredentials()
	{
		$this->credentials['api_key'] = $this->settings_repo->getSiteSetting('youtube', 'api_key');
	}

	/**
	* Connect to the channel API
	* @param string $channel_id
	*/
	public function getChannel($channel_id)
	{
		if ( !$channel_id ) return; // Abort if a channel id isn't provided
		$api_endpoint = $this->supported_sites->getKey('youtube', 'api_endpoint');
		$client = new Client(['base_url' => $api_endpoint]);
		try {
			$response = $client->get('channels', [
				'query' => [
					'part' => 'id,snippet',
					'id' => $channel_id,
					'key' => $this->credentials['api_key']
				]
			]);
			$feed = json_decode($response->getBody());
			$this->feed = $feed->items;
		} catch (\Exception $e){

			return false;
		}
	}

	/**
	* Get the Feed Item
	*/
	public function getItem()
	{
		return $this->feed;
	}

}