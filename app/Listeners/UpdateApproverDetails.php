<?php namespace SocialCurator\Listeners;

/**
* Update approval status when a post is updated
*/
class UpdateApproverDetails {

	/**
	* The Post ID
	* @var int $post_id
	*/
	private $post_id;

	/**
	* The Post after update
	* @var Post Object $post
	*/
	private $post;

	public function __construct($post_id, $post)
	{
		$this->post_id = $post_id;
		$this->post = $post;
	}

	/**
	* Update the Status
	*/
	public function updateStatus()
	{
		if ( !$this->isSocialPost() ) return;
		if ( $this->post->post_status == 'publish' ) $this->addApprover();
		if ( $this->post->post_status !== 'publish' ) $this->removeApprover();
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
	* Add Approved by if old is pending and new is publish
	*/
	private function addApprover()
	{
		if ( isset($_REQUEST['social_curator_approved_by']) ) return;
		$current_user = wp_get_current_user();
		update_post_meta($this->post_id, 'social_curator_approved_by', $current_user->display_name);
		update_post_meta($this->post_id, 'social_curator_approved_date', date('Y-m-d H:m:s'));
	}

	/**
	* Remove Approver if old is approved and new is pending
	*/
	private function removeApprover()
	{
		delete_post_meta($this->post_id, 'social_curator_approved_by');
		delete_post_meta($this->post_id, 'social_curator_approved_date');
	}

}