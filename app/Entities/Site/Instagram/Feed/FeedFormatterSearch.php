<?php 

namespace SocialCurator\Entities\Site\Instagram\Feed;

/**
* Formats the Instagram Search Feed into an importable array
*/
class FeedFormatterSearch {

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
			$this->formatted_feed[$key]['id'] = $item->id;
			$this->formatted_feed[$key]['type'] = $item->type;
			$this->formatted_feed[$key]['date'] = $item->created_time;
			$this->formatted_feed[$key]['content'] = $item->caption->text;
			$this->formatted_feed[$key]['user_id'] = $item->user->id;
			$this->formatted_feed[$key]['screen_name'] = $item->user->username;
			$this->formatted_feed[$key]['profile_image'] = $item->user->profile_picture;
			$this->formatted_feed[$key]['image'] = ( isset($item->images->standard_resolution->url) ) ? $item->images->standard_resolution->url : null;
			$this->formatted_feed[$key]['video_url'] = ( isset($item->videos->standard_resolution) ) ? $item->videos->standard_resolution->url : null;
			$this->formatted_feed[$key]['link'] = $item->link;
			$this->formatted_feed[$key]['profile_url'] = 'https://instagram.com/' . $item->user->username;
		}
		return $this->formatted_feed;
	}

}