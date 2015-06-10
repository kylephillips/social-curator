<?php settings_fields( 'social-curator-general' ); ?>
<div class="social-curator-settings-enabled-sites">
	<h3><?php _e('Enabled Sites', 'socialcurator'); ?></h3>
	<ul>
		<?php 
		$enabled = $this->settings_repo->getEnabledSites(); 
		foreach ( $this->supported_sites->getSites() as $key => $site ) : ?>
			<li>
				<input type="hidden" name="social_curator_enabled_sites[<?php echo $key; ?>]" value="<?php echo $key; ?>" />
				<label for="social_curator_enabled_sites_<?php echo $key; ?>">
					<input type="checkbox" name="social_curator_enabled_sites[<?php echo $key; ?>][enabled]" id="social_curator_enabled_sites_<?php echo $key; ?>" <?php if ( in_array($key, $enabled) ) echo 'checked'; ?> />
					<?php echo $site['name']; ?>
				</label>
			</li>
		<?php endforeach; ?>
	</ul>
</div>

<div class="social-curator-site-settings">
	<h4 style="margin:0 0 8px 0;"><?php _e('Default Import Status', 'socialcurator'); ?></h4>
	<select name="social_curator_import_status">
		<option value="pending" <?php if ( $this->settings_repo->importStatus() == 'pending' ) echo 'selected'; ?> ><?php _e('Pending Review', 'socialcurator'); ?></option>
		<option value="publish" <?php if ( $this->settings_repo->importStatus() == 'publish' ) echo 'selected'; ?>><?php _e('Published', 'socialcurator'); ?></option>	
	</select>
</div>

<div class="social-curator-settings-fallback-avatar">
	<h4 style="margin:0;"><?php _e('Fallback User Avatar', 'socialcurator'); ?></h4>
	<p style="margin-top:0;"><?php _e('Image will display if a user profile image is not available.', 'socialcurator'); ?></p>
	
	<div class="avatar-fallback-image" data-avatar-image>
		<input type="hidden" data-fallback-avatar-url="<?php echo SocialCurator\Helpers::plugin_url() . '/assets/images/avatar-fallback.png' ?>" />
		<?php echo $this->settings_repo->fallbackAvatar(false); ?>
	</div>

	<div class="choose-avatar-button">
		<input data-choose-avatar-image type="button" value="<?php _e('Choose Image', 'socialcurator'); ?>" class="button" />
		<input type="text" data-fallback-avatar-field name="social_curator_fallback_avatar" value="<?php echo get_option('social_curator_fallback_avatar'); ?>" style="display:none;" />
	</div>
</div>
<?php submit_button(); ?>