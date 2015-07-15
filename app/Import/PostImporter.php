<?php 

namespace SocialCurator\Import;

use SocialCurator\Import\AvatarImporter;
use SocialCurator\Import\MediaImporter;
use SocialCurator\Config\SettingsRepository;
use SocialCurator\Entities\PostType\SocialPost\SocialPostRepository;

/**
* Import a Single Post from formatted array
*/
class PostImporter 
{

	/**
	* Site the feed is from
	* @var string
	*/
	private $site;

	/**
	* Single Post Feed Item to Import
	* @var array
	*/
	private $post_data;

	/**
	* New Post ID
	* @var int - the Newly Imported WP Post ID
	*/
	private $post_id;

	/**
	* Meta Fields to Import
	* @var array
	*/
	private $meta;

	/**
	* Avatar Importer
	* @var SocialCurator\Import\AvatarImporter
	*/
	private $avatar_importer;

	/**
	* Media Importer
	* @var SocialCurator\Import\MediaImporter
	*/
	private $media_importer;

	/**
	* Settings Repository
	* @var SocialCurator\Config\SettingsRepository
	*/
	private $settings_repo;

	/**
	* Social Post Repository
	* @var SocialCurator\Entities\PostType\SocialPost\SocialPostRepository
	*/
	private $social_post_repo;

	public function __construct()
	{
		$this->avatar_importer = new AvatarImporter;
		$this->media_importer = new MediaImporter;
		$this->settings_repo = new SettingsRepository;
		$this->setMeta();
	}

	/**
	* Set the Meta Fields to import
	* key = array key in feed item
	* value = meta name
	*/ 
	private function setMeta()
	{
		$this->meta = array(
			'id' => 'social_curator_original_id',
			'type' => 'social_curator_type',
			'user_id' => 'social_curator_user_id',
			'screen_name' => 'social_curator_screen_name',
			'link' => 'social_curator_link',
			'video_url' => 'social_curator_video_url',
			'profile_url' => 'social_curator_profile_url'
		);
	}

	/**
	* Create the Post
	*/
	public function createPost($site, $post_data, $checktrash = true)
	{
		$this->site = $site;
		$this->post_data = $post_data;
		if ( $checktrash )	{
			if ( $this->isTrashed() ) return false;
		}
		$status = $this->settings_repo->importStatus();
		$imported = array(
			'post_type' => 'social-post',
			'post_content' => $this->post_data['content'],
			'post_status' => $status,
			'post_title' => $this->site . ' - ' . $this->post_data['id'],
			'post_date' => date('Y-m-d H:i:s', intval($this->post_data['date'])),
			'post_date_gmt' => date('Y-m-d H:i:s', intval($this->post_data['date'])),
		);
		$this->post_id = wp_insert_post($imported);
		$this->attachMeta();
		$this->saveAvatar();
		$this->saveThumbnail();
		if ( $status == 'publish' ) $this->saveApprovedBy();
		return true;
	}

	/**
	* Attach Meta Fields to the newly created post
	*/
	private function attachMeta()
	{
		add_post_meta($this->post_id, 'social_curator_site', $this->site);
		foreach($this->meta as $key => $fieldname){
			if ( isset($this->post_data[$key]) && !is_null($this->post_data[$key]) ) 
				add_post_meta($this->post_id, $fieldname, $this->post_data[$key]);
		}
	}

	/**
	* Save User Avatars
	*/
	private function saveAvatar()
	{
		if ( is_null($this->post_data['profile_image'] )) return; 
		$avatar_image = $this->avatar_importer->run('social-curator/avatars', $this->post_data['profile_image'], $this->post_data['screen_name'], $this->post_id);
		add_post_meta($this->post_id, 'social_curator_avatar', $avatar_image);
	}

	/**
	* Save the Thumbnail
	*/
	private function saveThumbnail()
	{
		if ( !$this->post_data['image'] ) return;
		$attachment_id = $this->media_importer->runImport($this->post_data['image'], $this->post_id);
		add_post_meta($this->post_id, '_thumbnail_id', $attachment_id);
	}

	/**
	* Get the New Post ID
	*/
	public function getID()
	{
		return $this->post_id;
	}

	/**
	* Is the Post Logged as Trash?
	* @return boolean
	*/
	private function isTrashed()
	{
		global $wpdb;
		$table = $wpdb->prefix . 'social_curator_trashed_posts';
		$site = $this->site;
		$post_id = $this->post_data['id'];
		$row = $wpdb->get_row("SELECT * FROM $table WHERE `site`= '$site' AND `post_id`= '$post_id'");
		return ( $row ) ? true : false;
	}

	/**
	* Save Approved by if the Import Status is set as published
	*/
	private function saveApprovedBy()
	{
		update_post_meta($this->post_id, 'social_curator_approved_by', __('Social Curator Importer', 'socialcurator'));
		update_post_meta($this->post_id, 'social_curator_approved_date', date('Y-m-d H:m:s'));
	}

}