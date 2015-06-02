<?php namespace SocialCurator\Listeners;

use SocialCurator\Import\Importer;
use SocialCurator\Config\SettingsRepository;

/**
* Run a Manual Import
*/
class RunManualImport extends ListenerBase {

	/**
	* Importer
	* @var SocialCurator\Import\Importer
	*/
	private $importer;

	/**
	* Return Data
	* @var array
	*/
	private $return_data;

	/**
	* Settings Repository
	* @var SocialCurator\Config\SettingsRepository
	*/
	private $settings_repo;


	public function __construct()
	{
		parent::__construct();
		$this->settings_repo = new SettingsRepository;
		$this->importer = new Importer;
		$this->runImport();
		$this->sendSuccess();
	}

	/**
	* Run the Importer
	*/
	private function runImport()
	{
		$this->importer->doImport();
		$ids = $this->importer->getIDs();
		$this->return_data['post_ids'] = ( isset( $ids ) ) ? $ids : array();
		$this->return_data['import_count'] = isset( $ids ) ? count($ids) : 0;
		$this->return_data['import_date'] = $this->settings_repo->lastImport('M jS') . ' at ' . $this->settings_repo->lastImport('g:ia');
	}

	/**
	* Send Success Data
	*/
	protected function sendSuccess($message = null)
	{
		return wp_send_json(array(
			'status' => 'success', 
			'posts' => $this->return_data['post_ids'],
			'import_count' => $this->return_data['import_count'],
			'import_date' => $this->return_data['import_date']
		));
	}

}