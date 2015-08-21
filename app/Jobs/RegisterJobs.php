<?php 

namespace SocialCurator\Jobs;

use SocialCurator\Jobs\ImportPosts;

/**
* Schedule necessary jobs/crons
*/
class RegisterJobs 
{

	public function __construct()
	{
		add_action('import_social_posts', array($this, 'importPosts'));
		global $social_curator_file;
		register_activation_hook($social_curator_file, array($this, 'scheduleJobs'));
		register_deactivation_hook($social_curator_file, array($this, 'removeJobs'));
	}

	/**
	* Schedule the WP Cron Jobs
	*/
	public function scheduleJobs()
	{
		if ( !wp_next_scheduled('import_social_posts') ){
			wp_schedule_event( time(), 'everyminute', 'import_social_posts' );
		}
	}

	/**
	* Import Posts
	*/
	public function importPosts()
	{
		new ImportPosts;
	}

	/**
	* Remove the WP Cron Jobs
	*/
	public function removeJobs()
	{
		wp_clear_scheduled_hook('import_social_posts');
	}

}