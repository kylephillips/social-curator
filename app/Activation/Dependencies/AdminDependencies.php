<?php 

namespace SocialCurator\Activation\Dependencies;

use SocialCurator\Helpers;

/**
* Register & Enqueue the Admin Dependencies
*/
class AdminDependencies extends Dependencies 
{

	public function __construct()
	{
		parent::__construct();
		add_action( 'admin_enqueue_scripts', array( $this, 'styles' ));
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ));
	}

	/**
	* Register/Enqueue the Plugin Admin Styles
	*/
	public function styles()
	{
		wp_enqueue_style(
			'social-curator-admin', 
			Helpers::plugin_url() . '/assets/css/admin/social-curator-admin.css', 
			array(),
			$this->version
		);
	}

	/**
	* Register/Enqueue the Plugin Admin Scripts
	*/
	public function scripts()
	{
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
		wp_enqueue_script('jquery-masonry');
		wp_enqueue_script(
			'social-curator-admin', 
			Helpers::plugin_url() . '/assets/js/admin/social-curator-admin.js', 
			array('jquery', 'jquery-masonry'),
			$this->version
		);
		wp_localize_script( 
			'social-curator-admin', 
			'social_curator_admin', 
			$this->localizedData()
		);
	}

	/**
	* Localized JS Data
	*/
	public function localizedData()
	{
		$data = array(
			'social_curator_nonce' => wp_create_nonce( 'social-curator-nonce' ),
			'can_delete_posts' => ( current_user_can('edit_others_posts') ) ? true : false,
			'run_import' => __('Run Import', 'socialcurator'),
			'import' => __('Import', 'socialcurator'),
			'import_all' => __('Import All', 'socialcurator'),
			'importing' => __('Importing', 'socialcurator'),
			'approved_by' => __('Approved by', 'socialcurator'),
			'on' => __('on', 'socialcurator'),
			'edit' => __('Edit', 'socialcurator'),
			'permanently_delete' => __('Permanently Delete', 'socialcurator'),
			'restore' => __('Restore', 'socialcurator'),
			'updating' => __('Updating', 'socialcurator'),
			'update' => __('Update', 'socialcurator'),
			'choose_image' => __('Choose Image', 'socialcurator'),
			'unapprove_and_trash' => __('Unapprove and Trash', 'socialcurator'),
			'fetching_feed' => __('Fetching Feed', 'socialcurator'),
			'test_feed' => __('Test Feed', 'socialcurator')
		);
		return $data;
	}

}