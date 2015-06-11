<?php 

namespace SocialCurator\Feed;

/**
* Contract for Parsers
*/
interface ParserInterface 
{

	public function parse($content);

}