<?php namespace SocialCurator\Listeners;

use SocialCurator\Config\SupportedSites;
use SocialCurator\Config\SettingsRepository;
use SocialCurator\Import\SingleFeedImporter;
use SocialCurator\Import\FailedImportLog;

/**
* Run a Manual Import
*/
class RunSingleImport extends ListenerBase {

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
	* Supported Sites
	* @var SocialCurator\Config\SupportedSites
	*/
	private $supported_sites;

	/**
	* Settings Repository
	* @var SocialCurator\Config\SettingsRepository
	*/
	private $settings_repo;

	/**
	* Site to Import From
	* @var string
	*/
	private $site;

	public function __construct()
	{
		parent::__construct();
		$this->supported_sites = new SupportedSites;
		$this->settings_repo = new SettingsRepository;
		$this->importer = new SingleFeedImporter;
		$this->runImport();
		$this->sendSuccess();
	}

	/**
	* Run the Importer
	*/
	private function runImport()
	{
		$this->site = ( isset($_POST['site']) ) ? sanitize_text_field($_POST['site']) : 'all';
		$id = ( isset($_POST['id']) ) ? sanitize_text_field($_POST['id']) : '';
		$feed_class = 'SocialCurator\Entities\Site\\' . $this->supported_sites->getKey($this->site, 'namespace') . '\Feed\Feed';

		if ( !class_exists($feed_class) ) return $this->sendError(__('There was an error connecting to the feed.', 'socialcurator'));

		try {
			$feed = new $feed_class('single', $id);
			$feed_array = array($feed->getFeed());
			$this->importer->import($this->site, $feed_array);
			$ids = $this->importer->getIDs();
			$this->return_data['post_ids'] = ( isset( $ids ) ) ? $ids : array();
			$this->return_data['import_count'] = isset( $ids ) ? count($ids) : 0;
			$this->return_data['import_date'] = $this->settings_repo->lastImport('M jS') . ' at ' . $this->settings_repo->lastImport('g:ia');
		} catch ( \Exception $e ){
			new FailedImportLog($e->getMessage());
			$this->sendError($e->getMessage());
		}
	}

//608775602179440640

//608777103320993792	
// $feed = new \SocialCurator\Entities\Site\Twitter\Feed\Feed('single', '608756287959130112');
// $importer = new \SocialCurator\Import\SingleFeedImporter;
// $feed_array = array($feed->getFeed());
// $importer->import('twitter', $feed_array);

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