<div class="social-curator-test-feed">
	<h3><?php _e('Test Feed', 'socialcurator'); ?></h3>
	<div class="social-curator-alert-error" data-test-feed-error style="display:none;"></div>
	<div class="options">
		<div class="formatting">
			<h4>Formatting</h4>
			<p>
				<label class="block-label"><input type="radio" data-feed-format name="feed-format" value="formatted" checked /><?php _e('Import data', 'socialcurator'); ?></label>
				<label class="block-label"><input type="radio" data-feed-format name="feed-format" value="unformatted" /><?php _e('All feed data', 'socialcurator'); ?></label>
			</p>
		</div>
		<div class="type">
			<h4>Feed Type</h4>
			<p>
				<label class="block-label"><input type="radio" data-feed-type name="feed-type" value="search" checked /><?php _e('Search', 'socialcurator'); ?></label>
				<?php if ( $this->supported_sites->getKey($this->site_index, 'single_import') ) : ?>
				<label class="block-label"><input type="radio" data-feed-type name="feed-type" value="single" /><?php _e('Single', 'socialcurator'); ?></label>
				<span style="display:none;padding-top:10px;" data-feed-id-container>
					<label style="margin-right:8px;"><?php _e('ID', 'socialcurator'); ?></label><input type="text" data-feed-id />
				</span>
				<?php endif; ?>
			</p>
		</div>
	</div><!-- .options -->
	<button class="button" data-test-feed data-site="<?php echo $this->site_index; ?>"><?php _e('Test Feed', 'socialcurator'); ?></button>
	<div class="social-curator-test-feed-results" data-test-feed-results style="display:none;"></div>
</div><!-- .social-curator-test-feed -->