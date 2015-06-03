<?php namespace SocialCurator\Entities\PostType\SocialPost;

use SocialCurator\Config\SupportedSites;

class SocialPostPresenter {

	/**
	* Supported Sites
	* @var SocialCurator\Config\SupportedSites
	*/
	private $supported_sites;

	public function __construct()
	{
		$this->supported_sites = new SupportedSites;
	}

	/**
	* Get a profile image/avatar
	* @param string $screen_name
	* @return html
	*/
	public function getAvatar($screen_name = null)
	{
		if ( !$screen_name ) return false;
		$upload_dir = wp_upload_dir();
		return '<img src="' . $upload_dir['baseurl'] . '/social-curator/avatars/' . $screen_name . '" />';
	}

	/**
	* Get the link to a user profile
	* @param string $user
	* @param string $site
	*/
	public function getProfileLink($user, $site)
	{
		$site_details = $this->supported_sites->getSite($site);
		if ( empty($site_details) ) return false;
		return $site_details['user_uri'] . $user;
	}

}