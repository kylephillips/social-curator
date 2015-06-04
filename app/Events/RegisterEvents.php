<?php namespace SocialCurator\Events;

use SocialCurator\Listeners\RunManualImport;
use SocialCurator\Listeners\LogTrashedPost;
use SocialCurator\Listeners\DeleteUserAvatar;
use SocialCurator\Listeners\DeletePostThumbnail;
use SocialCurator\Listeners\UpdateApprovalStatus;
use SocialCurator\Listeners\GetSocialPosts;
use SocialCurator\Listeners\TrashPost;

/**
* Register the App-wide events
* Events for individual social sites should be registered within their own namespace
*/
class RegisterEvents {

	public function __construct()
	{
		// Run an Import Manully - Admin Only
		add_action( 'wp_ajax_social_curator_manual_import', array($this, 'importWasRun' ));

		// Post Was Trashed
		add_action('before_delete_post', array($this, 'postWasDeleted'));

		// Post Was Saved
		add_action('save_post', array($this, 'postStatusChanged'), 10, 3);

		// Request for Social Posts (AJAX)
		add_action( 'wp_ajax_nopriv_social_curator_get_posts', array($this, 'postsRequested' ));
		add_action( 'wp_ajax_social_curator_get_posts', array($this, 'postsRequested' ));

		// Trash a Post
		add_action( 'wp_ajax_social_curator_trash_post', array($this, 'postTrashRequested' ));
	}

	/**
	* Import was triggered manally
	*/
	public function importWasRun()
	{
		new RunManualImport;
	}

	/**
	* Post was Permanently Deleted
	*/
	public function postWasDeleted($post)
	{
		// Log the trashed post to prevent future import
		$logger = new LogTrashedPost($post);
		$logger->log();

		// Delete the User Avatar for the POst
		$avatar_deleter = new DeleteUserAvatar($post);
		$avatar_deleter->delete();

		// Delete the post thumbnail for the post
		$thumbnail_deleter = new DeletePostThumbnail($post);
		$thumbnail_deleter->delete();
	}

	/**
	* Post was saved
	*/
	public function postStatusChanged($post_id, $post, $update)
	{
		$updater = new UpdateApprovalStatus($post_id, $post);
		$updater->updateStatus();
	}

	/**
	* A request was made for social posts via AJAX
	*/
	public function postsRequested()
	{
		new GetSocialPosts;
	}

	/**
	* A request was made to trash a post
	*/
	public function postTrashRequested()
	{
		new TrashPost;
	}

}