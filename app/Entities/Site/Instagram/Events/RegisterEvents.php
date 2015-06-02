<?php namespace SocialCurator\Entities\Site\Instagram\Events;

use SocialCurator\Entities\Site\Instagram\Listeners\InstagramTokenRequest;
use SocialCurator\Entities\Site\Instagram\Listeners\removeInstagramAuth;

class RegisterEvents {

	public function __construct()
	{
		// Instagram Token Request
		add_action( 'wp_ajax_nopriv_instagram_token_request', array($this, 'instagramTokenWasRequested' ));
		add_action( 'wp_ajax_instagram_token_request', array($this, 'instagramTokenWasRequested' ));

		// Remove Auth Credentials
		add_action( 'wp_ajax_nopriv_instagram_remove_auth', array($this, 'instagramAuthWasRemoved' ));
		add_action( 'wp_ajax_instagram_remove_auth', array($this, 'instagramAuthWasRemoved' ));
	}

	/**
	* Request an Instagram Token
	*/
	public function instagramTokenWasRequested()
	{
		new InstagramTokenRequest;
	}

	/**
	* Remove Authorization Credentials
	*/
	public function instagramAuthWasRemoved()
	{
		new removeInstagramAuth();
	}

}