<?php namespace SocialCurator\Entities\PostType\SocialPost;

class SocialPostPresenter {

	/**
	* Get a profile image/avatar
	* @param string $screen_name
	* @return html
	*/
	public function getAvatar($screen_name)
	{
		$upload_dir = wp_upload_dir();
		return '<img src="' . $upload_dir['baseurl'] . '/social-curator/avatars/' . $screen_name . '" />';
	}

}