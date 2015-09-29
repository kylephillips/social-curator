<?php 
settings_fields( 'social-curator-site-' . $this->site_index); 
$fieldname = 'social_curator_site_' . $this->site_index;
$search_term = $this->settings_repo->getSiteSetting($this->site_index, 'search_term');
$user_term = $this->settings_repo->getSiteSetting($this->site_index, 'user_term');
?>

<h3 class="social-curator-settings-header"><i class="social-curator-icon-twitter"></i> <?php _e('Twitter Settings', 'socialcurator'); ?></h3>
<div class="social-curator-site-settings-instructions">
	<p><?php _e('To connect a Twitter account, first create an application from the Twitter developer console. Once the application has been created, you must create an access token for the application.', 'socialcurator') . '</p>'; ?></p>
	<p><strong><?php _e('API Homepage', 'socialcurator'); ?>:</strong> <a href="https://apps.twitter.com/" target="_blank"><?php _e('Twitter Apps', 'socialcurator'); ?></a></p>
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
			<label><?php _e('Search Term', 'socialcurator'); ?> (<a href="https://dev.twitter.com/rest/public/search" target="_blank"><?php _e('Search Reference', 'socialcurator'); ?></a>)</label>
			<input type="text" name="<?php echo $fieldname; ?>[search_term]" value="<?php if ( $search_term ) echo $search_term; ?>" />
		</li>
		<li>
			<label><input type="checkbox" name="<?php echo $fieldname; ?>[include_retweets]" <?php if ( $this->settings_repo->getSiteSetting($this->site_index, 'include_retweets') ) echo ' checked'; ?> ) /><?php _e('Include Retweets in Import', 'socialcurator'); ?></label>
		</li>
	</ul>
</div>

<div class="social-curator-site-settings">
	<ul class="fields">
		<li>
			<label><?php _e('User Feed', 'socialcurator'); ?><br>(<?php _e('User name only, do not include @. Leave this blank to only use search term.', 'socialcurator'); ?>)</label>
			<input type="text" name="<?php echo $fieldname; ?>[user_term]" value="<?php if ( $user_term ) echo $user_term; ?>" />
		</li>
	</ul>
</div>

<?php submit_button(); ?>