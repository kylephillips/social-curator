<?php

namespace SocialCurator\Curate;

use SocialCurator\Entities\PostType\SocialPost\SocialPostRepository;
use SocialCurator\Config\SettingsRepository;

class RegisterCurateAdminBar
{
	/**
	* Social Posts Repo
	* @var SocialCurator\Entities\PostType\SocialPost\SocialPostRepository
	*/
	private $social_post_repo;

	/**
	* Settings Repo
	* @var SocialCurator\Config\SettingsRepository
	*/
	private $settings_repo;

	public function __construct()
	{
		$this->social_post_repo = new SocialPostRepository;
		$this->settings_repo = new SettingsRepository;
		add_action( 'admin_bar_menu', array($this, 'curateLink'), 999 );
	}

	// Add the curate link to the admin bar
	public function curateLink($wp_admin_bar)
	{
		if ( !$this->settings_repo->displayMenu('show_adminbar_menu') ) return;
		if ( !current_user_can('edit_posts') ) return;
		$unmoderated_count = $this->social_post_repo->getUnmoderatedCount();
		$title = $this->settings_repo->menuSetting('adminbar_title');
		if ( $unmoderated_count > 0 ) $title .= ' <span class="ab-label" data-social-curator-unmoderated-count>(' . $unmoderated_count . ')</span>';
		$args = array(
			'id'	=> 'social_curator',
			'title' => $title,
			'href'  => admin_url('admin.php?page=social-curator'),
			'meta'  => array( 'class' => 'my-toolbar-page' )
		);
		$wp_admin_bar->add_node( $args );
	}
}