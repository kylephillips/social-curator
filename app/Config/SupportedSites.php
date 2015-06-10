<?php namespace SocialCurator\Config;

/**
* Configuration for Supported Sites
* Each method sets array of keys required for site.
* Required keys include name, api_endpoint, and settings fields
*/
class SupportedSites {

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
			'namespace' => 'Twitter',
			'api_endpoint' => 'https://api.twitter.com/1.1/',
			'icon_class' => 'social-curator-icon-twitter',
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
			'namespace' => 'Instagram',
			'api_endpoint' => 'https://api.instagram.com/v1/',
			'icon_class' => 'social-curator-icon-instagram',
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
			'namespace' => 'Flickr',
			'api_endpoint' => 'https://api.flickr.com/',
			'icon_class' => 'social-curator-icon-flickr2',
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
			'name' => 'YouTube',
			'namespace' => 'Youtube',
			'api_endpoint' => 'https://www.googleapis.com/youtube/v3/',
			'icon_class' => 'social-curator-icon-youtube3',
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
			'namespace' => 'Facebook',
			'api_endpoint' => 'https://www.googleapis.com/youtube/v3/',
			'icon_class' => 'social-curator-icon-facebook',
			'settings_fields' => array(
				'app_id' => __('APP ID', 'socialcurator'),
				'app_secrent' => __('APP Secret', 'socialcurator'),
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

}