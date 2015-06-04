<div class="wrap social-curator-curation-page">
	
	<h2>
		<?php _e('Social Curator', 'socialcurator'); ?>
		<div class="social-curator-curation-loading-indicator" data-curation-loader>
			<img src="<?php echo SocialCurator\Helpers::plugin_url(); ?>/assets/images/loading-admin.gif" />
		</div>
	</h2>
	
	<div class="social-curator-run-import social-curator-alert">
		<p>
			<a href="#" data-social-curator-manual-import class="button button-primary"><?php _e('Run Import', 'socialcurator'); ?></a>
			<?php _e('Last Import', 'socialcurator'); ?>: <span data-social-curator-last-import><?php echo $this->settings_repo->lastImport('M jS'); ?> at <?php echo $this->settings_repo->lastImport('g:ia'); ?></span><br>
			<strong><span data-social-curator-unmoderated-count><?php echo $this->social_post_repo->getUnmoderatedCount(); ?></span> <?php _e('awaiting moderation', 'socialcurator'); ?></strong></p>
		</p>
	</div><!-- .social-curator-import-form -->

	<div class="social-curator-alert-success social-curator-alert" data-social-curator-import-count style="display:none;">
		<span>0</span> <?php _e('New Posts Imported', 'socialcurator'); ?>
		<a href="#" class="close" data-dismiss="alert">&times;</a>
	</div>

	<!-- TODO: add masonry -->
	<div class="social-curator-post-grid">

	</div><!-- .social-curator-post-grid -->

	
	<!-- Template used for cloning / appending new posts -->
	<div class="post-template" data-post-template>
		<?php include('single-post.php'); ?>
	</div><!-- .post-template -->

</div><!-- .wrap -->