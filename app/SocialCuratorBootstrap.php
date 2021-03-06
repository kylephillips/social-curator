<?php 

namespace SocialCurator;

/**
* Primary Plugin Bootstrap
*/
class SocialCuratorBootstrap 
{

	public function __construct()
	{
		session_start();
		$this->pluginInit();
		add_action( 'init', array($this, 'wordpressInit') );
		add_filter( 'plugin_action_links_' . 'social-curator/social-curator.php', array($this, 'settingsLink' ) );
	}

	/**
	* Initialize Plugin
	*/
	private function pluginInit()
	{
		new Jobs\RegisterIntervals;
		new Jobs\RegisterJobs;
		new Entities\PostType\SocialPost\ContentFilter;
		new Entities\PostType\SocialPost\RegisterSocialPost;
		new Entities\PostType\SocialPost\SocialPostMeta;
		new Config\RegisterSettings;
		new Curate\RegisterCurateAdminBar;
		new Curate\RegisterCuratePage;
		new Config\Settings;
		new Events\RegisterAdminEvents;
		new Events\RegisterPublicEvents;
		new Activation\Migrations\CreateTables;
		new Activation\Dependencies\AdminDependencies;
		new Activation\Dependencies\PublicDependencies;
		new Activation\RegisterSites\RegisterSites;
		new API\APIFactory;
	}

	/**
	* Wordpress Initialization Actions
	*/
	public function wordpressInit()
	{
		$this->localize();
		new Entities\PostType\SocialPost\SocialPostColumns;
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