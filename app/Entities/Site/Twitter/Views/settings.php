<?php 
settings_fields( 'social-curator-site-' . $this->site_index); 
$fieldname = 'social_curator_site_' . $this->site_index;
?>

<h3><?php _e('Twitter Settings', 'socialcurator'); ?></h3>
<div class="social-curator-site-settings-instructions">
	<p><?php _e('To connect a Twitter account, first create an application from the Twitter developer console. Once the application has been created, you must create an access token for the application.', 'socialcurator') . '</p>'; ?></p>
	<p><strong><?php _e('API Homepage', 'socialcurator'); ?>: <a href="https://apps.twitter.com/" target="_blank"><?php _e('Twitter Apps', 'socialcurator'); ?></a></p>
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

<?php submit_button(); ?>