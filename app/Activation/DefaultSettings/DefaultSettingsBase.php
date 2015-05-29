<?php namespace SocialCurator\Activation\DefaultSettings;
/**
* Base Default Settings Class
*/
class DefaultSettingsBase {

	/**
	* Plugin Version
	* @var string
	*/
	protected $plugin_version;

	/**
	* Old Plugin Version
	* @var string
	*/
	protected $old_plugin_version;


	public function __construct()
	{
		$this->setOldVersion();
		$this->performUpdates();
		$this->setNewVersion();
	}

	/**
	* Get the current plugin version
	*/
	protected function setOldVersion()
	{
		$old_version = get_option('social_curator_version');
		$this->old_plugin_version = ( $old_version ) ? $old_version : '0.1';
	}

	/**
	* Perform Updates before setting the new plugin version
	*/
	protected function performUpdates()
	{

	}

	/**
	* Set the new plugin version
	*/
	protected function setNewVersion()
	{
		global $social_curator_version;
		$this->plugin_version = $social_curator_version;
		update_option('social_curator_version', $this->plugin_version);
	}

	/**
	* Check if current version is newer than given version
	* @param string - version number
	*/
	protected function versionIsNewerThan($version)
	{
		return ( version_compare($version, $this->old_plugin_version, '>=') ) ? true : false;
	}

	/**
	* Check if current version is older than given version
	* @param string - version number
	*/
	protected function versionIsOlderThan($version)
	{
		return ( version_compare($version, $this->old_plugin_version, '<=') ) ? true : false;
	}

}