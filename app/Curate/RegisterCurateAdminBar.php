<?php

namespace SocialCurator\Curate;

use SocialCurator\Entities\PostType\SocialPost\SocialPostRepository;

class RegisterCurateAdminBar
{
	/**
	* Social Posts Repo
	* @var SocialCurator\Entities\PostType\SocialPost\SocialPostRepository
	*/
	private $social_post_repo;

	public function __construct()
	{
		$this->social_post_repo = new SocialPostRepository;
		add_action( 'admin_bar_menu', array($this, 'curateLink'), 999 );
	}

	// Add the curate link to the admin bar
	public function curateLink($wp_admin_bar)
	{
		if ( !current_user_can('edit_posts') ) return;
		$unmoderated_count = $this->social_post_repo->getUnmoderatedCount();
		$title = __('Curate', 'socialcurator') . ' <span class="ab-label" data-social-curator-unmoderated-count>(' . $unmoderated_count . ')</span>';
		$args = array(
			'id'	=> 'social_curator',
			'title' => $title,
			'href'  => admin_url('admin.php?page=social-curator'),
			'meta'  => array( 'class' => 'my-toolbar-page' )
		);
		$wp_admin_bar->add_node( $args );
	}
}