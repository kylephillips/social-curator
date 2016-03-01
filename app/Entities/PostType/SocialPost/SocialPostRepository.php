<?php 

namespace SocialCurator\Entities\PostType\SocialPost;

use SocialCurator\Entities\PostType\SocialPost\SocialPostPresenter;

/**
* Repository for Social Posts
*/
class SocialPostRepository 
{

	/**
	* Social Post Presenter
	* @var SocialCurator\Entities\PostType\SocialPost\SocialPostPresenter
	*/
	private $presenter;

	public function __construct()
	{
		$this->presenter = new SocialPostPresenter;
	}

	/**
	* Does the post already exist?
	* @return boolean
	*/
	public function exists($site, $original_id)
	{
		$pq = new \WP_Query(array(
			'post_type' => 'social-post',
			'posts_per_page' => -1,
			'post_status' => array('publish', 'pending', 'draft', 'trash'),
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'social_curator_site',
					'value' => $site,
					'compare' => '='
				),
				array(
					'key' => 'social_curator_original_id',
					'value' => $original_id,
					'compare' => '='
				)
			)
		));
		return ( $pq->have_posts() ) ? true : false;
	}

	/**
	* Get the number of unmoderated posts
	*/
	public function getUnmoderatedCount()
	{
		$pq = new \WP_Query(array(
			'post_type' => 'social-post',
			'posts_per_page' => -1,
			'post_status' => array('pending', 'draft'),
			'fields' => 'ids'
		));
		if ( $pq->have_posts() ) :
			return count($pq->posts);
		else :
			return 0;
		endif;
	}

	/**
	* Get Posts by ID(s)
	* @param int|array Post ID(s)
	* @param str|array Post Statuses
	* @return Array of Posts for JSON response
	*/
	public function getPostsArray($query_params)
	{
		$args = array(
			'post_type' => 'social-post',
			'posts_per_page' => -1,
			'ignore_sticky_posts' => 1,
			'posts_per_page' => -1,
			'orderby' => 'date',
			'order' => 'DESC'
		);
		
		// Basic Params
		if ( isset($query_params['post__in']) ) $args['post__in'] = $ids;
		
		$args['post_status'] = ( isset($query_params['post_status']) ) ? $query_params['post_status'] : 'publish';
		if ( !is_user_logged_in() ) $args['post_status'] = 'publish';
		
		// Set Site Field Param
		if ( isset($query_params['site']) ){
			$args['meta_key'] = 'social_curator_site';
			$args['meta_value'] = $query_params['site'];
		}

		if ( isset($query_params['offset']) && isset($query_params['number']) ){
			$args['posts_per_page'] = $query_params['number'];
			$args['offset'] = $query_params['offset'];
		}

		// Only Posts with Thumbnails
		if ( isset($query_params['thumbnailsonly']) ){
			if ( $query_params['thumbnailsonly'] !== 'false') $args['meta_query'] = array(array('key' => '_thumbnail_id'));
		}

		$pq = new \WP_Query(apply_filters('social_curator_posts', $args));
		$thumbnail_size = ( isset($query_params['thumbnailsize']) ) ? $query_params['thumbnailsize'] : 'full';

		$posts = array();
		if ( $pq->have_posts() ) : $c = 0; while ( $pq->have_posts() ) : $pq->the_post();
			$id = get_the_id();
			$posts[$c]['id'] = $id;
			$posts[$c]['content'] = apply_filters('the_content', get_the_content());
			$posts[$c]['site'] = get_post_meta($id, 'social_curator_site', true);
			$posts[$c]['profile_image'] = $this->presenter->getAvatar($id);
			$posts[$c]['profile_image_link'] = $this->presenter->getAvatarURL($id);
			$posts[$c]['profile_link'] = $this->presenter->getProfileLink($id);
			$posts[$c]['profile_name'] = $this->presenter->getProfileName($id);
			$posts[$c]['icon_link'] = $this->presenter->getIconLink($id);
			$posts[$c]['date'] = get_the_date();
			$posts[$c]['video_url'] = get_post_meta($id, 'social_curator_video_url', true);
			$posts[$c]['type'] = get_post_meta($id, 'social_curator_type', true);
			$posts[$c]['link'] = get_post_meta($id, 'social_curator_link', true);
			$posts[$c]['thumbnail'] = $this->presenter->getThumbnailURL($id, $thumbnail_size);
			$posts[$c]['original_id'] = get_post_meta($id, 'social_curator_original_id', true);
			if ( is_user_logged_in() ){
				$posts[$c]['status'] = get_post_status($id);
				$posts[$c]['approved_by'] = get_post_meta($id, 'social_curator_approved_by', true);
				$posts[$c]['approved_date'] = get_post_meta($id, 'social_curator_approved_date', true);
				$posts[$c]['edit_link'] = get_edit_post_link($id);
			}
		$c++; endwhile; endif; wp_reset_postdata();
		return $posts;
	}

	/**
	* Get number of social posts in the trash
	*/
	public function trashCount()
	{
		global $wpdb;
		$table = $wpdb->prefix . 'posts';
		$count = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE `post_type` = 'social-post' AND `post_status` = 'trash'");
		return $count;
	}

}