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
		add_action('init', array($this, 'scheduleJobs'));
		add_action('import_social_posts', array($this, 'importPosts'));
	}

	/**
	* Schedule the WP Cron Jobs
	*/
	public function scheduleJobs()
	{
		if ( !wp_next_scheduled( 'import_social_posts' ) ) {
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

}