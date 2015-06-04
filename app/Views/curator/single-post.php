<div class="social-curator-post-grid-single">
	<div class="social-curator-post-head">
		<span data-icon-link class="social-curator-post-link"><?php echo $post['icon_link']; ?></span>
		<img src="<?php echo $post['profile_image_link']; ?>" data-profile-image class="social-curator-profile-image">
		<p>By <strong><a href="<?php echo $post['profile_link']; ?>" data-profile-link target="_blank"><span data-profile-name><?php echo $post['profile_name']; ?></span></a></strong><br>on <span data-date><?php echo $post['date']; ?></span></p>
	</div><!-- .post-head -->
	
	<?php if ( $post['thumbnail'] ) : ?>
	<div class="social-curator-post-image" data-thumbnail>
		<img src="<?php echo $post['thumbnail']; ?>" />
	</div>
	<?php endif; ?>
	
	<?php if ( $post['content'] ) : ?>
	<div class="social-curator-post-content" data-post-content>
		<?php echo $post['content']; ?>
	</div>
	<?php endif; ?>

	<?php if ( $post['approved_by'] ) : ?>
	<div class="social-curator-alert-success">
		<?php echo __('Approved by', 'socialcurator') . ' ' . $post['approved_by'] . ' ' . __('on', 'socialcurator') . ' ' . $post['approved_date']; ?>
	</div>
	<?php else : ?>
	<div class="social-curator-status-buttons">
		<a href="#" data-trash-post data-post-id="<?php echo $post['id']; ?>" class="social-curator-trash"><i class="social-curator-icon-bin2"></i><?php _e('Trash', 'socialcurator'); ?></a>
		<a href="#" data-approve-post data-post-id="<?php echo $post['id']; ?>" class="social-curator-approve"><?php _e('Approve', 'socialcurator'); ?><i class="social-curator-icon-checkmark"></i></a>
	</div>
	<?php endif; ?>
</div><!-- .social-curator-post -->