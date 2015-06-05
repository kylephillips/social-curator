<?php
$fallback = SocialCurator\Helpers::plugin_url() . '/assets/images/kickapoo-fallback.png';
?>
<div class="social-curator-post-grid-single <?php if ( $post['status'] == 'publish' ) echo 'approved'; ?>" data-post-container-id="<?php echo $post['id']; ?>">
	<div class="social-curator-post-head">
		<span data-icon-link class="social-curator-post-link"><?php echo $post['icon_link']; ?></span>
		<img src="<?php echo $post['profile_image_link']; ?>" data-profile-image class="social-curator-profile-image"  onerror="this.onerror=null;this.src='<?php echo $fallback; ?>';" />
		<p>By <strong><a href="<?php echo $post['profile_link']; ?>" data-profile-link target="_blank"><span data-profile-name><?php echo $post['profile_name']; ?></span></a></strong><br>on <span data-date><?php echo $post['date']; ?></span></p>
	</div><!-- .post-head -->
	
	<?php if ( $post['thumbnail'] ) : ?>
	<div class="social-curator-post-image" data-thumbnail>
		<?php 
		if ( $post['type'] == 'video' && $post['video_url'] ){
			$video_link = $post['video_url'];
			echo '<a href="' . $video_link . '" target="_blank">';
		}
		?>
		<img src="<?php echo $post['thumbnail']; ?>" />
		<?php if ( isset($video_link) ) echo '</a>'; ?>
	</div>
	<?php endif; ?>
	
	<?php if ( $post['content'] ) : ?>
	<div class="social-curator-post-content" data-post-content>
		<?php echo $post['content']; ?>
		<p><a href="<?php echo $post['edit_link']; ?>">(<?php _e('Edit', 'socialcurator'); ?>)</a></p>
	</div>
	<?php endif; ?>

	<?php if ( $post['approved_by'] ) : ?>
	<div class="social-curator-alert-success">
		<?php echo __('Approved by', 'socialcurator') . ' ' . $post['approved_by'] . ' ' . __('on', 'socialcurator') . ' ' . $post['approved_date']; ?>
		<?php if ( current_user_can('edit_others_posts') ) : ?>
			<br><a href="#" data-trash-post data-post-id="<?php echo $post['id']; ?>" class="unapprove-link"><?php _e('Unapprove and Trash', 'socialcurator'); ?></a>
		<?php endif; ?>
	</div>
	<?php else : ?>
	<div class="social-curator-status-buttons">
		<a href="#" data-trash-post data-post-id="<?php echo $post['id']; ?>" class="social-curator-trash"><i class="social-curator-icon-bin2"></i><?php _e('Trash', 'socialcurator'); ?></a>
		<a href="#" data-approve-post data-post-id="<?php echo $post['id']; ?>" class="social-curator-approve"><?php _e('Approve', 'socialcurator'); ?><i class="social-curator-icon-checkmark"></i></a>
	</div>
	<?php endif; ?>
</div><!-- .social-curator-post -->