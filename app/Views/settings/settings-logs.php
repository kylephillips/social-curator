<h3><?php _e('Import Error Logs', 'socialcurator'); ?></h3>
<?php
$logs = $this->log_repo->getLogs();
if ( $logs ) :
?>
<table class="social-curator-log-table">
	<thead>
		<tr>
			<th><?php _e('Date', 'socialcurator'); ?></th>
			<th><?php _e('Time', 'socialcurator'); ?></th>
			<th><?php _e('Type', 'socialcurator'); ?></th>
			<th class="long"><?php _e('Message', 'socialcurator'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $logs as $log ) : ?>
		<tr>
			<td><?php echo date_i18n('F jS, Y', strtotime($log->time)); ?></td>
			<td><?php echo date_i18n('H:m a', strtotime($log->time)); ?></td>
			<td><?php echo $log->type; ?></td>
			<td class="long"><?php echo $log->message; ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<button class="button button-primary" data-social-curator-clear-logs><?php _e('Clear Logs', 'socialcurator'); ?></button>

<div class="social-curator-button-loading-indicator" data-log-clear-loader>
	<img src="<?php echo SocialCurator\Helpers::plugin_url(); ?>/assets/images/loading-admin.gif" />
</div>

<script>
jQuery(document).ready(function(){
	var logs = new SocialCuratorLogs;
});
</script>

<?php else : // No Logs ?>
<div class="social-curator-alert">
	<p><?php _e('No Import Error Logs', 'socialcurator'); ?></p>
</div>
<?php endif; ?>
