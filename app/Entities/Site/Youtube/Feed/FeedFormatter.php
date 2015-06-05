<?php namespace SocialCurator\Entities\Site\Youtube\Feed;

use SocialCurator\Config\SettingsRepository;
use SocialCurator\Entities\Site\Youtube\Feed\FetchChannel;

/**
* Formats the Youtube Feed into an importable array
*/
class FeedFormatter {

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
	* Channel Fetcher
	* @var array
	*/
	private $channel;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		$this->channel = new FetchChannel;
	}

	/**
	* Format the Feed into an array for import
	*/
	public function format($unformatted_feed)
	{
		$this->unformatted_feed = $unformatted_feed;
		foreach($this->unformatted_feed as $key => $item){
			$this->formatted_feed[$key]['type'] = 'video';
			$this->formatted_feed[$key]['id'] = strval($item->id->videoId);
			$this->formatted_feed[$key]['date'] = date('U', strtotime($item->snippet->publishedAt));
			$this->formatted_feed[$key]['content'] = $item->snippet->title . ': ' . $item->snippet->description;
			$this->formatted_feed[$key]['user_id'] = strval($item->snippet->channelId);
			$this->formatted_feed[$key]['screen_name'] = $item->snippet->channelTitle;
			$this->formatted_feed[$key]['image'] = ( isset($item->snippet->thumbnails->high) ) ? $item->snippet->thumbnails->high->url : null;
			$this->formatted_feed[$key]['video_url'] = 'https://www.youtube.com/watch?v=' . $item->id->videoId;
			$this->formatted_feed[$key]['link'] = 'https://www.youtube.com/watch?v=' . $item->id->videoId;
			$this->formatted_feed[$key]['profile_url'] = 'https://www.youtube.com/user/' . $item->snippet->channelTitle;
			$this->addChannelInfo($key, $item->snippet->channelId);
		}
		return $this->formatted_feed;
	}

	/**
	* Add Channel/User Info
	* @param string $channel_id
	*/
	private function addChannelInfo($key, $channel_id)
	{
		$this->channel->getChannel($channel_id);
		$item = $this->channel->getItem();
		$this->formatted_feed[$key]['profile_image'] = $item[0]->snippet->thumbnails->high->url;
	}

}