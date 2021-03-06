<?php 

namespace SocialCurator\Entities\Site\Facebook\Feed;

use SocialCurator\Config\SettingsRepository;

/**
* Formats the Facebook Feed into an importable array
*/
class FeedFormatter 
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
		$this->unformatted_feed = $unformatted_feed->data;
		
		foreach($this->unformatted_feed as $key => $item){
			$this->formatted_feed[$key]['type'] = 'fbpost';
			$this->formatted_feed[$key]['id'] = $this->postID($item->id);
			$this->formatted_feed[$key]['date'] = date('U', strtotime($item->created_time));
			$this->formatted_feed[$key]['content'] = $this->formatContent($item);
			$this->formatted_feed[$key]['user_id'] = strval($item->from->id);
			$this->formatted_feed[$key]['screen_name'] = $item->from->name;
			$this->formatted_feed[$key]['profile_image'] = $this->getImageUrl($item->from->id);
			$this->formatted_feed[$key]['image'] = ( $item->type == 'photo' ) ? $this->getImageUrl($item->object_id) : null;
			$this->formatted_feed[$key]['video_url'] = null;
			$this->formatted_feed[$key]['link'] = ( isset($item->link) ) ? $item->link : 'http://facebook.com/profile.php?id=' . $item->from->id;
			$this->formatted_feed[$key]['profile_url'] = 'http://facebook.com/profile.php?id=' . $item->from->id;
		}
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
	* Get the Post ID
	* Removes the user id from the id
	*/
	private function postID($id)
	{
		return str_replace($this->settings_repo->getSiteSetting('facebook', 'page_id') . '_', '', $id);
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