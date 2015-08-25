var SocialCurator = SocialCurator || {};
/**
* Dropdowns
*/
SocialCurator.Dropdowns = function()
{
	var plugin = this;
	var $ = jQuery;
	
	plugin.toggleButton = '[data-toggle="social-curator-dropdown"]';
	plugin.currentDropdown = "";

	plugin.init = function()
	{
		plugin.bindEvents();
	}

	plugin.bindEvents = function()
	{
		$(document).on('click', plugin.toggleButton, function(e){
			e.preventDefault();
			plugin.currentDropdown = $(this).siblings('.social-curator-dropdown-content');
			plugin.toggleDropdown();
		});

		$(document).on('click', function(e){
			plugin.windowListener(e.target);
		});
	}

	plugin.toggleDropdown = function()
	{
		if ( $(plugin.currentDropdown).is(':visible') ){
			$('.social-curator-dropdown-content').hide();
		} else {
			$('.social-curator-dropdown-content').hide();
			$(plugin.currentDropdown).show();
		}
		$(plugin.currentDropdown).parents('.social-curator-dropdown').toggleClass('open');
	}

	plugin.windowListener = function(target)
	{
		if ( $(target).parents('.social-curator-dropdown').length == 0 ){
			$('.social-curator-dropdown-content').hide();
			$('.social-curator-dropdown').removeClass('open');
		}
	}

	plugin.closeAll = function()
	{
		$('.social-curator-dropdown-content').hide();
		$('.social-curator-dropdown').removeClass('open');
	}

	return plugin.init();
}