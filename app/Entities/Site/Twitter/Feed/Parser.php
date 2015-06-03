<?php namespace SocialCurator\Entities\Site\Twitter\Feed;

use SocialCurator\Feed\ParserInterface;
use SocialCurator\Feed\ParserBase;

class Parser extends ParserBase implements ParserInterface {

	public function parse($content)
	{
		$content = $this->parseLinks($content);
		$content = $this->parseHashtags($content);
		return $content;
	}

	/**
	* Parse provided text for hashtags
	* @return string 
	*/
	private function parseHashtags($content)
	{
		$content = preg_replace(
			'/\s+#(\w+)/',
			' <a href="http://twitter.com/hashtag/$1?src=hash">#$1</a>',
		$content);
		return $content;
	}

}