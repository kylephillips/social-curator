<?php 

namespace SocialCurator\Entities\Site\Flickr\Feed;

use SocialCurator\Feed\FeedBase;
use \GuzzleHttp\Client;

/**
* Fetch a Single Photo from the API
*/
class FetchFeedSingle extends FeedBase 
{

	/**
	* Search Term for Querying API
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
		$this->credentials['api_key'] = $this->settings_repo->getSiteSetting('flickr', 'api_key');
	}

	/**
	* Connect to the search API
	*/
	private function search()
	{
		if ( !$this->id ) return; // Abort if a search term isn't provided
		$api_endpoint = $this->supported_sites->getKey('flickr', 'api_endpoint');
		$client = new Client(['base_url' => $api_endpoint]);
		try {
			$response = $client->get('services/rest/', [
				'query' => [
					'method' => 'flickr.photos.getInfo',
					'api_key' => $this->credentials['api_key'],
					'photo_id' => $this->id,
					'format' => 'json',
					'nojsoncallback' => 1,
					'extras' => 'description, date_upload, owner_name, icon_server, o_dims, views, media, path_alias, url_sq, url_t, url_s, url_q, url_m, url_n, url_z, url_c, url_l, url_o'
				]
			]);
			$feed = json_decode($response->getBody());
			if ( !isset($feed->photo) ){
				throw new \Exception(__('Flickr Photo not found.', 'socialcurator'));
			}
			$this->feed = $feed->photo;
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