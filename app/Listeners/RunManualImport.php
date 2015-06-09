<?php namespace SocialCurator\Listeners;

use SocialCurator\Import\Importer;
use SocialCurator\Config\SettingsRepository;
use SocialCurator\Config\SupportedSites;
use SocialCurator\Import\FailedImportLog;

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

	/**
	* Supported Sites
	* @var SocialCurator\Config\SupportedSites
	*/
	private $supported_sites;

	/**
	* Site to Import From
	* @var string
	*/
	private $site;

	public function __construct()
	{
		parent::__construct();
		$this->settings_repo = new SettingsRepository;
		$this->supported_sites = new SupportedSites;
		$this->importer = new Importer;
		$this->runImport();
		$this->sendSuccess();
	}

	/**
	* Run the Importer
	*/
	private function runImport()
	{
		$this->site = ( isset($_POST['site']) ) ? sanitize_text_field($_POST['site']) : 'all';
		try {
			$this->importer->doImport($this->site);
			$ids = $this->importer->getIDs();
			$this->return_data['post_ids'] = ( isset( $ids ) ) ? $ids : array();
			$this->return_data['import_count'] = isset( $ids ) ? count($ids) : 0;
			$this->return_data['import_date'] = $this->settings_repo->lastImport('M jS') . ' at ' . $this->settings_repo->lastImport('g:ia');
		} catch ( \Exception $e ){
			new FailedImportLog($e->getMessage());
			$this->sendError($e->getMessage());
		}
	}

	/**
	* Send Success Data
	*/
	protected function sendSuccess($message = null)
	{
		$site_name = ' ' . __('from', 'socialcurator') . ' ';
		$site_name .= ( $this->site !== 'all' ) ? $this->supported_sites->getKey($this->site, 'name') : __('All Sites', 'socialcurator');
		return wp_send_json(array(
			'status' => 'success', 
			'posts' => $this->return_data['post_ids'],
			'import_count' => $this->return_data['import_count'],
			'import_date' => $this->return_data['import_date'],
			'site' => $site_name
		));
	}

}