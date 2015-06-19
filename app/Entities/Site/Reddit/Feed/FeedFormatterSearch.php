<?php 

namespace SocialCurator\Entities\Site\Reddit\Feed;

use SocialCurator\Config\SettingsRepository;

/**
* Formats the Reddit Search Feed into an importable array
*/
class FeedFormatterSearch 
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
	* Format the Feed into an array for import
	*/
	public function format($unformatted_feed)
	{
		$this->unformatted_feed = $unformatted_feed;
		foreach($this->unformatted_feed as $key => $item){
			$this->formatted_feed[$key]['type'] = 'reddit';
			$this->formatted_feed[$key]['id'] = strval($item->data->id);
			$this->formatted_feed[$key]['date'] = date('U', $item->data->created);
			$this->formatted_feed[$key]['content'] = $item->data->title . ': ' . $item->data->selftext;
			$this->formatted_feed[$key]['user_id'] = $item->data->author;
			$this->formatted_feed[$key]['screen_name'] = $item->data->author;
			$this->formatted_feed[$key]['image'] = ( isset($item->data->preview) ) ? $item->data->preview->images[0]->source->url : null;
			$this->formatted_feed[$key]['video_url'] = null;
			$this->formatted_feed[$key]['link'] = 'http://www.reddit.com' . $item->data->permalink;
			$this->formatted_feed[$key]['profile_url'] = 'http://www.reddit.com/user/' . $item->data->author;
			$this->formatted_feed[$key]['profile_image'] = ( isset($item->data->thumbnail ) ) ? $item->data->thumbnail : '';
		}
		return $this->formatted_feed;
	}

}