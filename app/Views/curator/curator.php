<?php
$enabled_sites = $this->settings_repo->getEnabledSites();
?>
<div class="wrap social-curator-curation-page">

	<h2>
		<?php echo $this->settings_repo->menuSetting('page_title'); ?>
		<div class="social-curator-curation-loading-indicator" data-curation-loader>
			<img src="<?php echo SocialCurator\Helpers::plugin_url(); ?>/assets/images/loading-admin.gif" />
		</div>
	</h2>
	
	<div class="social-curator-run-import social-curator-alert">
		<p>
			<div class="social-curator-dropdown one">
				<a href="#" data-toggle="social-curator-dropdown" class="button toggle-button"><?php _e('Run an Import', 'socialcurator'); ?><i class="dashicons dashicons-arrow-down"></i></a>
				<div class="social-curator-dropdown-content">
					<ul>
						<?php
							foreach ( $enabled_sites as $site ){
								$sitename = $this->supported_sites->getKey($site, 'name');
								$out = '<li><button data-import-site="' . $site . '" class="site-button">' . __('Import', 'socialcurator') . ' ' . $sitename . '</button></li>';
								echo $out;
							}
						?>
						<li>
							<a href="#" data-social-curator-import-all class="button button-primary"><?php _e('Import All', 'socialcurator'); ?></a>
						</li>
					</ul>
				</div><!-- .social-curator-drowdown-content -->
			</div><!-- .dropdown -->

			<!-- Single Importer -->
			<div class="social-curator-dropdown two" style="margin-right:8px;">
				<a href="#" data-toggle="social-curator-dropdown" class="button toggle-button"><?php _e('Import Single Post', 'socialcurator'); ?><i class="dashicons dashicons-arrow-down"></i>
				</a>
				<div class="social-curator-dropdown-content social-curator-single-import">
					<select data-social-curator-single-import-site>
						<?php
							foreach ( $this->supported_sites->singleImportSites() as $site => $name ){
								if ( !in_array($site, $enabled_sites) ) continue;
								echo '<option value="' . $site . '">' . $name . '</option>';
							}
						?>
					</select>
					<div class="single-import-id">
						<input type="text" data-social-curator-single-import-id placeholder="<?php _e('ID', 'socialcurator'); ?>" />
						<a href="#" data-id-help-modal data-social-curator-modal-open="id-help-twitter">?</a>
					</div>
					<button class="button button-primary" data-social-curator-single-import><?php _e('Import', 'socialcurator'); ?>
				</div>
			</div>

			<?php _e('Last Import', 'socialcurator'); ?>: <span data-social-curator-last-import><?php echo $this->settings_repo->lastImport('M jS'); ?> at <?php echo $this->settings_repo->lastImport('g:ia'); ?></span><br>
			<strong><span data-social-curator-unmoderated-count><?php echo $this->social_post_repo->getUnmoderatedCount(); ?></span> <?php _e('awaiting moderation', 'socialcurator'); ?></strong></p>
		</p>
	</div><!-- .social-curator-import-form -->

	<div class="social-curator-filter-grid">
		<div class="field">
			<select data-filter-status>
				<option value="all"><?php _e('All Statuses', 'socialcurator'); ?></option>
				<option value="pending"><?php _e('Unmoderated', 'socialcurator'); ?></option>
				<option value="publish"><?php _e('Approved', 'socialcurator'); ?></option>
				<option value="trash"><?php _e('Trashed', 'socialcurator'); ?></option>
			</select>
		</div>
		<div class="field">
			<select data-filter-site>
				<option value="all"><?php _e('All Sites', 'socialcurator'); ?></option>
				<?php 
				foreach ( $this->settings_repo->getEnabledSites() as $site ){
					$sitename = $this->supported_sites->getKey($site, 'name');
					echo '<option value="' . $site . '">' . $sitename . '</option>';
				}
				?>
			</select>
		</div>
		<button class="button" data-filter-grid><?php _e('Filter', 'socialcurator'); ?></button>

		<?php if ( current_user_can('delete_others_posts') ) : ?>
			<button class="button pull-right" data-social-curator-modal-open="empty-trash-confiramtion"><?php _e('Empty Trash', 'socialcurator'); ?> (<span data-trash-count><?php echo $this->social_post_repo->trashCount(); ?></span>)</button>
		<?php endif; ?>

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

	<div class="social-curator-post-grid-footer">
		<div data-social-curator-grid-loading style="display:none;">
			<img src="<?php echo SocialCurator\Helpers::plugin_url(); ?>/assets/images/loading-admin.gif" />
		</div>
		<button class="button button-primary" data-social-curator-load-more><?php _e('Load More', 'socialcurator'); ?></button>
	</div>

	
	<!-- Template used for cloning / appending new posts -->
	<div data-post-template style="display:none;">
		<?php include('single-post-template.php'); ?>
	</div><!-- .post-template -->

	<!-- Help Modals -->
	<?php $this->helpModals(); ?>
	<?php include(SocialCurator\Helpers::view('curator/trash-confirm-modal')); ?>

</div><!-- .wrap -->