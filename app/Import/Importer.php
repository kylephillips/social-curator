<?php namespace SocialCurator\Import;

use SocialCurator\Config\SettingsRepository;
use SocialCurator\Config\SupportedSites;
use SocialCurator\Import\SingleFeedImporter;

/**
* The Primary Import Class
*/
class Importer {

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


	
	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		$this->supported_sites = new SupportedSites;
		$this->feed_importer = new SingleFeedImporter;
	}

	/**
	* Run the Import
	*/
	public function doImport()
	{
		$this->loopSiteFeeds();
	}

	/**
	* Loop through the enabled feeds
	*/
	private function loopSiteFeeds()
	{
		$enabled_sites = $this->settings_repo->getEnabledSites();
		foreach($enabled_sites as $enabled_site){
			$site = $this->supported_sites->getSite($enabled_site);
			$feed_class = 'SocialCurator\Entities\Site\\' . $site['namespace'] . '\Feed\Feed';
			if ( !class_exists($feed_class) ) continue;
			$feed = new $feed_class;
			$feed = $feed->getFeed();
			$this->feed_importer->import($enabled_site, $feed);
		}
	}

}