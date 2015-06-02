<?php namespace SocialCurator\Listeners;

/**
* Base Event Class Handles Nonce Validation
*/
class ListenerBase {

	/**
	* Nonce
	*/
	protected $nonce;


	public function __construct()
	{
		$this->setNonce();
		$this->validateNonce();
	}

	/**
	* Set the Nonce
	*/
	protected function setNonce()
	{
		$this->nonce = sanitize_text_field($_POST['nonce']);
	}

	/**
	* Validate the Nonce
	*/
	protected function validateNonce()
	{
		if ( !wp_verify_nonce( $this->nonce, 'social-curator-nonce' ) ) 
			return $this->sendError( __('Invalid form field', 'socialcurator') );
	}

	/**
	* Send an Error Response
	* @param string $error - Error message to return
	* @return JSON
	*/
	protected function sendError($error)
	{
		return wp_send_json(array('status' => 'error', 'message' => $error ));
	}

	/**
	* Send a Success Response
	* @param string $message - Success message to return
	* @return JSON
	*/
	protected function sendSuccess($message = null)
	{
		return wp_send_json(array('status' => 'success', 'message' => $message));
	}

}