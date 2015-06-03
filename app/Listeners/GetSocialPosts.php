<?php namespace SocialCurator\Listeners;

use SocialCurator\Entities\PostType\SocialPost\SocialPostPresenter;

/**
* Get an array of social posts and return an AJAX response
*/
class GetSocialPosts extends ListenerBase {

	/**
	* Social Post Presenter
	* @var SocialCurator\Entities\PostType\SocialPost\SocialPostPresenter
	*/
	private $presenter;

	/**
	* Post IDs to Retrieve
	*/
	private $post_ids;

	/**
	* Post Data Array to Return
	* @var array
	*/
	private $posts = array();

	/**
	* Post Status to Query
	* @var string
	*/
	private $status;


	public function __construct()
	{
		parent::__construct();
		$this->presenter = new SocialPostPresenter;
		$this->setPostIDs();
		$this->setStatus();
		$this->getPosts();
		$this->sendSuccess();
	}

	/**
	* Validate and Set Post IDs array
	*/
	private function setPostIDs()
	{
		if ( !isset($_POST['posts']) && !is_array($_POST['posts']) ) return $this->sendError(__('Invalid Post IDs', 'socialcurator'));
		$this->post_ids = $_POST['posts'];
	}

	/**
	* Set the Status
	*/
	private function setStatus()
	{
		$allowed = array('pending', 'publish', 'draft');
		if ( !isset($_POST['status']) ) return $this->status = 'pending';
		$this->status = $_POST['status'];
	}

	/**
	* Get the Posts
	* @todo add offset param
	*/
	private function getPosts()
	{
		$pq = new \WP_Query(array(
			'post_type' => 'social-post',
			'post__in' => $this->post_ids,
			'ignore_sticky_posts' => 1,
			'posts_per_page' => -1,
			'post_status' => $this->status,
		));
		$c = 0;
		if ( $pq->have_posts() ) : while ( $pq->have_posts() ) : $pq->the_post();
			$id = get_the_id();
			$this->posts[$c]['id'] = $id;
			$this->posts[$c]['content'] = apply_filters('the_content', get_the_content());
			$this->posts[$c]['site'] = get_post_meta($id, 'social_curator_site', true);
			$this->posts[$c]['profile_image'] = $this->presenter->getAvatar($id);
			$this->posts[$c]['profile_link'] = $this->presenter->getProfileLink($id);
			$this->posts[$c]['status'] = get_post_status($id);
			$this->posts[$c]['profile_name'] = $this->presenter->getProfileName($id);
			$this->posts[$c]['icon_link'] = $this->presenter->getIconLink($id);
			$this->posts[$c]['date'] = get_the_time('D, M jS - g:ia');
			$this->posts[$c]['video_url'] = get_post_meta($id, 'social_curator_video_url', true);
			$this->posts[$c]['type'] = get_post_meta($id, 'social_curator_type', true);
			$this->posts[$c]['thumbnail'] = $this->presenter->getThumbnailURL($id);
		$c++; endwhile; endif; wp_reset_postdata();
	}

	/**
	* Send a Success Response
	* @return JSON
	*/
	protected function sendSuccess($message = null)
	{
		return wp_send_json(array('status' => 'success', 'posts' => $this->posts));
	}


}