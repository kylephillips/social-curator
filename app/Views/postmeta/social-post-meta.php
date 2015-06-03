<?php wp_nonce_field( 'my_social_curator_meta_box_nonce', 'social_curator_meta_box_nonce' ); ?>
<div class="social-curator-meta">
	
	<?php if ( $this->meta['social_curator_link'] && $this->meta['social_curator_screen_name']) : ?>
	<div class="original-link">
		<?php echo $this->presenter->getAvatar($post->ID); ?>
		<p><strong><a href="<?php echo $this->presenter->getProfileLink($this->meta['social_curator_screen_name'], $this->meta['social_curator_site']); ?>" target="_blank">
			<?php echo __('By', 'socialcurator') . ' ' . $this->meta['social_curator_screen_name']; ?>
			</a></strong><a href="<?php echo esc_url($this->meta['social_curator_link']); ?>" target="_blank" class="button"><?php _e('View Original Post', 'socialcurator'); ?></a></p>
	</div>
	<?php endif; ?>

	<div class="field">
		<label for="social_curator_site"><?php _e('Site', 'socialcurator'); ?></label>
		<select name="social_curator_site" id="social_curator_site">
			<?php 
			foreach($this->settings_repo->getEnabledSites() as $site) { 
				$site_name = $this->supported_sites->getSite($site);
				$out = '<option value="' . $site . '"';
				if ( $site == $this->meta['social_curator_site'] ) $out .= ' selected';
				$out .= '>';
				$out .= $site_name['name'];
				$out .= '</option>';
				echo $out;
			}
			?>
		</select>
	</div>
	<div class="field even">
		<label for="social_curator_original_id"><?php _e('Original ID', 'socialcurator'); ?></label>
		<input type="text" name="social_curator_original_id" id="social_curator_original_id" value="<?php echo $this->meta['social_curator_original_id']; ?>" />
	</div>
	<div class="field full">
		<label for="social_curator_link"><?php _e('Link', 'socialcurator'); ?></label>
		<input type="text" name="social_curator_link" id="social_curator_link" value="<?php echo $this->meta['social_curator_link']; ?>" />
	</div>
	<div class="field-header">
		<h3><?php _e('User Details', 'socialcurator'); ?></h3>
	</div>
	<div class="field">
		<label for="social_curator_screen_name"><?php _e('Screen Name', 'socialcurator'); ?></label>
		<input type="text" name="social_curator_screen_name" id="social_curator_screen_name" value="<?php echo $this->meta['social_curator_screen_name']; ?>" />
	</div>
	<div class="field even">
		<label for="social_curator_user_id"><?php _e('User ID', 'socialcurator'); ?></label>
		<input type="text" name="social_curator_user_id" id="social_curator_user_id"  value="<?php echo $this->meta['social_curator_user_id']; ?>" />
	</div>
	<?php if ( $this->meta['social_curator_approved_by'] && $this->meta['social_curator_approved_date'] ) : ?>
		<div class="social-curator-alert">
			<?php _e('Approved By', 'socialcurator'); ?> <?php echo $this->meta['social_curator_approved_by']; ?> <?php _e('on', 'socialcurator'); ?> <?php echo $this->meta['social_curator_approved_date']; ?>
		</div>
		<input type="hidden" name="social_curator_approved_by" id="social_curator_approved_by" value="<?php echo $this->meta['social_curator_approved_by']; ?>" />
		<input type="hidden" name="social_curator_approved_date" id="social_curator_approved_date" value="<?php echo $this->meta['social_curator_approved_date']; ?>" />

	<?php else : ?>
		<div class="social-curator-alert"><?php _e('Pending Approval', 'socialcurator'); ?></div>
	<?php endif; ?>
</div><!-- .social-curator-meta -->