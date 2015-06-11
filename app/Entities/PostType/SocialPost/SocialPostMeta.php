<?php 

namespace SocialCurator\Entities\PostType\SocialPost;

use SocialCurator\Config\SettingsRepository;
use SocialCurator\Config\SupportedSites;
use SocialCurator\Entities\PostType\SocialPost\SocialPostPresenter;
use SocialCurator\Helpers;

/**
* Register the Custom Meta Fields for Social Posts
*/
class SocialPostMeta 
{

	/**
	* Settings Repository
	* @var SocialCurator\Config\SettingsRepository
	*/
	private $settings_repo;

	/**
	* Supported Sites
	* @var SocialCurator\Config\SupportedSites
	*/
	private $supported_sites;

	/**
	* Meta Data
	*/
	private $meta;

	/**
	* Fields
	*/
	public $fields;

	/**
	* Presenter
	* @var
	*/
	private $presenter;


	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		$this->supported_sites = new SupportedSites;
		$this->presenter = new SocialPostPresenter;
		$this->setFields();
		add_action( 'add_meta_boxes', array( $this, 'metaBox' ));
		add_action( 'save_post', array($this, 'savePost' ));
	}

	/**
	* Register the Meta Box
	*/	
	public function metaBox()
	{
		add_meta_box( 
			'social-curator-meta-box', 
			'Social Post', 
			array($this, 'displayMeta'), 
			'social-post', 
			'normal', 
			'high' 
    	);
	}

	/**
	* Set the Fields for use in custom meta
	*/
	private function setFields()
	{
		$this->fields = array(
			'social_curator_site',
			'social_curator_type',
			'social_curator_original_id',
			'social_curator_link',
			'social_curator_screen_name',
			'social_curator_avatar',
			'social_curator_user_id',
			'social_curator_video_url',
			'social_curator_approved_by',
			'social_curator_approved_date',
			'social_curator_profile_url'
		);
	}

	/**
	* Set the Field Data
	*/
	private function setData($post)
	{
		foreach ( $this->fields as $field )
		{
			$this->meta[$field] = get_post_meta( $post->ID, $field, true );
		}
	}

	/**
	* Meta Boxes for Output
	*/
	public function displayMeta($post)
	{
		$this->setData($post);
		include( Helpers::view('postmeta/social-post-meta') );
	}

	/**
	* Save the custom post meta
	*/
	public function savePost( $post_id ) 
	{
		if ( get_post_type($post_id) == 'social-post' ) :
			if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
			if( !isset( $_POST['social_curator_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['social_curator_meta_box_nonce'], 'my_social_curator_meta_box_nonce' ) ) return $post_id;

			// Save Custom Fields
			foreach ( $this->fields as $key => $field )
			{
				if ( isset($_POST[$field]) && $_POST[$field] !== "" ) update_post_meta( $post_id, $field, esc_attr( $_POST[$field] ) );
			}
		endif;
	} 

}