<?php namespace SocialCurator\Entities\Site\Twitter\Feed;

/**
* Formats the Twitter Feed into an importable array
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
	* Format the Feed into an array for import
	*/
	public function format($unformatted_feed)
	{
		$this->unformatted_feed = $unformatted_feed;
		foreach($this->unformatted_feed as $key => $item){
			$this->formatted_feed[$key]['type'] = 'tweet';
			$this->formatted_feed[$key]['id'] = strval($item->id);
			$this->formatted_feed[$key]['date'] = $item->created_at;
			$this->formatted_feed[$key]['content'] = $item->text;
			$this->formatted_feed[$key]['user_id'] = strval($item->user->id);
			$this->formatted_feed[$key]['screen_name'] = $item->user->screen_name;
			$this->formatted_feed[$key]['profile_image'] = $item->user->profile_image_url;
			$this->formatted_feed[$key]['image'] = ( isset($item->entities->media[0]->media_url) ) ? $item->entities->media[0]->media_url : null;
			$this->formatted_feed[$key]['video_url'] = null;
			$this->formatted_feed[$key]['link'] = 'https://twitter.com/' . $item->user->screen_name . '/status/' . $item->id;
		}
		return $this->formatted_feed;
	}

}