<?php 

namespace SocialCurator\Listeners;

/**
* Delete a user avatar
*/
class DeleteUserAvatar 
{

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
	* Remove the Image
	*/
	private function removeImage()
	{
		$image = get_post_meta($this->post_id, 'social_curator_avatar', true);
		if ( !$image ) return;

		$uploads = wp_upload_dir();
		$file = $uploads['basedir'] . '/social-curator/avatars/' . $image;
		if ( !file_exists($file) ) return;
		unlink($file);
	}

}