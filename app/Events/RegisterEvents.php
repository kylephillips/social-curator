<?php namespace SocialCurator\Events;

use SocialCurator\Listeners\RunManualImport;

/**
* Register the App-wide events
* Events for individual social sites should be registered within their own namespace
*/
class RegisterEvents {

	public function __construct()
	{
		// Run an Import Manully
		add_action( 'wp_ajax_nopriv_social_curator_manual_import', array($this, 'importWasRun' ));
		add_action( 'wp_ajax_social_curator_manual_import', array($this, 'importWasRun' ));
	}

	/**
	* Request an Instagram Token
	*/
	public function importWasRun()
	{
		new RunManualImport;
	}

}