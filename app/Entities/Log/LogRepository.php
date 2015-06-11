<?php

namespace SocialCurator\Entities\Log;

class LogRepository
{
	public function getLogs()
	{
		global $wpdb;
		$table = $wpdb->prefix . 'social_curator_logs';
		$query = "SELECT * FROM $table";
		$rows = $wpdb->get_results($query);
		return $rows;
	}

	public function clearLogs()
	{
		if ( !current_user_can('delete_others_posts') ) return;
		global $wpdb;
		$table = $wpdb->prefix . 'social_curator_logs';
		$wpdb->query("TRUNCATE TABLE $table");
	}
}