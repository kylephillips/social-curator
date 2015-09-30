<?php 

namespace SocialCurator\Config;

use SocialCurator\Config\SupportedSites;
use SocialCurator\Helpers;

class SettingsRepository 
{

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
	public function lastImport($format = 'Y-m-d H:i:s')
	{
		$option = get_option('social_curator_last_import');
		if ( !$option ) return __('No Imports Yet', 'socialcurator');
		return date($format, $option);
	}

	/**
	* Get the last import count
	*/
	public function lastImportCount()
	{
		$option = get_option('social_curator_last_import_count');
		if ( !$option ) return 0;
		return $option;
	}

	/**
	* Get the default import status
	*/
	public function importStatus()
	{
		$option = get_option('social_curator_import_status');
		if ( !$option ) return 'pending';
		return $option;
	}

	/**
	* Get Notification Emails
	*/
	public function notificationEmails($format = 'list')
	{
		$option = get_option('social_curator_notification_emails');
		if ( $format == 'list' ) return $option;
		$emails = str_replace(' ', '', $option);
		return explode(',', $emails);
	}

	/**
	* Get the email "from name" setting
	*/
	public function fromName()
	{
		return get_option('social_curator_notification_from');
	}

	/**
	* Get the email "from email" setting
	*/
	public function fromEmail()
	{
		return get_option('social_curator_notification_from_email');
	}

	/**
	* Get the Fallback Avatar
	*/
	public function fallbackAvatar($return_url = true)
	{
		$default_url = Helpers::plugin_url() . '/assets/images/avatar-fallback.png';
		$option = get_option('social_curator_fallback_avatar');
		if ( !$option && !$return_url) return false;
		if ( !$option && $return_url ) return $default_url;
		if ( !$option ) return '<img src="' . $default_url . '" />';
		if ( $return_url ) return $option;
		return '<img src="' . $option . '" />';
	}

	/**
	* Get admin menu option
	*/
	public function menuSetting($setting = 'title')
	{
		$option = get_option('social_curator_admin_menu');
		if ( $setting == 'title' && !isset($option['title']) ) return "Curator";
		if ( $setting == 'title' && $option['title'] == "" ) return "Curator";
		if ( $setting == 'icon_class' && !isset($option['icon_class']) ) return "dashicons-share";
		if ( $setting == 'icon_class' && $option['icon_class'] == "" ) return "dashicons-share";
		return $option[$setting];
	}

}