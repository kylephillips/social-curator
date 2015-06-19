<h3><?php _e('Bulk Import Posts', 'socialcurator'); ?></h3>
<div class="social-curator-bulk-import">
	<p>
		<label><?php _e('Site', 'socialcurator'); ?></label>
		<select data-bulk-import-site>
		<?php 
		foreach ( $this->supported_sites->getSites() as $key => $site ){
			echo '<option value="' . $key . '">' . $site['name'] . '</option>';
		}
		?>
		</select>
	</p>
	<p>
		<label><?php _e('Post IDs', 'socialcurator'); ?></label>
		<?php _e('Enter post IDs as a comma-separated list. IDs must be from the selected site.', 'socialcurator'); ?>
		<textarea data-bulk-import-ids></textarea>
	</p>
	<p>
		<button class="button button-primary"><?php _e('Import', 'socialcurator'); ?></button>
	</p>
	<div class="social-curator-button-loading-indicator" data-log-clear-loader>
		<img src="<?php echo SocialCurator\Helpers::plugin_url(); ?>/assets/images/loading-admin.gif" />
	</div>
</div><!-- .social-curator-bulk-import -->