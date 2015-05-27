<?php namespace SocialCurator\Entities\PostType\SocialPost;

use SocialCurator\Helpers;

/**
* Register the Custom Meta Fields for Social Posts
*/
class SocialPostMeta {

	public function __construct()
	{
		add_action( 'add_meta_boxes', array( $this, 'metaBox' ));
	}


	/**
	* Register the Meta Box
	*/	
	public function metaBox()
	{
		add_meta_box( 
			'social-curator-meta-box', 
			'Social Post Data', 
			array($this, 'displayMeta'), 
			'social-post', 
			'normal', 
			'high' 
    	);
	}


	/**
	* Meta Boxes for Output
	*/
	public function displayMeta($post)
	{
		// $this->setData($post);
		include( Helpers::view('postmeta/social-post-meta') );
	}

}