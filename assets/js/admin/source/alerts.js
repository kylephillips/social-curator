jQuery(function($){

	$(document).ready(function(){
		var alerts = new SocialCuratorAlert;
		alerts.init();
	});

	/**
	* Close an Alert
	*/
	var SocialCuratorAlert = function()
	{
		var plugin = this;
		plugin.button = '[data-dismiss="alert"]';
		plugin.el = "";

		// Initialize
		plugin.init = function()
		{
			plugin.bindEvents();
		}

		// Bind Events
		plugin.bindEvents = function()
		{
			$(document).on('click', plugin.button, function(e){
				e.preventDefault();
				plugin.el = $(this).parents('.social-curator-alert')
				plugin.closeAlert();
			});
		}

		// Close the Alert
		plugin.closeAlert = function()
		{
			$(plugin.el).fadeOut('fast');
		}
	}

});