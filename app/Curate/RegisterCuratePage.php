<?php namespace SocialCurator\Curate;

use SocialCurator\Helpers;

/**
* Register the Curate Admin Menu Item and Display the View for Curating Posts
*/
class RegisterCuratePage {

	public function __construct()
	{
		add_action('admin_menu', array($this, 'registerMenu'));
	}

	/**
	* Register the Menu
	*/
	public function registerMenu()
	{
		add_menu_page( 
			'Curate Social Posts', 
			'Social Curator', 
			'edit_posts', 
			'social-curator', 
			array($this, 'view'), 
			'dashicons-share', 
			26
		);
	}

	/**
	* Display the Curator View
	*/
	public function view()
	{
		include(Helpers::view('curator/curator'));
	}

}