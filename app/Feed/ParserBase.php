<?php 
namespace SocialCurator\Feed;

class ParserBase 
{

	protected function parseLinks($text)
	{
		$text = preg_replace(
			'@(https?://(?![^" ]*(?:jpg|png|gif))[^" ]+)@',
			'<a href="$1">$1</a>',
		$text);
		return str_replace('</p>', '', $text);
	}

}