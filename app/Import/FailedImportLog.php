<?php namespace SocialCurator\Import;
/**
* Log a failed import
*/
class FailedImportLog {

	/**
	* The message to log
	* @var string
	*/
	private $message;

	public function __construct($message)
	{
		$this->message = $message;
		$this->updateLogTable();
	}
	
	/**
	* Update the DB Log Table
	*/
	private function updateLogTable()
	{
		global $wpdb;
		$table = $wpdb->prefix . 'social_curator_logs';
		$wpdb->insert(
			$table,
			array(
				'type' => 'failed-import',
				'message' => sanitize_text_field($this->message)
			),
			array(
				'%s',
				'%s'
			)
		);
	}

}