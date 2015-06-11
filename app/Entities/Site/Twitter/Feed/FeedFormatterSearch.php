<?php 

namespace SocialCurator\Entities\Site\Twitter\Feed;

use SocialCurator\Config\SettingsRepository;

/**
* Formats the Twitter Search Feed results into an importable array
*/
class FeedFormatterSearch 
{

	/**
	* The Unformatted Feed
	*/
	private $unformatted_feed;

	/**
	* The Formatted Feed
	* @var array
	*/
	private $formatted_feed;

	/**
	* Settings Repository
	* @var SocialCurator\Config\SettingsRepository
	*/
	private $settings_repo;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
	}

	/**
	* Format the Feed into an array for import
	*/
	public function format($unformatted_feed)
	{
		$this->unformatted_feed = $unformatted_feed;
		foreach($this->unformatted_feed as $key => $item){
			if ( $this->includeRetweet($item) ) continue;
			$this->formatted_feed[$key]['type'] = 'tweet';
			$this->formatted_feed[$key]['id'] = strval($item->id);
			$this->formatted_feed[$key]['date'] = date('U', strtotime($item->created_at));
			$this->formatted_feed[$key]['content'] = $item->text;
			$this->formatted_feed[$key]['user_id'] = strval($item->user->id);
			$this->formatted_feed[$key]['screen_name'] = $item->user->screen_name;
			$this->formatted_feed[$key]['profile_image'] = $item->user->profile_image_url;
			$this->formatted_feed[$key]['image'] = ( isset($item->entities->media[0]->media_url) ) ? $item->entities->media[0]->media_url : null;
			$this->formatted_feed[$key]['video_url'] = null;
			$this->formatted_feed[$key]['link'] = 'https://twitter.com/' . $item->user->screen_name . '/status/' . $item->id;
			$this->formatted_feed[$key]['profile_url'] = 'https://twitter.com/' . $item->user->screen_name;
		}
		return $this->formatted_feed;
	}

	/**
	* Import Retweet?
	* @param array $item
	*/
	private function includeRetweet($item)
	{
		return ( isset($item->retweeted_status) && !$this->settings_repo->getSiteSetting('twitter', 'include_retweets') ) ? true : false;
	}

}