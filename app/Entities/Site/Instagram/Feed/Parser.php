<?php namespace SocialCurator\Entities\Site\Instagram\Feed;

use SocialCurator\Feed\ParserInterface;
use SocialCurator\Feed\ParserBase;

class Parser extends ParserBase implements ParserInterface {

	public function parse($content)
	{
		$content = $this->parseLinks($content);
		return $content;
	}

}