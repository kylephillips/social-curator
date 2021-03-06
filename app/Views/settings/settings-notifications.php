<?php settings_fields( 'social-curator-notifications' ); ?>
<h3><?php _e('Email Notifications', 'socialcurator'); ?></h3>

<p><?php _e('Daily notifications are sent if there are any import errors, or if the unmoderated count exceeds the specified number.', 'socialcurator'); ?></p>

<div class="social-curator-settings-notifications">
	<p class="half">
		<label for="social_curator_notification_from" style="display:block;"><?php _e('From Name', 'socialcurator'); ?></label>
		<input type="text" name="social_curator_notification_from" id="social_curator_notification_from" value="<?php echo $this->settings_repo->fromName(); ?>" />
	</p>
	<p class="half right">
		<label for="social_curator_notification_from_email" style="display:block;"><?php _e('From Email', 'socialcurator'); ?></label>
		<input type="text" name="social_curator_notification_from_email" id="social_curator_notification_from_email" value="<?php echo $this->settings_repo->fromEmail(); ?>" />
	</p>
	<p>
		<label for="social_curator_notification_emails" style="display:block;margin-bottom:5px;">
			<?php _e('Notification Emails (format as comma separated list)', 'socialcurator'); ?>
		</label>
		<textarea name="social_curator_notification_emails" id="social_curator_notification_emails" style="width:100%;height:150px;"><?php echo $this->settings_repo->notificationEmails(); ?></textarea>
	</p>
</div>
<?php submit_button(); ?>