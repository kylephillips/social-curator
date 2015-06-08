<div class="wrap social-curator-curation-page">

	<h2>
		<?php _e('Social Curator Posts', 'socialcurator'); ?>
		<div class="social-curator-curation-loading-indicator" data-curation-loader>
			<img src="<?php echo SocialCurator\Helpers::plugin_url(); ?>/assets/images/loading-admin.gif" />
		</div>
	</h2>
	
	<div class="social-curator-run-import social-curator-alert">
		<p>
			<div class="social-curator-dropdown">
				<a href="#" data-toggle="social-curator-dropdown" class="button toggle-button"><?php _e('Run an Import', 'socialcurator'); ?><i class="dashicons dashicons-arrow-down"></i></a>
				<div class="social-curator-dropdown-content">
					<ul>
						<?php
							foreach ( $this->settings_repo->getEnabledSites() as $site ){
								$sitename = $this->supported_sites->getKey($site, 'name');
								$out = '<li><button data-import-site="' . $site . '" class="site-button">' . __('Import', 'socialcurator') . ' ' . $sitename . '</button></li>';
								echo $out;
							}
						?>
						<li><a href="#" data-social-curator-import-all class="button button-primary"><?php _e('Import All', 'socialcurator'); ?></a></li>
					</ul>
				</div><!-- .social-curator-drowdown-content -->
			</div><!-- .dropdown -->

			<?php _e('Last Import', 'socialcurator'); ?>: <span data-social-curator-last-import><?php echo $this->settings_repo->lastImport('M jS'); ?> at <?php echo $this->settings_repo->lastImport('g:ia'); ?></span><br>
			<strong><span data-social-curator-unmoderated-count><?php echo $this->social_post_repo->getUnmoderatedCount(); ?></span> <?php _e('awaiting moderation', 'socialcurator'); ?></strong></p>
		</p>
	</div><!-- .social-curator-import-form -->

	<div class="social-curator-filter-grid">
		<div class="field">
			<select data-filter-status>
				<option value=""><?php _e('All Statuses', 'socialcurator'); ?></option>
				<option value="pending"><?php _e('Unmoderated', 'socialcurator'); ?></option>
				<option value="publish"><?php _e('Approved', 'socialcurator'); ?></option>
				<option value="trash"><?php _e('Trashed', 'socialcurator'); ?></option>
			</select>
		</div>
		<div class="field">
			<select data-filter-site>
				<option value=""><?php _e('All Sites', 'socialcurator'); ?></option>
				<?php 
				foreach ( $this->settings_repo->getEnabledSites() as $site ){
					$sitename = $this->supported_sites->getKey($site, 'name');
					echo '<option value="' . $site . '">' . $sitename . '</option>';
				}
				?>
			</select>
		</div>
		<button class="button" data-filter-grid><?php _e('Filter', 'socialcurator'); ?></button>
	</div><!-- .social-curator-filter-grid -->

	<div class="social-curator-alert-success social-curator-alert" style="display:none;">
		<span data-social-curator-import-count>0</span> <?php _e('New Posts Imported', 'socialcurator'); ?><span data-social-curator-import-site> <?php _e('from all sites', 'socialcurator'); ?></span>
		<a href="#" class="close" data-dismiss="alert">&times;</a>
	</div>

	<div class="social-curator-alert-error" style="display:none;">
		<span data-social-curator-import-error></span>
	</div>


	<div class="social-curator-post-grid" data-post-grid>
		<?php $this->loopPosts(); ?>
		<div class="gutter-sizer"></div>
	</div><!-- .social-curator-post-grid -->

	
	<!-- Template used for cloning / appending new posts -->
	<div data-post-template style="display:none;">
		<?php include('single-post-template.php'); ?>
	</div><!-- .post-template -->

</div><!-- .wrap -->