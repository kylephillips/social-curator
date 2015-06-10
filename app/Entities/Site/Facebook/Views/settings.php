<?php 
settings_fields( 'social-curator-site-' . $this->site_index); 
$fieldname = 'social_curator_site_' . $this->site_index;
$search_term = $this->settings_repo->getSiteSetting($this->site_index, 'search_term');
?>

<h3 class="social-curator-settings-header"><i class="social-curator-icon-facebook"></i> <?php _e('Facebook Settings', 'socialcurator'); ?></h3>
<div class="social-curator-site-settings-instructions">
	<p><?php _e('To connect a Facebook account, first create an application from the Facebook developer console. Once the application has been created, you must create an access token for the application.', 'socialcurator') . '</p>'; ?></p>
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

<?php submit_button(); ?>

<?php
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
FacebookSession::setDefaultApplication($this->supported_sites->getKey('facebook', 'app_id'), $this->supported_sites->getKey('facebook', 'app_secret'));