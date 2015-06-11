<?php 
settings_fields( 'social-curator-site-' . $this->site_index); 
$fieldname = 'social_curator_site_' . $this->site_index;
$code = ( isset($_GET['code']) ) ? $_GET['code'] : '';
?>

<h3 class="social-curator-settings-header"><i class="social-curator-icon-instagram"></i> <?php _e('Instagram Settings', 'socialcurator'); ?></h3>

<?php if ( !$this->settings_repo->getSiteSetting('instagram', 'auth_token') ) : ?>
	<div class="social-curator-site-settings-instructions">
		<p><?php _e('To use the Instagram API, first setup an application from the Instagram developer area. The Redirect URL below must be included in the application setup.', 'socialcurator') . '</p>'; ?></p>
		<p><strong><?php _e('API Homepage', 'socialcurator'); ?>:</strong> <a href="https://instagram.com/developer/" target="_blank"><?php _e('Instagram Developer Docs', 'socialcurator'); ?></a></p>
		<p><strong>Redirect URL:</strong><br><input type="text" value="<?php echo $this->getRedirectURL('instagram'); ?>" /></p>
	</div>

	<h3><?php _e('Step 1: Save API Credentials', 'socialcurator'); ?></h3>
	<div class="social-curator-site-settings">
		<ul class="fields">
		<?php 
			foreach ( $this->site['settings_fields'] as $key => $label ) : 
			if ( $key == 'auth_token' ) continue;
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
	/**
	* Authorize Button
	*/
	if ( $this->settings_repo->fieldsForAuthRedirectComplete($this->site_index, $this->site) && !isset($_GET['code']) ) :
	$authorize_url = 'https://api.instagram.com/oauth/authorize/?client_id=' . $this->settings_repo->getSiteSetting($this->site_index, 'client_id') . '&redirect_uri=' . urlencode($this->getRedirectURL('instagram')) . '&response_type=CODE';
	?>
	<h3><?php _e('Step 2: Authorize Application', 'socialcurator'); ?></h3>
	<p><a href="<?php echo $authorize_url; ?>" class="button"><?php _e('Authorize', 'socialcurator'); ?></a></p>
	<?php endif; ?>


	<?php
	/**
	* Getting/Saving Access Token Indication
	*/
	if ( isset($_GET['code']) ) : ?>
	<h3><?php _e('Step 2: Authorize Application', 'socialcurator'); ?></h3>
	<div class="social-curator-instagram-token-error social-curator-alert-error" style="display:none;"></div>
	<div class="social-curator-instagram-token-indicator">
		<div class="social-curator-loading-small"></div>
		<div class="social-curator-indicator-status"><strong><?php _e('Status', 'socialcurator'); ?>:</strong> <span class="instagram-token-status"><?php _e('Requesting Access Token'); ?></span></div>
	</div>
	<script>
		jQuery(document).ready(function(){
			requestInstagramToken();
		});
	</script>
	<?php endif; ?>


<?php else : // Auth Token is saved 
$search_term = $this->settings_repo->getSiteSetting($this->site_index, 'search_term');
?>
<div class="social-curator-site-settings">
	<ul class="fields">
		<li>
			<label><?php _e('Search Term', 'socialcurator'); ?></label>
			<input type="text" name="<?php echo $fieldname; ?>[search_term]" value="<?php if ( $search_term ) echo $search_term; ?>" />
		</li>
	</ul>
	<!-- Auth Fields -->
		<input type="hidden" name="<?php echo $fieldname; ?>[client_id]" value="<?php echo $this->settings_repo->getSiteSetting($this->site_index, 'client_id'); ?>">
		<input type="hidden" name="<?php echo $fieldname; ?>[client_secret]" value="<?php echo $this->settings_repo->getSiteSetting($this->site_index, 'client_secret'); ?>">
		<input type="hidden" name="<?php echo $fieldname; ?>[auth_token]" value="<?php echo $this->settings_repo->getSiteSetting($this->site_index, 'auth_token'); ?>">
		<input type="hidden" name="<?php echo $fieldname; ?>[auth_user]" value="<?php echo $this->settings_repo->getSiteSetting($this->site_index, 'auth_user'); ?>">
</div>
<?php submit_button(); ?>

<div class="social-curator-alert social-curator-alert-success">
	<?php _e('Connected to Instagram as', 'socialcurator'); ?>: <strong><?php echo $this->settings_repo->getSiteSetting($this->site_index, 'auth_user'); ?></strong>
</div>
<p><a href="#" data-instagram-remove-auth class="button"><?php _e('Disconnect', 'socialcurator'); ?></a></p>
<p><em>(<?php _e('Disconnecting will remove authorization credentials from this website. To fully disconnect, visit the connected Instagram account > Edit Profile > Manage Applications > and revoke access.', 'socialcurator'); ?>)</em></p>
<?php endif; ?>

<script>

	jQuery(document).on('click', '[data-instagram-remove-auth]', function(e){
		e.preventDefault();
		removeInstagramAuth();
	});

	/**
	* Request an Access Token from Instagram using the provided access code
	*/
	function requestInstagramToken()
	{
		jQuery('.social-curator-instagram-token-indicator').show();
		jQuery('.social-curator-alert-error').hide();

		var code = "<?php echo $code; ?>";
		jQuery.ajax({
			url : ajaxurl,
			type : 'POST',
			data : {
				action : 'instagram_token_request',
				nonce : social_curator_admin.social_curator_nonce,
				code: code,
				redirect_uri : "<?php echo $this->getRedirectURL('instagram'); ?>"
			},
			success: function(data){
				if ( data.status === 'success' ){
					window.location.replace("<?php echo $this->getRedirectURL('instagram'); ?>");
				} else {
					jQuery('.social-curator-instagram-token-indicator').hide();
					jQuery('.social-curator-instagram-token-error').text(data.message).show();
				}
			}
		});
	}

	/**
	* Remove Instagram credentials
	*/
	function removeInstagramAuth()
	{
		jQuery.ajax({
			url : ajaxurl,
			type : 'POST',
			data : {
				action : 'instagram_remove_auth',
				nonce : social_curator_admin.social_curator_nonce
			},
			success: function(data){
				window.location.replace("<?php echo $this->getRedirectURL('instagram'); ?>");
			}
		});
	}

</script>