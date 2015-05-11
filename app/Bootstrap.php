<?php namespace SocialCurator;

/**
* Primary Plugin Bootstrap
*/
class Bootstrap {

	public function __construct()
	{
		$this->pluginInit();
		add_action( 'init', array($this, 'wordpressInit') );
		add_filter( 'plugin_action_links_' . 'social-curator/social-curator.php', array($this, 'settingsLink' ) );
	}

	/**
	* Initialize Plugin
	*/
	private function pluginInit()
	{
		
	}


	/**
	* Wordpress Initialization Actions
	*/
	public function wordpressInit()
	{
		$this->localize();
	}


	/**
	* Localization Domain
	*/
	public function localize()
	{
		load_plugin_textdomain(
			'socialcurator', 
			false, 
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages'
		);
	}


	/**
	* Add a link to the settings on the plugin page
	*/
	public function settingsLink($links)
	{ 
		$settings_link = '<a href="options-general.php?page=social-curator-settings">' . __('Settings') . '</a>'; 
		array_unshift($links, $settings_link); 
		return $links; 
	}


}