<?php 

namespace SocialCurator\Listeners;

/**
* Move a post to the trash, return AJAX response
*/
class TrashPost extends ListenerBase 
{

	/**
	* Post ID
	*/
	private $post_id;

	public function __construct()
	{
		parent::__construct();
		$this->setID();
		$this->trashPost();
		$this->sendSuccess();
	}

	/**
	* Set/validate the post id to be trashed
	*/
	private function setID()
	{
		if ( !isset($_POST['post_id']) ) return $this->sendError(__('Please provide a valid post ID', 'socialcurator'));
		$this->post_id = sanitize_text_field(intval($_POST['post_id']));
	}

	/**
	* Trash the Post
	*/
	private function trashPost()
	{
		wp_trash_post($this->post_id);
	}

}