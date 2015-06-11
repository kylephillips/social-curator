<?php

namespace SocialCurator\Listeners;

use SocialCurator\Entities\Log\LogRepository;

class ClearLogs extends ListenerBase
{

	/**
	* Log Repository
	*/
	private $log_repo;

	public function __construct()
	{
		parent::__construct();
		$this->log_repo = new LogRepository;
		$this->clear();
		$this->sendSuccess(__('Logs successfully cleared.', 'socialcurator'));
	}

	/**
	* Clear the Logs
	*/
	private function clear()
	{
		$this->log_repo->clearLogs();
	}

}