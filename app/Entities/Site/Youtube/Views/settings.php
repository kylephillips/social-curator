<?php 
settings_fields( 'social-curator-site-' . $this->site_index); 
$fieldname = 'social_curator_site_' . $this->site_index;
$search_term = $this->settings_repo->getSiteSetting($this->site_index, 'search_term');
?>

<h3 class="social-curator-settings-header"><i class="social-curator-icon-youtube3"></i> <?php _e('YouTube Settings', 'socialcurator'); ?></h3>
<div class="social-curator-site-settings-instructions">
	<p><?php _e('To connect a YouTube account, first create an application from the Google Developers Console and enable the YouTube API. Once the application has been created, you must create a public API Key. This site\'s IP address must be whitelisted.', 'socialcurator') . '</p>'; ?></p>
	<p><strong><?php _e('API Homepage', 'socialcurator'); ?>:</strong> <a href="https://console.developers.google.com/" target="_blank"><?php _e('Google Developer Console', 'socialcurator'); ?></a></p>
</div>

<div class="social-curator-site-settings">
	<ul class="fields">
	<?php 
		foreach ( $this->site['settings_fields'] as $key => $label ) : 
		$setting = $this->settings_repo->getSiteSetting($this->site_index, $key);
	?>
	<li>
		<label><?php echo $label; ?></label>
		<input type="text" name="<?php echo $fieldname; ?>[<?php echo $key; ?>]" value="<?php if ( $setting ) echo $setting; ?>" />
	</li>
	<?php endforeach; ?>
	</ul>
</div><!-- .social-curator-site-settings -->

<div class="social-curator-site-settings">
	<ul class="fields">
		<li>
			<label><?php _e('Search Term', 'socialcurator'); ?></label>
			<input type="text" name="<?php echo $fieldname; ?>[search_term]" value="<?php if ( $search_term ) echo $search_term; ?>" />
		</li>
	</ul>
</div>
<?php submit_button(); ?>