<?php 

namespace SocialCurator\Activation\Dependencies;

/**
* Base Dependency Class
*/
class Dependencies 
{

	/**
	* Plugin Version
	* @var string
	*/
	protected $version;

	public function __construct()
	{
		$this->setVersion();
	}

	/**
	* Set the Plugin Version
	*/
	protected function setVersion()
	{
		global $social_curator_version;
		$this->version = $social_curator_version;
	}

}