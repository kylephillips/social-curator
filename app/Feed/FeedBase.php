<?php namespace SocialCurator\Feed;

use SocialCurator\Config\SettingsRepository;
use SocialCurator\Config\SupportedSites;

/**
* Base class for extending site/API specific feeds
*/
abstract class FeedBase {

	/**
	* Settings Repository
	* @var SocialCurator\Config\SettingsRepository
	*/
	protected $settings_repo;

	/**
	* Supported Sites
	* @var SocialCurator\Config\SupportedSites
	*/
	protected $supported_sites;

	/**
	* API Credentials
	* @var array
	*/
	protected $credentials;

	/**
	* The Unformatted Feed
	*/
	protected $feed;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		$this->supported_sites = new SupportedSites;
	}

}