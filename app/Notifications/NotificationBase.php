<?php namespace SocialCurator\Notifications;

use SocialCurator\Config\SettingsRepository;

/**
* Base class for extending email notifications
*/
abstract class NotificationBase {

	/**
	* Settings Repository
	* @var SocialCurator\Config\SettingsRepository
	*/
	protected $settings_repo;

	/**
	* Emails to send to
	* @var array
	*/
	protected $emails;

	/**
	* Message to Send
	*/
	protected $message;

	/**
	* Transient
	*/
	protected $transient_status;

	/**
	* Notification Type
	*/
	protected $type;

	public function __construct($type, $message)
	{
		$this->type = $type;
		$this->message = $message;
		$this->settings_repo = new SettingsRepository;
		$this->setEmails();
		$this->getTransient();
	}

	/**
	* Set the notification emails
	*/
	protected function setEmails()
	{
		$this->emails = $this->settings_repo->notificationEmails();
	}

	/**
	* Set the transient status
	*/
	protected function getTransient()
	{
		$this->transient_status = ( get_transient('social_curator_' . $this->type . '_notification') ) ? true : false;
	}

	/**
	* Set the new transient
	* Ensures notifcations only go out once a day
	*/
	protected function setTransient()
	{
		set_transient('social_curator_' . $this->type . '_notification', 'true', 86400);
	}

	/**
	* Send the Notification
	*/
	protected function send($subject)
	{
		if ( $this->transient_status ) return;

		$from = $this->settings_repo->fromName();
		if ( !$from ) return;
		
		$from_email = $this->settings_repo->fromEmail();
		if ( !$from_email ) return;

		$headers = 'From: ' . $from . ' <' . $from_email . '>' . "\r\n";
		wp_mail($this->emails, $subject, $this->message, $headers);
		$this->setTransient();
	}

}