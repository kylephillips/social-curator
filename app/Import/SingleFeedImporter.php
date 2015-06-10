<?php namespace SocialCurator\Import;

use SocialCurator\Import\PostImporter;

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
	* Array of new post ids
	* @var array
	*/
	private $post_ids;

	public function __construct()
	{
		$this->post_importer = new PostImporter;
	}

	/**
	* Run the import
	* @param string $site
	* @param array $feed
	*/
	public function import($site, $feed)
	{
		foreach($feed as $feed_item){
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