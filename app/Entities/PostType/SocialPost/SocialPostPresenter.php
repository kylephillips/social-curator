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
	* @param int $post_id
	*/
	public function getProfileLink($post_id)
	{
		$user = $this->getProfileName($post_id);
		if ( !$user ) return false;

		$site = get_post_meta($post_id, 'social_curator_site', true);
		if ( !$site ) return false;

		$site_details = $this->supported_sites->getSite($site);
		if ( empty($site_details) ) return false;
		
		return $site_details['user_uri'] . $user;
	}

	/**
	* Get the profile name
	* @param int $post_id
	*/
	public function getProfileName($post_id)
	{
		return get_post_meta($post_id, 'social_curator_screen_name', true);
	}

	/**
	* Get icon link to post
	* @param int $post_id
	* @return html
	*/
	public function getIconLink($post_id)
	{
		$site = get_post_meta($post_id, 'social_curator_site', true);
		$icon_class = $this->supported_sites->getKey($site, 'icon_class');
		$link = get_post_meta($post_id, 'social_curator_link', true);
		return '<a href="' . $link . '" target="_blank"><i class="' . $icon_class . '"></i></a>';
	}

	/**
	* Get the Post thumbnail URL
	* @param int $post_id
	*/
	public function getThumbnailURL($post_id)
	{
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'full' );
		return $thumb['0'];
	}

}