<?php namespace SocialCurator\Jobs;

use SocialCurator\Import\Importer;
use SocialCurator\Config\SettingsRepository;
use SocialCurator\Config\SupportedSites;

/**
* Run an Import Job
*/
class ImportPosts {

	/**
	* Importer
	* @var SocialCurator\Import\Importer
	*/
	private $importer;

	public function __construct()
	{
		$this->importer = new Importer;
		$this->runImport();
	}

	/**
	* Run the Importer
	*/
	private function runImport()
	{
		try {
			$this->importer->doImport('all');
		} catch ( \Exception $e ){
			// TODO: Log Exception
		}
	}

}