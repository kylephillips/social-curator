<?php 

namespace SocialCurator\Entities\PostType\SocialPost;

use SocialCurator\Config\SupportedSites;
use SocialCurator\Entities\PostType\SocialPost\SocialPostPresenter;

/**
* Change the default WP manage columns to include custom data
*/
class SocialPostColumns 
{

	/**
	* Supported Sites
	* @var SocialCurator\Config\SupportedSites
	*/
	private $supported_sites;

	/**
	* Social Post Presenter
	* @var SocialCurator\Entities\PostType\SocialPost\SocialPostPresenter
	*/
	private $presenter;

	public function __construct()
	{
		$this->supported_sites = new SupportedSites;
		$this->presenter = new SocialPostPresenter;
		add_filter('manage_edit-social-post_columns', array($this, 'setColumns'));
		add_action('manage_social-post_posts_custom_column', array($this, 'columnData'), 10, 2);
	}

	/**
	* Set the Custom Columns
	* @param array $columns
	*/
	public function setColumns($columns)
	{
		unset($columns['title']);
		unset($columns['date']);
		$columns['user'] = __('User', 'socialcurator');
		$columns['content'] = __('Post', 'socialcurator');
		$columns['media'] = __('Media', 'socialcurator');
		if ( current_user_can('edit_others_posts') ) $columns['moderate'] = __('Moderate', 'socialcurator');
		$columns['date'] = __('Date', 'socialcurator');
		return $columns;
	}

	/**
	* Set the Column Row Data
	* @param string $column
	* @param ing $post_id
	*/
	public function columnData($column, $post_id)
	{
		if ( $column == 'user' ) return $this->user($post_id);
		if ( $column == 'content' ) return $this->content($post_id);
		if ( $column == 'media' ) return $this->media($post_id);
		if ( $column == 'moderate' ) return $this->moderate($post_id);
	}

	/**
	* User Column Display
	* @param int $post_id
	*/
	private function user($post_id)
	{
		$out = '<div class="social-curator-column-user">';
		$out .= $this->presenter->getAvatar($post_id);
		$out .= '<p><strong>' . $this->presenter->getProfileName($post_id) . '</strong><br><a href="' . get_edit_post_link($post_id) . '">' . __('Edit', 'socialcurator') . '</a> | <a href="' . get_delete_post_link($post_id) . '">' . __('Trash', 'socialcurator');
		if ( isset($_GET['post_status']) && $_GET['post_status'] == 'trash' ){
			$_wpnonce = wp_create_nonce( 'untrash-post_' . $post_id );
			$url = admin_url( 'post.php?post=' . $post_id . '&action=untrash&_wpnonce=' . $_wpnonce );
			$out .= ' | <a href="' . $url . '">' . __('Restore', 'socialcurator') . '</a>';
		}
		$out .= '</p>';
		$out .= '</div>';
		echo $out;
	}

	/**
	* Content Column Display
	* @param int $post_id
	*/
	private function content($post_id)
	{
		$site = get_post_meta($post_id, 'social_curator_site', true);
		$out = '<div class="social-curator-column-content">';
		$out .= '<i class="' . $this->supported_sites->getKey($site, 'icon_class') . '"></i>';
		$out .= get_the_content();
		$out .= '</div>';
		echo $out;
	}

	/**
	* Media Column Display
	* @param int $post_id
	*/
	private function media($post_id)
	{
		$out = '<div class="social-curator-column-media">';
		if ( has_post_thumbnail($post_id) ){
			$out .= get_the_post_thumbnail($post_id, 'thumbnail');
		}
		$out .= '</div>';
		echo $out;
	}

	/**
	* Moderate Column Display
	* @param int $post_id
	*/
	private function moderate($post_id)
	{
		$status = get_post_status($post_id);
		$out = '<div class="social-curator-column-moderate">';
		$out .= '<select data-social-curator-moderate-select>';
		
		// Pending
		$out .= '<option value="pending"';
		if ( $status == 'draft' || $status == 'pending' ) $out .= ' selected';
		$out .= '>' . __('Pending', 'socialcurator') . '</option>';

		// Approved
		$out .= '<option value="publish"';
		if ( $status == 'publish' ) $out .= ' selected';
		$out .= '>' . __('Approved', 'socialcurator') . '</option>';

		// Trash
		$out .= '<option value="trash">' . __('Trash', 'socialcurator') . '</option>';

		$out .= '</select>';
		$out .= '<button class="button" data-social-curator-moderate-select-button data-post-id="' . $post_id . '">' . __('Update', 'socialcurator') . '</button>';
		$out .= '</div>';
		echo $out;
	}

}