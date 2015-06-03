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
	* @param int $post_id WP Post ID
	* @return html
	*/
	public function getAvatar($post_id)
	{
		if ( !$post_id ) return false;
		$upload_dir = wp_upload_dir();
		$image = get_post_meta($post_id, 'social_curator_avatar', true);
		if ( !$image ) return false;
		return '<img src="' . $upload_dir['baseurl'] . '/social-curator/avatars/' . $image . '" />';
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