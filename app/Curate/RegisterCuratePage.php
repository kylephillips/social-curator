<?php 

namespace SocialCurator\Curate;

use SocialCurator\Helpers;
use SocialCurator\Config\SettingsRepository;
use SocialCurator\Config\SupportedSites;
use SocialCurator\Entities\PostType\SocialPost\SocialPostRepository;

/**
* Register the Curate Admin Menu Item and Display the View for Curating Posts
*/
class RegisterCuratePage 
{

	/**
	* Settings Repo
	* @var SocialCurator\Config\SettingsRepository
	*/
	private $settings_repo;

	/**
	* Settings Repo
	* @var SocialCurator\Config\SupportedSites
	*/
	private $supported_sites;

	/**
	* Social Posts Repo
	* @var SocialCurator\Entities\PostType\SocialPost\SocialPostRepository
	*/
	private $social_post_repo;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		$this->social_post_repo = new SocialPostRepository;
		$this->supported_sites = new SupportedSites;
		add_action('admin_menu', array($this, 'registerMenu'));
	}

	/**
	* Register the Menu
	*/
	public function registerMenu()
	{
		$title = $this->settings_repo->menuSetting('title');
		$icon_class = $this->settings_repo->menuSetting('icon_class');
		$unmoderated_count = $this->social_post_repo->getUnmoderatedCount();
		if ( $unmoderated_count > 0 ) $title .= '<span class="update-plugins"><span class="plugin-count" data-social-curator-unmoderated-count>' . $unmoderated_count . '</span></span>';
		add_menu_page( 
			'Curate Social Posts', 
			$title, 
			'edit_posts', 
			'social-curator', 
			array($this, 'view'), 
			'dashicons-share', 
			'59.2'
		);
	}

	/**
	* Get Posts
	*/
	private function loopPosts()
	{
		$args = array(
			'post_status' => array('publish', 'pending', 'draft'),
			'offset' => 0,
			'number' => 10
		);
		$posts = $this->social_post_repo->getPostsArray($args, true);
		foreach($posts as $post) :
			include(Helpers::view('curator/single-post'));
		endforeach;
	}

	/**
	* Display the Curator View
	*/
	public function view()
	{
		include(Helpers::view('curator/curator'));
	}

	/**
	* Display the Help Modals
	*/
	private function helpModals()
	{
		foreach ( $this->supported_sites->getSites() as $key => $site ){
			if ( !isset($site['help_modal_id']) || !$site['help_modal_id'] ) continue;
			include(Helpers::site_view($site['name'], 'id-help-modal'));
		}
	}

}