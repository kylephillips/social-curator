<?php namespace SocialCurator\Entities\Site\Flickr\Feed;

use SocialCurator\Feed\FeedBase;
use \GuzzleHttp\Client;

/**
* Fetch the API Feed
*/
class FetchFeedSearch extends FeedBase {

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
		$this->credentials['api_key'] = $this->settings_repo->getSiteSetting('flickr', 'api_key');
	}

	/**
	* Set the Search Term
	*/
	private function setSearchTerm()
	{
		$term = $this->settings_repo->getSiteSetting('flickr', 'search_term');
		$this->search_term =  ( $term ) ? $term : null;
	}

	/**
	* Connect to the search API
	*/
	private function search()
	{
		if ( !$this->search_term ) return; // Abort if a search term isn't provided
		$api_endpoint = $this->supported_sites->getKey('flickr', 'api_endpoint');
		$client = new Client(['base_url' => $api_endpoint]);
		try {
			$response = $client->get('services/rest/', [
				'query' => [
					'method' => 'flickr.photos.search',
					'api_key' => $this->credentials['api_key'],
					'text' => $this->search_term,
					'format' => 'json',
					'nojsoncallback' => 1,
					'extras' => 'description, date_upload, owner_name, icon_server, o_dims, views, media, path_alias, url_sq, url_t, url_s, url_q, url_m, url_n, url_z, url_c, url_l, url_o'
				]
			]);
			$feed = json_decode($response->getBody());
			$this->feed = $feed->photos->photo;
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