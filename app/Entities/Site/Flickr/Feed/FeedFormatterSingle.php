<?php 

namespace SocialCurator\Entities\Site\Flickr\Feed;

/**
* Formats the Flickr Search Feed into an importable array
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
		if ( !isset($item->originalformat) ){
			throw new \Exception(__('Original image could not be found.', 'socialcurator'));
		}
		$this->formatted_feed['type'] = 'image';
		$this->formatted_feed['id'] = strval($item->id);
		$this->formatted_feed['date'] = strval($item->dateuploaded);
		$this->formatted_feed['content'] = $item->title->_content . ': ' . $item->description->_content;
		$this->formatted_feed['user_id'] = strval($item->owner->nsid);
		$this->formatted_feed['video_url'] = null;
		$this->formatted_feed['image'] = $this->getImageURL($item);
		$this->formatted_feed['screen_name'] = $item->owner->username;
		$this->formatted_feed['profile_image']= $this->getProfileImage($item->owner->iconfarm, $item->owner->iconserver, $item->owner->nsid);
		$this->formatted_feed['profile_url'] = 'https://flickr.com/photos/' . $item->owner->path_alias;
		$this->formatted_feed['link'] = 'https://flickr.com/photos/' . $item->owner->path_alias . '/' . $item->id;
		return $this->formatted_feed;
	}

	/**
	* Get largest image url available
	*/
	private function getImageURL($item)
	{
		return 'https://farm' . $item->farm . '.staticflickr.com/' . $item->server . '/' . $item->id . '_' . $item->secret . '_b.' . $item->originalformat;
	}

	/**
	* Get profile icon
	*/
	private function getProfileImage($iconfarm, $iconserver, $user_id)
	{
		return 'http://farm' . $iconfarm . '.staticflickr.com/' . $iconserver . '/buddyicons/' . $user_id . '.jpg';
	}

}