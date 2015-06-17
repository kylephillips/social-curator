<?php 

namespace SocialCurator\Events;

use SocialCurator\Listeners\RunManualImport;
use SocialCurator\Listeners\RunSingleImport;
use SocialCurator\Listeners\LogTrashedPost;
use SocialCurator\Listeners\DeleteUserAvatar;
use SocialCurator\Listeners\DeletePostThumbnail;
use SocialCurator\Listeners\UpdateApproverDetails;
use SocialCurator\Listeners\GetSocialPosts;
use SocialCurator\Listeners\TrashPost;
use SocialCurator\Listeners\ApprovePost;
use SocialCurator\Listeners\RestorePost;
use SocialCurator\Listeners\DeletePost;
use SocialCurator\Listeners\UpdateStatus;
use SocialCurator\Listeners\EmptyTrash;
use SocialCurator\Listeners\ClearLogs;
use SocialCurator\Listeners\TestFeed;

/**
* Register the Admin events
* Events for individual social sites should be registered within their own namespace
*/
class RegisterAdminEvents 
{

	public function __construct()
	{
		// Run a Search Import Manully
		add_action( 'wp_ajax_social_curator_manual_import', array($this, 'importWasRun' ));

		// Run a Single Import
		add_action( 'wp_ajax_social_curator_single_import', array($this, 'singleImportWasRun' ));

		// Post Was Trashed
		add_action('before_delete_post', array($this, 'postWasDeleted'));

		// Post Was Saved
		add_action('save_post', array($this, 'postStatusChanged'), 10, 3);

		// Request for Social Posts (AJAX)
		add_action( 'wp_ajax_nopriv_social_curator_get_posts', array($this, 'postsRequested' ));
		add_action( 'wp_ajax_social_curator_get_posts', array($this, 'postsRequested' ));

		// Trash a Post
		add_action( 'wp_ajax_social_curator_trash_post', array($this, 'postTrashRequested' ));

		// Approve a Post
		add_action( 'wp_ajax_social_curator_approve_post', array($this, 'postApprovalRequested' ));

		// Restore a Post
		add_action( 'wp_ajax_social_curator_restore_post', array($this, 'restorePostRequested' ));

		// Delete a Post
		add_action( 'wp_ajax_social_curator_delete_post', array($this, 'deletePostRequested' ));

		// Update the Status of a Post (Generic update event for AJAX Requests)
		add_action( 'wp_ajax_social_update_post_status', array($this, 'statusWasUpdated' ));

		// Empty the trash
		add_action( 'wp_ajax_social_empty_trash', array($this, 'trashWasEmptied' ));

		// Clear the Logs
		add_action( 'wp_ajax_social_curator_clear_logs', array($this, 'logsWereCleared' ));

		// Get a Test feed
		add_action( 'wp_ajax_social_curator_test_feed', array($this, 'testFeedRequested' ));
	}

	/**
	* Import was triggered manally
	*/
	public function importWasRun()
	{
		new RunManualImport;
	}

	/**
	* 
	*/
	public function singleImportWasRun()
	{
		new RunSingleImport;
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
		$updater = new UpdateApproverDetails($post_id, $post);
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

	/**
	* A request was made to trash a post
	*/
	public function postApprovalRequested()
	{
		new ApprovePost;
	}

	/**
	* A request was made to restore a post
	*/
	public function restorePostRequested()
	{
		new RestorePost;
	}

	/**
	* A request was made to delete a post
	*/
	public function deletePostRequested()
	{
		new DeletePost;
	}

	/**
	* A request was made to update the status of a post
	*/
	public function statusWasUpdated()
	{
		new UpdateStatus;
	}

	/**
	* A request was made to empty the trash
	*/
	public function trashWasEmptied()
	{
		new EmptyTrash;
	}

	/**
	* A request was made to clear the import logs
	*/
	public function logsWereCleared()
	{
		new ClearLogs;
	}

	/**
	* A request was made for a test feed
	*/
	public function testFeedRequested()
	{
		new TestFeed;
	}

}