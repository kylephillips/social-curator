<?php namespace SocialCurator\Entities\Site\Facebook\Feed;

use SocialCurator\Feed\ParserInterface;
use SocialCurator\Feed\ParserBase;

class Parser extends ParserBase implements ParserInterface {

	public function parse($content)
	{
		// $content = $this->parseHashtags($content);
		// $content = $this->parseMentions($content);
		return $content;
	}

	/**
	* Parse provided text for hashtags
	* @return string 
	*/
	private function parseHashtags($content)
	{
		//screentendo?source=feed_text&story_id=882286641808020
		$content = preg_replace(
			'/\s+#(\w+)/',
			'<a href="https://www.facebook.com/hashtag/$1?source=feed_text">#$1</a>',
		$content);
		return $content;
	}


	/**
	* Parse provided text for @mentions
	* @return string 
	*/
	private function parseMentions($content)
	{
		$content = preg_replace(
			'/@([a-zA-Z0-9_]+)/',
			'<a href="http://www.twitter.com/$1">@$1</a>',
			$content);
		return $content;
	}

}