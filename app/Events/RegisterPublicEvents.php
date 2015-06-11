<?php 

namespace SocialCurator\Events;

class RegisterPublicEvents 
{

	public function __construct()
	{
		// Request for a nonce
		add_action( 'wp_ajax_nopriv_social_curator_nonce', array($this, 'nonceRequested' ));
		add_action( 'wp_ajax_social_curator_nonce', array($this, 'nonceRequested' ));
	}

	/**
	* An AJAX request for a new nonce was made
	*/
	public function nonceRequested()
	{
		$nonce = wp_create_nonce( 'social-curator-nonce' );
		return wp_send_json(array('status' => 'success', 'nonce' => $nonce));
	}

}