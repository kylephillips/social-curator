<?php 

namespace SocialCurator\Listeners;

/**
* Empty the Social Posts Trash
*/
class EmptyTrash extends ListenerBase 
{

	public function __construct()
	{
		parent::__construct();
		$this->emptyTrash();
	}

	private function emptyTrash()
	{
		if ( !current_user_can('delete_others_posts') ) return $this->sendError(__('Capabilities error', 'socialcurator'));
		$pq = new \WP_Query(array(
			'post_type' => 'social-post',
			'posts_per_page' => -1,
			'post_status' => 'trash'
		));
		if ( $pq->have_posts() ) : while ( $pq->have_posts() ) : $pq->the_post();
			wp_delete_post(get_the_id(), true);
		endwhile; endif; wp_reset_postdata();
		$this->sendSuccess('done');
	}

}