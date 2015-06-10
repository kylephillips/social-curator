<?php namespace SocialCurator\Notifications;

/**
* Email the Admin when an import fails
*/
class FailedImportNotification extends NotificationBase {

	public function __construct($message)
	{
		parent::__construct('failed_import', $message);
		$this->setUpEmail();
	}

	/**
	* Setup and send the message
	*/
	protected function setUpEmail()
	{
		$this->message = __('Error Message: ') . $this->message;
		$this->send(__('There was an error importing social posts', 'socialcurator'));
	}

}