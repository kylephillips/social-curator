<div class="wrap social-curator-curation-page">
	
	<h2>
		<?php _e('Social Curator', 'socialcurator'); ?>
		<div class="social-curator-curation-loading-indicator" data-curation-loader>
			<img src="<?php echo SocialCurator\Helpers::plugin_url(); ?>/assets/images/loading-admin.gif" />
		</div>
	</h2>
	
	<div class="social-curator-run-import">
		<p>
			<strong><?php _e('Last Import', 'socialcurator'); ?>:</strong> <span data-social-curator-last-import><?php echo $this->settings_repo->lastImport('M jS'); ?> at <?php echo $this->settings_repo->lastImport('g:ia'); ?></span>
			<a href="#" data-social-curator-manual-import class="button button-primary"><?php _e('Run Import', 'socialcurator'); ?></a>
		</p>
	</div><!-- .social-curator-import-form -->

	<div class="social-curator-alert-success social-curator-alert" data-social-curator-import-count style="display:none;">
		<span>0</span> <?php _e('New Posts Imported', 'socialcurator'); ?>
		<a href="#" class="close" data-dismiss="alert">&times;</a>
	</div>

</div><!-- .wrap -->