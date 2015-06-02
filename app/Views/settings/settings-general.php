<?php settings_fields( 'social-curator-general' ); ?>
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

<?php submit_button(); ?>