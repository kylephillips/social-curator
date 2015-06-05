<?php namespace SocialCurator\Entities\Site\Flickr\Feed;

/**
* Formats the Flickr Feed into an importable array
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
			$this->formatted_feed[$key]['type'] = 'image';
			$this->formatted_feed[$key]['id'] = strval($item->id);
			$this->formatted_feed[$key]['date'] = strval($item->dateupload);
			$this->formatted_feed[$key]['content'] = $item->title . ': ' . $item->description->_content;
			$this->formatted_feed[$key]['user_id'] = strval($item->owner);
			$this->formatted_feed[$key]['video_url'] = null;
			$this->formatted_feed[$key]['image'] = $this->getImageURL($item);
			$this->formatted_feed[$key]['screen_name'] = $item->ownername;
			$this->formatted_feed[$key]['profile_image']= $this->getProfileImage($item->iconfarm, $item->iconserver, $item->owner);
			$this->formatted_feed[$key]['profile_url'] = 'https://flickr.com/photos/' . $item->pathalias;
			$this->formatted_feed[$key]['link'] = 'https://flickr.com/photos/' . $item->pathalias . '/' . $item->id;
		}
		return $this->formatted_feed;
	}

	/**
	* Get largest image url available
	*/
	private function getImageURL($item)
	{
		if ( property_exists($item, 'url_l') ) return $item->url_l;
		if ( property_exists($item, 'url_o') ) return $item->url_o;
		if ( property_exists($item, 'url_c') ) return $item->url_c;
		if ( property_exists($item, 'url_z') ) return $item->url_z;
		if ( property_exists($item, 'url_n') ) return $item->url_n;
		if ( property_exists($item, 'url_m') ) return $item->url_m;
		if ( property_exists($item, 'url_q') ) return $item->url_q;
		if ( property_exists($item, 'url_s') ) return $item->url_s;
		return '';
	}

	/**
	* Get profile icon
	*/
	private function getProfileImage($iconfarm, $iconserver, $user_id)
	{
		return 'http://farm' . $iconfarm . '.staticflickr.com/' . $iconserver . '/buddyicons/' . $user_id . '.jpg';
	}

}