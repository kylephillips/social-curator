<?php 

namespace SocialCurator\Import;

use SocialCurator\Config\SettingsRepository;
use SocialCurator\Config\SupportedSites;
use SocialCurator\Import\SingleFeedImporter;

/**
* The Primary Import Class
*/
class Importer 
{

	/**
	* Settings Repository
	* @var SocialCurator\Config\SettingsRepository
	*/
	private $settings_repo;

	/**
	* Supported Sites
	* @var SocialCurator\Config\SuportedSites
	*/
	private $supported_sites;

	/**
	* Single Feed Importer
	* @var SocialCurator\Import\SingleFeedImporter
	*/
	private $feed_importer;

	/**
	* All New Post IDs
	*/
	private $post_ids = array();

	/**
	* Site to Import From
	*/
	private $site;
	
	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		$this->supported_sites = new SupportedSites;
		$this->feed_importer = new SingleFeedImporter;
	}

	/**
	* Run the Import
	*/
	public function doImport($site)
	{
		$this->site = $site;
		$this->loopSiteFeeds();
		$this->setImportData();
	}

	/**
	* Loop through the enabled feeds
	*/
	private function loopSiteFeeds()
	{
		$enabled_sites = $this->settings_repo->getEnabledSites();
		foreach($enabled_sites as $enabled_site){
			if ( $this->site !== 'all' && $enabled_site !== $this->site ) continue;
			$site = $this->supported_sites->getSite($enabled_site);
			$feed_class = 'SocialCurator\Entities\Site\\' . $site['namespace'] . '\Feed\Feed';
			if ( !class_exists($feed_class) ) continue;
			$feed = new $feed_class;
			$feed = $feed->getFeed();
			$this->feed_importer->import($enabled_site, $feed);
			if ( is_array($this->feed_importer->getIDs()) ) $this->post_ids = array_merge($this->post_ids, $this->feed_importer->getIDs());
		}
		$this->post_ids = array_unique($this->post_ids);
	}

	/**
	* Update last import date
	*/
	private function setImportData()
	{
		update_option('social_curator_last_import', time());
		update_option('social_curator_last_import_count', count($this->post_ids));
	}

	/**
	* Get Imported Post IDs
	*/
	public function getIDs()
	{
		return ( $this->post_ids ) ? $this->post_ids : array();
	}

}