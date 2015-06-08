<?php namespace SocialCurator\Activation\Dependencies;

use SocialCurator\Helpers;

/**
* Register & Enqueue the Admin Dependencies
*/
class PublicDependencies extends Dependencies {

	public function __construct()
	{
		parent::__construct();
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ));
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ));
	}


	/**
	* Register/Enqueue the Plugin Admin Styles
	*/
	public function styles()
	{
		wp_enqueue_style(
			'social-curator', 
			Helpers::plugin_url() . '/assets/css/public/social-curator.css', 
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
			'social-curator', 
			Helpers::plugin_url() . '/assets/js/public/social-curator.js', 
			array('jquery'),
			$this->version
		);
		wp_localize_script( 
			'social-curator', 
			'social_curator', 
			$this->localizedData()
		);
	}


	/**
	* Localized JS Data
	*/
	public function localizedData()
	{
		$data = array(
			'ajaxurl' => admin_url('admin-ajax.php')
		);
		return $data;
	}

}