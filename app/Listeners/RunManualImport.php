<?php namespace SocialCurator\Listeners;

/**
* Run a Manual Import
*/
class RunManualImport extends ListenerBase {

	public function __construct()
	{
		parent::__construct();
		$this->sendSuccess('testing');
	}

}