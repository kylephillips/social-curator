<?php namespace SocialCurator\Config;

use SocialCurator\Helpers;

/**
* Plugin Settings
*/
class Settings {


	public function __construct()
	{
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
		$tab = ( isset($_GET['tab']) ) ? $_GET['tab'] : 'general';
		include( Helpers::view('settings/settings') );
	}


}