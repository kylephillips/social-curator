<?php 

namespace SocialCurator\Entities\Site\Youtube\Feed;

use SocialCurator\Config\SettingsRepository;
use SocialCurator\Entities\Site\Youtube\Feed\FetchChannel;

/**
* Formats the Youtube Single Video Feed into an importable array
*/
class FeedFormatterSingle
{

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
	public function format($item)
	{
		$this->formatted_feed['type'] = 'video';
		$this->formatted_feed['id'] = strval($item->id);
		$this->formatted_feed['date'] = date('U', strtotime($item->snippet->publishedAt));
		$this->formatted_feed['content'] = $item->snippet->title . ': ' . $item->snippet->description;
		$this->formatted_feed['user_id'] = strval($item->snippet->channelId);
		$this->formatted_feed['screen_name'] = $item->snippet->channelTitle;
		$this->formatted_feed['image'] = ( isset($item->snippet->thumbnails->high) ) ? $item->snippet->thumbnails->high->url : null;
		$this->formatted_feed['video_url'] = 'https://www.youtube.com/watch?v=' . $item->id;
		$this->formatted_feed['link'] = 'https://www.youtube.com/watch?v=' . $item->id;
		$this->formatted_feed['profile_url'] = 'https://www.youtube.com/user/' . $item->snippet->channelTitle;
		$this->addChannelInfo($item->snippet->channelId);
		return $this->formatted_feed;
	}

	/**
	* Add Channel/User Info
	* @param string $channel_id
	*/
	private function addChannelInfo($channel_id)
	{
		$this->channel->getChannel($channel_id);
		$item = $this->channel->getItem();
		$this->formatted_feed['profile_image'] = $item[0]->snippet->thumbnails->high->url;
	}

}