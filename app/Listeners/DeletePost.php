<?php namespace SocialCurator\Listeners;

/**
* Delete a post permanently
*/
class DeletePost extends ListenerBase {

	/**
	* Post ID
	* @var int
	*/
	private $post_id;

	public function __construct()
	{
		parent::__construct();
		$this->setID();
		$this->deletePost();
		$this->sendSuccess(__('Post Deleted Successfully', 'socialcurator'));
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
	private function deletePost()
	{
		if ( !is_user_logged_in() ) return;
		if ( !current_user_can('delete_posts') ) return;
		wp_delete_post($this->post_id, true);
	}

}