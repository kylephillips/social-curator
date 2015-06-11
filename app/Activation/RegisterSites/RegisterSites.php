<?php 

namespace SocialCurator\Activation\RegisterSites;

use SocialCurator\Helpers;

/**
* Register all supported sites
* Bootstraps all supported sites by newing up a Registration class for each
*/
class RegisterSites 
{

	public function __construct()
	{
		$this->registerSites();
	}

	/**
	* Loop through the Site namespace/directory and new up Registration classes for each
	*/
	private function registerSites()
	{
		$directory = Helpers::plugin_root() . '/Entities/Site';
		$folders = new \DirectoryIterator($directory);
		foreach($folders as $folder)
		{
			if ( $folder->isDot() ) continue;
			$class = 'SocialCurator\Entities\Site\\' . $folder->getFilename() . '\Registration';
			if ( !class_exists($class) ) continue;
			$site = new $class;
		}
	}

}