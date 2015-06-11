var SocialCuratorLogs = function()
{
	var plugin = this;
	var $ = jQuery;
	plugin.button = '[data-social-curator-clear-logs]';

	plugin.init = function()
	{
		plugin.bindEvents();
	}

	plugin.bindEvents = function()
	{
		$(document).on('click', plugin.button, function(e){
			e.preventDefault();
			plugin.clearLogs();
		});
	}

	// Clear the Logs
	plugin.clearLogs = function()
	{
		plugin.toggleLoading(true);
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				nonce : social_curator_admin.social_curator_nonce,
				action: 'social_curator_clear_logs'
			},
			success: function(data){
				plugin.toggleLoading(false);
				console.log(data);
				document.location.reload(true);
			}
		});
	}

	plugin.toggleLoading = function(loading)
	{
		if ( loading ){
			$(plugin.button).attr('disabled', 'disabled');
			$('[data-log-clear-loader]').css('display','inline-block');
			return;
		}
		$(plugin.button).attr('disabled', false);
		$('[data-log-clear-loader]').css('display','none');
		return;
	}

	return plugin.init();
}