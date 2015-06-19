<?php 

namespace SocialCurator\Entities\Site\Instagram\Feed;

/**
* Formats a single Instagram Feed post into an importable array
*/
class FeedFormatterSingle 
{
	/**
	* The Formatted Feed
	* @var array
	*/
	private $formatted_feed;

	/**
	* Format the Feed into an array for import
	*/
	public function format($item)
	{
		if ( !$item ) return;
		$this->formatted_feed['type'] = $item->type;
		$this->formatted_feed['id'] = $item->id;
		$this->formatted_feed['date'] = date('U', intval($item->created_time));
		$this->formatted_feed['content'] = $item->caption->text;
		$this->formatted_feed['user_id'] = $item->user->id;
		$this->formatted_feed['screen_name'] = $item->user->username;
		$this->formatted_feed['profile_image'] = $item->user->profile_picture;
		$this->formatted_feed['image'] = ( isset($item->images->standard_resolution->url) ) ? $item->images->standard_resolution->url : null;
		$this->formatted_feed['video_url'] = ( isset($item->videos->standard_resolution) ) ? $item->videos->standard_resolution->url : null;
		$this->formatted_feed['link'] = $item->link;
		$this->formatted_feed['profile_url'] = 'https://instagram.com/' . $item->user->username;
		
		return $this->formatted_feed;
	}

}