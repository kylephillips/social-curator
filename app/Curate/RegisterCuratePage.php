<?php namespace SocialCurator\Curate;

use SocialCurator\Helpers;
use SocialCurator\Config\SettingsRepository;

/**
* Register the Curate Admin Menu Item and Display the View for Curating Posts
*/
class RegisterCuratePage {

	/**
	* Settings Repo
	* @var SocialCurator\Config\SettingsRepository
	*/
	private $settings_repo;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
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
			'59.2'
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