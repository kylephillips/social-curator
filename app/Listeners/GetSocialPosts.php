<?php namespace SocialCurator\Listeners;

use SocialCurator\Entities\PostType\SocialPost\SocialPostPresenter;
use SocialCurator\Entities\PostType\SocialPost\SocialPostRepository;

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
	* Query Parameters to Pass to Repo Method
	* @var array
	*/
	private $query_params;

	/**
	* Social Post Repository
	* @var SocialCurator\Entities\PostType\SocialPost\SocialPostRepository
	*/
	private $social_post_repo;


	public function __construct()
	{
		parent::__construct();
		$this->presenter = new SocialPostPresenter;
		$this->social_post_repo = new SocialPostRepository;
		$this->setQueryParams();
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
	* Set the Query Parameters
	*/
	private function setQueryParams()
	{
		$this->query_params['post_status'] = ( !isset($_POST['status']) ) ? 'pending' : sanitize_text_field($_POST['status']);
	}

	/**
	* Get the Posts
	* @todo add offset param
	*/
	private function getPosts()
	{
		$this->posts = $this->social_post_repo->getPostsArray($this->post_ids, $this->status);
	}

	/**
	* Send a Success Response
	* @return JSON
	*/
	protected function sendSuccess($message = null)
	{
		return wp_send_json(array(
			'status' => 'success', 
			'posts' => $this->posts,
			'unmoderated_count' => $this->social_post_repo->getUnmoderatedCount()
		));
	}


}