<?php 

namespace SocialCurator\Listeners;

/**
* Restore a post back to Pending
*/
class RestorePost extends ListenerBase 
{

	/**
	* Post ID
	* @var int
	*/
	private $post_id;

	public function __construct()
	{
		parent::__construct();
		$this->setID();
		$this->restorePost();
		$this->sendSuccess(__('Post Restored Successfully', 'socialcurator'));
	}

	/**
	* Set/validate the post id to be restored
	*/
	private function setID()
	{
		if ( !isset($_POST['post_id']) ) return $this->sendError(__('Please provide a valid post ID', 'socialcurator'));
		$this->post_id = sanitize_text_field(intval($_POST['post_id']));
	}

	/**
	* Restore the Post
	*/
	private function restorePost()
	{
		wp_update_post(array('ID' => $this->post_id, 'post_status' => 'pending'));
	}

}