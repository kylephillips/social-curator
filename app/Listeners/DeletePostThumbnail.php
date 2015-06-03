<?php namespace SocialCurator\Listeners;

/**
* Delete a Social Post Thumbnail
*/
class DeletePostThumbnail {

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
	* Delete the Avatar Image
	*/
	public function delete()
	{
		if ( $this->isSocialPost() ) return $this->removeImage();
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
	* Remove the Thumbnail
	*/
	private function removeImage()
	{
		$thumb_id = get_post_thumbnail_id($this->post_id);
		if ( !$thumb_id ) return;
		wp_delete_attachment($thumb_id, true);
		delete_post_thumbnail($this->post_id);
	}

}