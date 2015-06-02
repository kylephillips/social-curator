<?php namespace SocialCurator\Activation\Dependencies;

use SocialCurator\Helpers;

/**
* Register & Enqueue the Admin Dependencies
*/
class AdminDependencies extends Dependencies {

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
		wp_enqueue_script(
			'social-curator-admin', 
			Helpers::plugin_url() . '/assets/js/admin/social-curator-admin.js', 
			array('jquery'),
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
			'run_import' => __('Run Import', 'socialcurator'),
			'importing' => __('Importing', 'socialcurator')
		);
		return $data;
	}

}