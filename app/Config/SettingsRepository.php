<?php namespace SocialCurator\Config;

use SocialCurator\Config\SupportedSites;

class SettingsRepository {

	/**
	* Get the plugin version being used
	* @since 1.0
	*/
	public function getVersion()
	{
		global $social_curator_version;
		return $social_curator_version;
	}

	/**
	* Get array of supported sites
	* @since 1.0
	*/
	public function getEnabledSites()
	{
		$sites = get_option('social_curator_enabled_sites');
		$enabled = array();
		if ( !is_array($sites) ) return $enabled;
		foreach ( $sites as $key => $site ){
			if ( isset($site['enabled']) ) $enabled[] = $key;
		}
		return $enabled;
	}
	

	/**
	* Get Single Setting/Option for a single supported site
	* @param string $site
	* @param string $setting - See SocialCurator\Config\SupportedSites
	* @since 1.0
	*/
	public function getSiteSetting($site, $setting)
	{
		$option = get_option('social_curator_site_' . $site);
		if ( !$option ) return false;
		if ( isset($option[$setting]) && $option[$setting] !== "" ) return $option[$setting];
		return false;
	}


	/**
	* Are required fields for auth token redirect filled out?
	*/
	public function fieldsForAuthRedirectComplete($sitekey, $site)
	{
		$fields = $site['required_for_auth'];
		if ( empty($fields) ) return true;
		foreach($fields as $field){
			$option = get_option('social_curator_site_' . $sitekey);
			if ( !isset($option[$field]) || $option[$field] == "" ) return false;
		}
		return true;
	}

	/**
	* Get the last import
	*/
	public function lastImport()
	{
		$option = get_option('social_curator_last_import');
		if ( !$option ) return __('No Imports Yet', 'socialcurator');
		return $option;
	}

}