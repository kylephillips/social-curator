<?php namespace SocialCurator\Entities\PostType\SocialPost;

/**
* Repository for Social Posts
*/
class SocialPostRepository {

	/**
	* Does the post already exist?
	* @return boolean
	*/
	public function exists($site, $original_id)
	{
		$pq = new \WP_Query(array(
			'post_type' => 'social-post',
			'posts_per_page' => -1,
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

}