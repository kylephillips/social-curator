<?php namespace SocialCurator\Config;

/**
* Configuration for Supported Sites
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
	}

	/**
	* Add Twitter
	*/
	protected function twitter()
	{
		$this->sites['twitter'] = array(
			'name' => 'Twitter',
			'api_endpoint' => 'https://twitter.com',
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
			'api_endpoint' => 'https://instagram.com',
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

}