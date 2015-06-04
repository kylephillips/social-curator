<?php namespace SocialCurator\Commands;

use SocialCurator\Commands\RunImportCommand;

/**
* Register CRON commands that need to run
*/
class RegisterCommands {

	public function __construct()
	{
		if ( ! wp_next_scheduled( 'run_social_import' ) ) wp_schedule_event( time(), 'hourly', 'run_social_import' );
		add_action( 'run_social_import', array($this, 'runImport') );
	}

	/**
	* Run the Import
	*/
	public function runImport()
	{
		$import = new RunImportCommand;
	}

}