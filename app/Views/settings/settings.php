<div class="wrap">
	<h1><?php _e('Social Curator Settings', 'socialcurator'); ?></h1>

	<h2 class="nav-tab-wrapper">
		<a class="nav-tab <?php if ( $tab == 'general' ) echo 'nav-tab-active'; ?>" href="options-general.php?page=social-curator-settings"><?php _e('General', 'socialcurator'); ?></a>
		<a class="nav-tab <?php if ( $tab == 'notifications' ) echo 'nav-tab-active'; ?>" href="options-general.php?page=social-curator-settings&amp;tab=notifications"><?php _e('Notifications', 'socialcurator'); ?></a>
		<?php echo $this->siteTabs(); ?>
	</h2>

	<form method="post" enctype="multipart/form-data" action="options.php">
		<?php 
			if ( isset($this->site['name']) ){
				include(SocialCurator\Helpers::site_view($this->site['name'], 'settings')); 	
			} else {
				$tab = ( isset($_GET['tab']) ) ? $_GET['tab'] : 'general';
				include(SocialCurator\Helpers::view('settings/settings-' . $tab));
			}
		?>
	</form>	

	<p class="social-curator-version"><?php _e('Social Curator Version', 'socialcurator'); echo ' ' . $this->settings_repo->getVersion(); ?></p>
</div>