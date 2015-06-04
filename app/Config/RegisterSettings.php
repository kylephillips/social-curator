<?php namespace SocialCurator\Config;

use SocialCurator\Config\SupportedSites;


/**
* Register the Plugin Settings
*/
class RegisterSettings {

	/**
	* Supported Sites
	*/
	private $supported_sites;


	public function __construct()
	{
		$this->supported_sites = new SupportedSites;
		add_action( 'admin_init', array( $this, 'registerSettings' ) );
	}


	/**
	* Register the settings
	*/
	public function registerSettings()
	{
		register_setting( 'social-curator-general', 'social_curator_display' );
		register_setting( 'social-curator-general', 'social_curator_enabled_sites' );
		register_setting( 'social-curator-general', 'social_curator_import_status' );
		register_setting( 'social-curator-hidden', 'social_curator_last_import' );
		register_setting( 'social-curator-hidden', 'social_curator_last_import_count' );
		$this->registerSocialSiteSettings();
	}

	/**
	* Register Social Site Settings
	* Dynamically registers a group and setting for each enabled social site returned from SocialCurator\Config\SupportedSites
	*/
	public function registerSocialSiteSettings()
	{
		foreach($this->supported_sites->getSites() as $key => $site){
			$setting_group = 'social-curator-site-' . $key;
			$setting_name = 'social_curator_site_' . $key;
			register_setting($setting_group, $setting_name);
		}
	}

}