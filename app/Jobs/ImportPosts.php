<?php namespace SocialCurator\Jobs;

use SocialCurator\Import\Importer;
use SocialCurator\Import\FailedImportLog;
use SocialCurator\Notifications\FailedImportNotification;

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
			new FailedImportLog($e->getMessage());
			new FailedImportNotification($e->getMessage());
		}
	}

}