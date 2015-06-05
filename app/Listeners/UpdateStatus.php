<?php namespace SocialCurator\Listeners;

/**
* Update Post Status 
*/
class UpdateStatus extends ListenerBase {

	public function __construct()
	{
		parent::__construct();
		$this->updateStatus();
		$this->sendSuccess();
	}

	/**
	* Update the Post Status
	*/
	private function updateStatus()
	{
		$status = sanitize_text_field($_POST['status']);
		$id = intval(sanitize_text_field($_POST['post_id']));
		if ( $status == 'draft' || $status == 'pending' ) $status = 'pending';
		wp_update_post(array('ID' => $id, 'post_status' => $status));
		return;
	}

}