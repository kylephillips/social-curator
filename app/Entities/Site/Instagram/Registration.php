<?php namespace SocialCurator\Entities\Site\Instagram;

use SocialCurator\Entities\Site\Instagram\Events\RegisterEvents;

/**
* Register Instagram as a Supported Site
*/
class Registration {

	public function __construct()
	{
		$this->pluginInit();
	}

	/**
	* Initialize the Supported Site
	*/
	private function pluginInit()
	{
		new RegisterEvents;
	}

}