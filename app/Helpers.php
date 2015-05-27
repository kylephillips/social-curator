<?php namespace SocialCurator;

/**
* Helper Functions
*/
class Helpers {

	/**
	* Plugin Root Directory
	*/
	public static function plugin_url()
	{
		return plugins_url() . '/social-curator';
	}

	/**
	* View
	*/
	public static function view($file)
	{
		return dirname(__FILE__) . '/Views/' . $file . '.php';
	}

}