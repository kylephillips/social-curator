<?php 

namespace SocialCurator\Activation\Migrations;

use SocialCurator\Helpers;

/**
* Create DB Tables Required by the Plugin
*/
class CreateTables 
{

	public function __construct()
	{
		register_activation_hook( Helpers::plugin_file(), array($this, 'createTables') );
	}

	/**
	* Create the DB tables
	*/
	public function createTables()
	{
		$this->createTrashedTable();
		$this->createLogsTable();
	}

	/**
	* Create the Table to log trashed posts
	*/
	private function createTrashedTable()
	{
		global $wpdb;
		$tablename = $wpdb->prefix . 'social_curator_trashed_posts';
		if ( $wpdb->get_var("SHOW TABLES LIKE '" . $tablename . "'") != $tablename ) :
			$sql = 'CREATE TABLE ' . $tablename . '(
				id INTEGER(10) UNSIGNED AUTO_INCREMENT,
				time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				site VARCHAR(255) COLLATE utf8mb4_unicode_ci,
				post_id LONGTEXT COLLATE utf8mb4_unicode_ci,
				PRIMARY KEY  (id) )';
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		endif;
	}

	/**
	* Create the Table to store logs
	*/
	private function createLogsTable()
	{
		global $wpdb;
		$tablename = $wpdb->prefix . 'social_curator_logs';
		if ( $wpdb->get_var("SHOW TABLES LIKE '" . $tablename . "'") != $tablename ) :
			$sql = 'CREATE TABLE ' . $tablename . '(
				id INTEGER(10) UNSIGNED AUTO_INCREMENT,
				time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				type VARCHAR(255) DEFAULT "import-error",
				message LONGTEXT COLLATE utf8mb4_unicode_ci,
				PRIMARY KEY  (id) )';
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		endif;
	}

}