<?php namespace SocialCurator\Listeners;

/**
* Log a trashed post so it is not imported again
*/
class LogTrashedPost {

	/**
	* The Post ID
	* @var int
	*/
	private $post_id;

	public function __construct($post_id)
	{
		$this->post_id = $post_id;
	}

	/**
	* Log the Post
	*/
	public function log()
	{
		if ( $this->isSocialPost() ) return $this->updateLogTable();
		return true;
	}

	/**
	* Is the post a social post?
	* @return boolean
	*/
	private function isSocialPost()
	{
		return ( get_post_type($this->post_id) == 'social-post' ) ? true : false;
	}

	/**
	* Update the DB Log Table
	*/
	private function updateLogTable()
	{
		$site = get_post_meta($this->post_id, 'social_curator_site', true);
		$id = get_post_meta($this->post_id, 'social_curator_original_id', true);
		global $wpdb;
		$table = $wpdb->prefix . 'social_curator_trashed_posts';
		$wpdb->insert(
			$table,
			array(
				'site' => $site,
				'post_id' => $id,
				'time' => date('Y-m-d H:i:s')
			)
		);
	}

}