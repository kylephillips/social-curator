<?php namespace SocialCurator;

/**
* Helper Functions
*/
class Helpers {

	/**
	* Plugin URL
	*/
	public static function plugin_url()
	{
		return plugins_url() . '/social-curator';
	}

	/**
	* Plugin Root Directory
	*/
	public static function plugin_root()
	{
		return dirname(__FILE__);
	}

	/**
	* View
	*/
	public static function view($file)
	{
		return dirname(__FILE__) . '/Views/' . $file . '.php';
	}

	/**
	* Site Settings View
	*/
	public static function site_view($site_name, $file)
	{
		return dirname(__FILE__) . '/Entities/Site/' . $site_name . '/Views/' . $file . '.php';
	}

}