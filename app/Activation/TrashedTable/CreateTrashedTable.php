<?php namespace SocialCurator\Activation\TrashedTable;

use SocialCurator\Helpers;

/**
* Create the Trashed Social Posts Table
* Saves posts that are trashed so they are not imported on future imports
*/
class CreateTrashedTable {

	public function __construct()
	{
		register_activation_hook( Helpers::plugin_file(), [ $this, 'createTable' ] );
	}

	/**
	* Create the Table
	*/
	public function createTable()
	{
		global $wpdb;
		$tablename = $wpdb->prefix . 'social_curator_trashed_posts';
		if ( $wpdb->get_var("SHOW TABLES LIKE '" . $tablename . "'") != $tablename ) :
			$sql = 'CREATE TABLE ' . $tablename . '(
				id INTEGER(10) UNSIGNED AUTO_INCREMENT,
				time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				site VARCHAR(20),
				post_id VARCHAR(20),
				PRIMARY KEY  (id) )';
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		endif;
	}

}