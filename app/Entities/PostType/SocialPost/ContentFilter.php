<?php 

namespace SocialCurator\Entities\PostType\SocialPost;

use SocialCurator\Config\SupportedSites;

class ContentFilter 
{

	/**
	* Supported Sites
	* @var SocialCurator\Config\SupportedSites
	*/
	private $supported_sites;

	public function __construct()
	{
		$this->supported_sites = new SupportedSites;
		add_filter('the_content', array($this, 'parseContent'));
	}

	/**
	* Parse the content for hashtags/links
	*/
	public function parseContent($content)
	{
		global $post;
		if ( $post->post_type !== 'social-post' ) return $content;
		$site = get_post_meta($post->ID, 'social_curator_site', true);
		if ( !$site ) return $content;
		return $this->parse($site, $content);
	}

	/**
	* Parse the Content using site parse class
	*/
	private function parse($site, $content)
	{
		$parse_class = 'SocialCurator\Entities\Site\\' . $this->supported_sites->getKey($site, 'namespace') . '\Feed\Parser';
		if ( !class_exists($parse_class) ) return $content;
		$parser = new $parse_class;
		return $parser->parse($content);
	}

}