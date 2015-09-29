<?php 

namespace SocialCurator\Entities\Site\Twitter\Feed;

use SocialCurator\Feed\FeedBase;
use \GuzzleHttp\Client;
use \GuzzleHttp\Subscriber\Oauth\Oauth1;
use SocialCurator\Entities\Site\Twitter\Exceptions\NoUserException;

/**
* Fetch the User Timeline Feed
*/
class FetchFeedUser extends FeedBase 
{

	/**
	* Search Term for Querying API
	*/
	private $user_term;

	public function __construct()
	{
		parent::__construct();
		$this->setCredentials();
		$this->setUserTerm();
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
	private function setUserTerm()
	{
		$term = $this->settings_repo->getSiteSetting('twitter', 'user_term');
		$this->user_term =  ( $term ) ? $term : null;
	}

	/**
	* Connect to the search API
	*/
	private function search()
	{
		if ( !$this->user_term ) {
			throw new NoUserException(__('No user term entered.', 'SocialCurator'));
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
			$response = $client->get('statuses/user_timeline.json', [
				'auth' => 'oauth',
				'query' => [
					'screen_name' => $this->user_term
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