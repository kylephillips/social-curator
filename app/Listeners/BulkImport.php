<?php

namespace SocialCurator\Listeners;

use SocialCurator\Config\SupportedSites;
use SocialCurator\Import\SingleFeedImporter;

/**
* Run a bulk import
*/
class BulkImport extends ListenerBase
{
	/**
	* Post Importer
	* @var SocialCurator\Import\SingleFeedImporter
	*/
	private $importer;

	/**
	* Supported Sites
	* @var SocialCurator\Config\SupportedSites
	*/
	private $supported_sites;

	/**
	* Post IDs to Import
	* @var array
	*/
	private $post_ids;

	/**
	* Site to Import from
	*/
	private $site;

	/**
	* Import Count
	* @var int
	*/
	private $import_count = 0;

	/**
	* Import Errors
	*/
	private $errors = array();

	public function __construct()
	{
		parent::__construct();
		$this->importer = new SingleFeedImporter;
		$this->supported_sites = new SupportedSites;
		$this->setData();
		$this->runImport();
		$this->sendSuccess('Testing');
	}

	/**
	* Set the Form Data
	*/
	private function setData()
	{
		if ( !isset($_POST['post_ids']) || $_POST['post_ids'] == "" ) return $this->sendError(__('At least one post ID is required.', 'socialcurator'));
		$this->site = sanitize_text_field($_POST['site']);
		
		// Split post ids into array
		$post_ids = str_replace(' ', '', sanitize_text_field($_POST['post_ids']));
		$this->post_ids = explode(',', $post_ids);
	}

	/**
	* Run the Import
	*/
	private function runImport()
	{
		foreach($this->post_ids as $id){
			try {
				$this->importPost($id);
				$this->import_count = $this->import_count + 1;
			} catch ( \Exception $e ){
				$this->errors[] = $e->getMessage();
			}
		}
	}

	/**
	* Import a Single Post
	* @param int $id - the post ID to import
	*/
	private function importPost($id)
	{
		// Set the Feed Class
		$feed_class = $this->supported_sites->getKey($this->site, 'namespace') . '\Feed\Feed';
		if ( !class_exists($feed_class) ) return $this->sendError(__('There was an error connecting to the feed.', 'socialcurator'));

		$feed = new $feed_class('single', $id);
		$feed_array = array($feed->getFeed());
		$this->importer->import($this->site, $feed_array, true);
	}

	/**
	* Send Success Response
	*/
	protected function sendSuccess($message = null)
	{
		wp_send_json(array(
			'status' => 'success',
			'import_count' => $this->import_count,
			'errors' => $this->errors
		));
	}
}