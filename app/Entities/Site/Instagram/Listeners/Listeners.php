<?php namespace SocialCurator\Entities\Site\Instagram\Listeners;

use SocialCurator\Entities\Site\Instagram\Events\InstagramTokenRequest;
use SocialCurator\Entities\Site\Instagram\Events\removeInstagramAuth;

class Listeners {

	public function __construct()
	{
		// Instagram Token Request
		add_action( 'wp_ajax_nopriv_instagram_token_request', array($this, 'instagramTokenRequest' ));
		add_action( 'wp_ajax_instagram_token_request', array($this, 'instagramTokenRequest' ));

		// Remove Auth Credentials
		add_action( 'wp_ajax_nopriv_instagram_remove_auth', array($this, 'removeInstagramAuth' ));
		add_action( 'wp_ajax_instagram_remove_auth', array($this, 'removeInstagramAuth' ));
	}

	/**
	* Request an Instagram Token
	*/
	public function instagramTokenRequest()
	{
		new InstagramTokenRequest;
	}

	/**
	* Remove Authorization Credentials
	*/
	public function removeInstagramAuth()
	{
		new removeInstagramAuth();
	}

}