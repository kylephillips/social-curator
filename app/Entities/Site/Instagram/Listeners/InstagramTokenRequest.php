<?php namespace SocialCurator\Entities\Site\Instagram\Listeners;

use SocialCurator\Listeners\ListenerBase;
use SocialCurator\Config\SettingsRepository;
use \GuzzleHttp\Client;

/**
* An Instagram Token was Requested
*/
class InstagramTokenRequest extends ListenerBase {

	/**
	* Form Data
	*/
	private $data;

	/**
	* Settings Repository
	*/
	private $settings_repo;


	public function __construct()
	{
		parent::__construct();
		$this->settings_repo = new SettingsRepository;
		$this->setFormData();
		$this->requestToken();
	}

	/**
	* Set Form Data
	*/
	private function setFormData()
	{
		$this->data['code'] = sanitize_text_field($_POST['code']);
		$this->data['redirect_uri'] = sanitize_text_field($_POST['redirect_uri']);
	}

	/**
	* Request Access Token
	*/
	private function requestToken()
	{
		$client = new Client();
		try {
			$response = $client->post('https://api.instagram.com/oauth/access_token', [
				'body' => [
					'client_id' => $this->settings_repo->getSiteSetting('instagram', 'client_id'),
					'client_secret' => $this->settings_repo->getSiteSetting('instagram', 'client_secret'),
					'grant_type' => 'authorization_code',
					'redirect_uri' => $this->data['redirect_uri'],
					'code' => $this->data['code']
				]
			]);
		} catch (\GuzzleHttp\Exception\ClientException $e){
			return $this->sendError($e->getMessage());
		}

		$body = json_decode($response->getBody());
		$auth_token = '';
		$auth_user = '';
		foreach($body as $key => $item){
			if ( $key == 'access_token' ) $auth_token = $item;
			if ( $key == 'user' ) $auth_user = $item->username;
		}
		$this->saveToken($auth_token, $auth_user);
		return $this->sendSuccess();
		
	}


	/**
	* Save the Access Token & User
	*/
	private function saveToken($token, $user)
	{
		$option = get_option('social_curator_site_instagram');
		$option['auth_token'] = $token;
		$option['auth_user'] = $user;
		update_option('social_curator_site_instagram', $option);
	}

}