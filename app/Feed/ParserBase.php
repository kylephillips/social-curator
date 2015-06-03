<?php namespace SocialCurator\Feed;

class ParserBase {

	protected function parseLinks($text)
	{
		$text = preg_replace(
			'@(https?://([-\w\.]+)+(/([\w/_\.]*(\?\S+)?(#\S+)?)?)?)@',
			'<a href="$1">$1</a>',
		$text);
		return $text;
	}

}