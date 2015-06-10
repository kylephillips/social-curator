<?php namespace SocialCurator\Entities\Site\Twitter\Feed;

/**
* Formats a single Twitter Feed post into an importable array
*/
class FeedFormatterSingle {

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
		$this->formatted_feed['type'] = 'tweet';
		$this->formatted_feed['id'] = $item->id_str;
		$this->formatted_feed['date'] = date('U', strtotime($item->created_at));
		$this->formatted_feed['content'] = $item->text;
		$this->formatted_feed['user_id'] = $item->user->id_str;
		$this->formatted_feed['screen_name'] = $item->user->screen_name;
		$this->formatted_feed['profile_image'] = $item->user->profile_image_url;
		$this->formatted_feed['image'] = ( isset($item->entities->media[0]->media_url) ) ? $item->entities->media[0]->media_url : null;
		$this->formatted_feed['video_url'] = null;
		$this->formatted_feed['link'] = 'https://twitter.com/' . $item->user->screen_name . '/status/' . $item->id;
		$this->formatted_feed['profile_url'] = 'https://twitter.com/' . $item->user->screen_name;
		
		return $this->formatted_feed;
	}

}