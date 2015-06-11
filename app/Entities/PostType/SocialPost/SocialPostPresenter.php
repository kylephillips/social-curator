<?php 

namespace SocialCurator\Entities\PostType\SocialPost;

use SocialCurator\Config\SupportedSites;
use SocialCurator\Helpers;
use SocialCurator\Config\SettingsRepository;

class SocialPostPresenter 
{

	/**
	* Supported Sites
	* @var SocialCurator\Config\SupportedSites
	*/
	private $supported_sites;

	/**
	* Settings Repository
	* @var SocialCurator\Config\SettingsRepository
	*/
	private $settings_repo;

	public function __construct()
	{
		$this->supported_sites = new SupportedSites;
		$this->settings_repo = new SettingsRepository;
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
		$fallback = $this->settings_repo->fallbackAvatar();
		return '<img src="' . $upload_dir['baseurl'] . '/social-curator/avatars/' . $image . '" onerror="this.onerror=null;this.src=' . "'" . $fallback . "'" . ';" />';
	}

	/**
	* Get a profile image/avatar link
	* @param int $post_id WP Post ID
	* @return url
	*/
	public function getAvatarURL($post_id)
	{
		if ( !$post_id ) return false;
		$upload_dir = wp_upload_dir();
		$image = get_post_meta($post_id, 'social_curator_avatar', true);
		if ( !$image ) return false;
		return $upload_dir['baseurl'] . '/social-curator/avatars/' . $image;
	}

	/**
	* Get the link to a user profile
	* @param int $post_id
	*/
	public function getProfileLink($post_id)
	{
		return get_post_meta($post_id, 'social_curator_profile_url', true);
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