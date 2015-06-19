<?php 
settings_fields( 'social-curator-site-' . $this->site_index); 
$fieldname = 'social_curator_site_' . $this->site_index;
$search_term = $this->settings_repo->getSiteSetting($this->site_index, 'search_term');
?>

<h3 class="social-curator-settings-header"><i class="social-curator-icon-reddit"></i> <?php _e('Reddit Settings', 'socialcurator'); ?></h3>
<div class="social-curator-site-settings">
	<ul class="fields">
		<li>
			<label><?php _e('Search Term', 'socialcurator'); ?></label>
			<input type="text" name="<?php echo $fieldname; ?>[search_term]" value="<?php if ( $search_term ) echo $search_term; ?>" />
		</li>
	</ul>
</div>
<?php submit_button(); ?>