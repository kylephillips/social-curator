<?php 

namespace SocialCurator\Entities\Site\Instagram\Listeners;

use SocialCurator\Listeners\ListenerBase;

/**
* Removes Instagram auth credentials
*/
class removeInstagramAuth extends ListenerBase 
{

	public function __construct()
	{
		parent::__construct();
		$this->removeAuthCredentials();
		$this->sendSuccess();
	}

	/**
	* Remove Instagram Authorization Credentials
	*/
	private function removeAuthCredentials()
	{
		$option = get_option('social_curator_site_instagram');
		unset($option['client_id']);
		unset($option['client_secret']);
		unset($option['auth_token']);
		unset($option['auth_user']);
		update_option('social_curator_site_instagram', $option);
	}

}