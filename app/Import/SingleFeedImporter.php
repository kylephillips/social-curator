<?php namespace SocialCurator\Import;

use SocialCurator\Import\PostImporter;
use SocialCurator\Entities\PostType\SocialPost\SocialPostRepository;

/**
* Import a Single Feed
*/
class SingleFeedImporter {

	/**
	* Single Post Importer
	* @var SocialCurator\Import\PostImporter
	*/
	private $post_importer;

	/**
	* Social Post Repository
	* @var SocialCurator\Entities\PostType\SocialPost\SocialPostRepository
	*/
	private $post_repo;

	/**
	* Array of new post ids
	* @var array
	*/
	private $post_ids;

	public function __construct()
	{
		$this->post_importer = new PostImporter;
		$this->post_repo = new SocialPostRepository;
	}

	/**
	* Run the import
	* @param string $site
	* @param array $feed
	* @param boolean $checktrash - override check if the post exists
	*/
	public function import($site, $feed, $checktrash = true)
	{
		foreach($feed as $feed_item){
			if ( $checktrash ) {
				if ( $this->post_repo->exists($site, $feed_item['id']) ) continue;
			}
			$importer = new PostImporter;
			if ( $importer->createPost($site, $feed_item) ){
				$this->post_ids[] = $importer->getID();
			}
		}
	}

	/**
	* Get the new post IDs
	*/
	public function getIDs()
	{
		return ( $this->post_ids ) ? $this->post_ids : array();
	}
	

}