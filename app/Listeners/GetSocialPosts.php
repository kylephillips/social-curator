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
		$this->getPosts();
		$this->sendSuccess();
	}

	/**
	* Set the Query Parameters
	*/
	private function setQueryParams()
	{
		if ( isset($_POST['posts']) ) $this->query_params['posts__in'] = $_POST['posts'];
		if ( isset($_POST['status']) ) $this->query_params['post_status'] = $_POST['status'];
		if ( isset($_POST['site']) ) $this->query_params['site'] = $_POST['site'];
		$this->query_params['offset'] = ( isset($_POST['offset']) ) ? intval($_POST['offset']) : 0;
	}

	/**
	* Get the Posts
	* @todo add offset param
	*/
	private function getPosts()
	{
		$this->posts = $this->social_post_repo->getPostsArray($this->query_params);
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