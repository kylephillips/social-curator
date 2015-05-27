<?php 
/**
* Static Wrapper for Bootstrap Class
* Prevents T_STRING error when checking for 5.3.2
*/
class SocialCurator {

	public static function init()
	{

		global $social_curator_version;
		$social_curator_version = '0.0.1';

		$app = new SocialCurator\Bootstrap;
	}
}