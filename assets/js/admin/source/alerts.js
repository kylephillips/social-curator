jQuery(document).ready(function(){
	var alerts = new SocialCuratorAlert;
});

/**
* Close an Alert
*/
var SocialCuratorAlert = function()
{
	var plugin = this;
	var $ = jQuery;
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

	return plugin.init();
}