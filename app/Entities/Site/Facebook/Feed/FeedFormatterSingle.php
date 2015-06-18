<?php 

namespace SocialCurator\Entities\Site\Facebook\Feed;

use SocialCurator\Config\SettingsRepository;

/**
* Formats a Single Facebook Post
*/
class FeedFormatterSingle 
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
	* Settings Repo
	* @var array
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
		$item = $unformatted_feed;
		$this->formatted_feed['type'] = 'fbpost';
		$this->formatted_feed['id'] = $item->id;
		$this->formatted_feed['date'] = date('U', strtotime($item->created_time));
		$this->formatted_feed['content'] = $this->formatContent($item);
		$this->formatted_feed['user_id'] = strval($item->from->id);
		$this->formatted_feed['screen_name'] = $item->from->name;
		$this->formatted_feed['profile_image'] = $this->getImageUrl($item->from->id);
		$this->formatted_feed['image'] = ( isset($item->images) ) ? $item->images[0]->source : null;
		$this->formatted_feed['video_url'] = null;
		$this->formatted_feed['link'] = ( isset($item->link) ) ? $item->link : 'http://facebook.com/profile.php?id=' . $item->from->id;
		$this->formatted_feed['profile_url'] = 'http://facebook.com/profile.php?id=' . $item->from->id;
		return $this->formatted_feed;
	}

	/**
	* Format the Post Content
	*/
	private function formatContent($item)
	{
		$content = '';
		if ( isset($item->message) ) $content .= '<div class="message">' . $item->message . '</div>';
		if ( isset($item->caption) ){
			$content .= '<div class="caption">';
			if ( isset($item->name) ) $content .= '<div class="caption-name">' . $item->name . '</div>';
			if ( isset($item->description) ) $content .= '<div class="caption-description">' . $item->description . '</div>';
			if ( isset($item->link) ) {
				$content .= '<div class="caption-link"><a href="' . esc_url($item->link) . '">' . $item->caption . '</a></div>';
			}
			$content .= '</div>';
		} elseif ( isset($item->name) ){
			$content .= '<div class="name">' . $item->name . '</div>';
		}
		return $content;
	}

	/**
	* Get user avatar from the API
	*/
	private function getImageUrl($id)
	{
		$url = 'https://graph.facebook.com/' . $id . '/picture';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$a = curl_exec($ch);
		$url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		curl_close($ch);
		return $url;
	}

}