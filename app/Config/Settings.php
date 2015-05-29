<?php namespace SocialCurator\Config;

use SocialCurator\Config\SettingsRepository;
use SocialCurator\Config\SupportedSites;
use SocialCurator\Helpers;

/**
* Plugin Settings
*/
class Settings {

	/**
	* Settings Repository
	* @var SocialCurator\Config\SettingsRepository
	*/
	private $settings_repo;

	/**
	* Supported Sites
	*/
	private $supported_sites;

	/**
	* Current Site Index
	* @see SocialCurator\Config\SupportedSites
	* @var string
	*/
	private $site_index;

	/**
	* Current site in view
	* @see SocialCurator\Config\SupportedSites
	* @var array
	*/
	private $site;


	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		$this->supported_sites = new SupportedSites;
		add_action('admin_menu', array($this, 'registerSettingsPage'));
	}

	/**
	* Register the settings page
	*/
	public function registerSettingsPage()
	{
		add_options_page( 
			__('Social Curator Settings', 'socialcurator'),
			__('Social Curator', 'socialcurator'),
			'manage_options',
			'social-curator-settings', 
			array( $this, 'settingsPage' ) 
		);
	}

	/**
	* Display the Settings Page
	* Callback for registerSettingsPage method
	*/
	public function settingsPage()
	{
		$this->site_index = ( isset($_GET['tab']) ) ? strtolower($_GET['tab']) : 'general';
		$this->site = $this->supported_sites->getSite($this->site_index);

		$tab = ( isset($_GET['tab']) ) ? $_GET['tab'] : 'general';
		include( Helpers::view('settings/settings') );
	}

	/**
	* Social Site Settings Tabs
	*/
	private function siteTabs()
	{
		$tab = ( isset($_GET['tab']) ) ? $_GET['tab'] : 'general';
		$out = '';
		foreach ( $this->supported_sites->getSites() as $key => $site){
			$out .= '<a class="nav-tab';
			if ( $tab == $key ) $out .= ' nav-tab-active';
			$out .= '" href="options-general.php?page=social-curator-settings&tab=' . $key . '">' . $site['name'] . '</a>';
		}
		return $out;
	}

	/**
	* Get Redirect URL Base for Authentication purposes
	*/
	private function getRedirectURL($tab = null)
	{
		$url = admin_url('options-general.php') . '?page=social-curator-settings';
		if ( isset($tab) ) $url .= '&tab=' . $tab;
		return $url;
	}


}