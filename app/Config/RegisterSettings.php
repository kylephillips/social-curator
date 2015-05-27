<?php namespace SocialCurator\Config;

/**
* Register the Plugin Settings
*/
class RegisterSettings {

	public function __construct()
	{
		add_action( 'admin_init', array( $this, 'registerSettings' ) );
	}


	/**
	* Register the settings
	*/
	public function registerSettings()
	{
		// register_setting( 'nestedpages-general', 'nestedpages_menu' );
	}

}