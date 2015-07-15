<?php

namespace SocialCurator\Listeners;

/**
* Log an image import error
*/
class LogImageImportError
{
	/**
	* Update the DB Log Table
	*/
	public function log($post_id, $message)
	{
		$message = '<a href="' . get_edit_post_link($post_id) . '">' . __('Post ID:', 'socialcurator') . ' ' . $post_id . '</a> - ' . sanitize_text_field($message);
		global $wpdb;
		$table = $wpdb->prefix . 'social_curator_logs';
		$wpdb->insert(
			$table,
			array(
				'type' => 'failed-image-import',
				'message' => $message
			),
			array(
				'%s',
				'%s'
			)
		);
	}
}