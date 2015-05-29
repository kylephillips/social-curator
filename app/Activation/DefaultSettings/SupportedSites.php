<?php namespace SocialCurator\Activation\DefaultSettings;

/**
* Register Supported Sites
*/
class SupportedSites extends DefaultSettingsBase {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Perform Updates before setting new version
	*/
	protected function performUpdates()
	{
		$this->initialRelease();
	}

	/**
	* Initial Release's Supported Sites
	*/
	private function initialRelease()
	{
	}

}