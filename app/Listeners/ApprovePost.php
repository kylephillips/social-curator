<?php namespace SocialCurator\Listeners;
/**
* Approve a post from AJAX request
*/
class ApprovePost extends ListenerBase {

	/**
	* Post ID
	* @var int
	*/
	private $post_id;

	/**
	* Return Data
	* @var array
	*/
	private $return_data;

	public function __construct()
	{
		parent::__construct();
		$this->setID();
		$this->approvePost();
		$this->addApprover();
		return $this->sendSuccess();
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
	* Approve the Post
	*/
	private function approvePost()
	{
		wp_update_post(array('ID' => $this->post_id, 'post_status' => 'publish'));
	}

	/**
	* Add Approved by if old is pending and new is publish
	*/
	private function addApprover()
	{
		$current_user = wp_get_current_user();
		update_post_meta($this->post_id, 'social_curator_approved_by', $current_user->display_name);
		update_post_meta($this->post_id, 'social_curator_approved_date', date('Y-m-d H:m:s'));
	}

	/**
	* Send a Success Response
	* @param string $message - Success message to return
	* @return JSON
	*/
	protected function sendSuccess($message = null)
	{
		$current_user = wp_get_current_user();
		return wp_send_json(array(
			'status' => 'success', 
			'id' => $this->post_id,
			'approved_by' => $current_user->display_name,
			'approved_date' => date('Y-m-d H:m:s')
		));
	}

}