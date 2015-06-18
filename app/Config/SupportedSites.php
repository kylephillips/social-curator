<?php 

namespace SocialCurator\Config;

/**
* Configuration for Supported Sites
* Each method sets array of keys required for site.
* Required keys include name, api_endpoint, and settings fields
*/
class SupportedSites 
{

	/**
	* Supported Sites
	* @var array
	*/
	protected $sites;

	public function __construct()
	{
		$this->twitter();
		$this->instagram();
		$this->facebook();
		$this->flickr();
		$this->youtube();
	}

	/**
	* Add Twitter
	*/
	protected function twitter()
	{
		$this->sites['twitter'] = array(
			'name' => 'Twitter',
			'namespace' => 'SocialCurator\Entities\Site\Twitter',
			'api_endpoint' => 'https://api.twitter.com/1.1/',
			'icon_class' => 'social-curator-icon-twitter',
			'single_import' => true,
			'help_modal_id' => true,
			'settings_fields' => array(
				'api_key' => __('API Key', 'socialcurator'), 
				'api_secret' => __('API Secret', 'socialcurator'), 
				'access_token' => __('Access Token', 'socialcurator'), 
				'access_token_secret' => __('Access Token Secret', 'socialcurator')
			)
		);
	}

	/**
	* Add Twitter
	*/
	protected function instagram()
	{
		$this->sites['instagram'] = array(
			'name' => 'Instagram',
			'namespace' => 'SocialCurator\Entities\Site\Instagram',
			'api_endpoint' => 'https://api.instagram.com/v1/',
			'icon_class' => 'social-curator-icon-instagram',
			'single_import' => true,
			'help_modal_id' => true,
			'required_for_auth' => array(
				'client_id', 'client_secret'
			),
			'settings_fields' => array(
				'client_id' => __('Client ID', 'socialcurator'), 
				'client_secret' => __('Client Secret', 'socialcurator'),
				'auth_token' => __('Authorization Token', 'socialcurator')
			)
		);
	}

	/**
	* Add Flickr
	*/
	protected function flickr()
	{
		$this->sites['flickr'] = array(
			'name' => 'Flickr',
			'namespace' => 'SocialCurator\Entities\Site\Flickr',
			'api_endpoint' => 'https://api.flickr.com/',
			'icon_class' => 'social-curator-icon-flickr2',
			'single_import' => true,
			'help_modal_id' => true,
			'settings_fields' => array(
				'api_key' => __('API Key', 'socialcurator')
			)
		);
	}

	/**
	* Add Youtube
	*/
	protected function youtube()
	{
		$this->sites['youtube'] = array(
			'name' => 'Youtube',
			'namespace' => 'SocialCurator\Entities\Site\Youtube',
			'api_endpoint' => 'https://www.googleapis.com/youtube/v3/',
			'icon_class' => 'social-curator-icon-youtube3',
			'single_import' => true,
			'help_modal_id' => true,
			'settings_fields' => array(
				'api_key' => __('API Key', 'socialcurator')
			)
		);
	}

	/**
	* Add Facebook
	*/
	protected function facebook()
	{
		$this->sites['facebook'] = array(
			'name' => 'Facebook',
			'namespace' => 'SocialCurator\Entities\Site\Facebook',
			'api_endpoint' => 'https://graph.facebook.com/v2.3',
			'icon_class' => 'social-curator-icon-facebook',
			'single_import' => true,
			'settings_fields' => array(
				'page_id' => __('Page ID', 'socialcurator'),
				'app_token' => __('App Token', 'socialcurator'),
			)
		);
	}

	/**
	* Get the Sites Array
	* @return array
	*/
	public function getSites()
	{
		return $this->sites;
	}

	/**
	* Get a single site by key
	* @param string $site
	* @return array
	*/
	public function getSite($site)
	{
		$all_sites = $this->getSites();
		foreach( $all_sites as $key => $single_site ){
			if ( $key == $site ) return $single_site;
		}
		return array();
	}

	/**
	* Get a supported site key
	*/
	public function getKey($site, $key)
	{
		$supported_site = $this->getSite($site);
		if ( empty($supported_site) ) return false;
		if ( !isset($supported_site[$key]) ) return false;
		return $supported_site[$key];
	}

	/**
	* Get Single Imported Sites Array
	* @return array
	*/
	public function singleImportSites()
	{
		$all_sites = $this->getSites();
		$sites = array();
		foreach($all_sites as $key => $site){
			if ( !isset($site['single_import']) || !$site['single_import'] ) continue;
			$sites[$key] = $site['name'];
		}
		return $sites;
	}

}