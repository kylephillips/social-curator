<?php 

namespace SocialCurator\Entities\PostType\SocialPost;

/**
* Register the Social Post Post Type
*/
class RegisterSocialPost 
{

	public function __construct()
	{
		add_action('init', array($this, 'register'));
	}

	public function register()
	{
		$labels = array(
			'name' => __('Social Posts', 'socialcurator'),  
			'singular_name' => __('Social Post', 'socialcurator'),
			'add_new_item'=> __('Add Social Post', 'socialcurator'),
			'edit_item' => __('Edit Social Post', 'socialcurator'),
			'view_item' => __('View Social Post', 'socialcurator')
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'show_ui' => true,
			'exclude_from_search' => false,
			'capability_type' => 'post',
			'hierarchical' => false,
			'has_archive' => true,
			'menu_position' => 20,
			'menu_icon' => 'dashicons-share-alt2',
			'supports' => array('title', 'editor', 'thumbnail'),
			'rewrite' => array('slug' => 'social-post', 'with_front' => false)
		);
		register_post_type( 'social-post' , $args );
	}

}