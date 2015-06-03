<?php namespace SocialCurator\Import;

use SocialCurator\Import\ImageImporter;
use SocialCurator\Config\SupportedSites;

/**
* Import a Single Post from formatted array
*/
class PostImporter {

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
	* @var int
	*/
	private $post_id;

	/**
	* Meta Fields to Import
	* @var array
	*/
	private $meta;

	/**
	* Image Importer
	* @var SocialCurator\Import\ImageImporter
	*/
	private $image_importer;

	/**
	* Supported Sites
	* @var SocialCurator\Config\SupportedSites
	*/
	private $supported_sites;

	public function __construct()
	{
		$this->image_importer = new ImageImporter;
		$this->supported_sites = new SupportedSites;
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
			'video_url' => 'social_curator_video_url'
		);
	}


	/**
	* Create the Post
	*/
	public function createPost($site, $post_data)
	{
		$this->site = $site;
		$this->post_data = $post_data;
		$imported = array(
			'post_type' => 'social-post',
			'post_content' => $this->post_data['content'],
			'post_status' => 'pending',
			'post_title' => $this->site . ' - ' . $this->post_data['id'],
			'post_date' => date('Y-m-d H:i:s', strtotime($this->post_data['date']))
		);
		$this->post_id = wp_insert_post($imported);
		$this->attachMeta();
		$this->saveAvatar();
	}


	/**
	* Attach Meta Fields
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
		$avatar_image = $this->image_importer->run('social-curator/avatars', $this->post_data['profile_image'], $this->post_data['screen_name']);
		add_post_meta($this->post_id, 'social_curator_avatar', $avatar_image);
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
	private function isTrash()
	{
		
	}


}