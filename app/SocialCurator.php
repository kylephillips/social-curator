<?php 
/**
* Static Wrapper for Bootstrap Class
* Prevents T_STRING error when checking for 5.3.2
*/
class SocialCurator 
{

	public static function init()
	{
		global $social_curator_version;
		$social_curator_version = '1.0';

		global $social_curator_directory;
		$social_curator_directory = dirname(dirname(__FILE__));

		global $social_curator_file;
		$social_curator_file = $social_curator_directory . '/social-curator.php';

		$app = new SocialCurator\SocialCuratorBootstrap;
	}
}