var SocialCurator = SocialCurator || {};

/**
* Settings Scripts
*/
SocialCurator.Settings = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.selectors = {
		showAdminSidebar : '[data-show-sidebar-menu]',
		adminSidebarOptions : '[data-sidebar-menu-option]',
		showAdminTopbar : '[data-show-adminbar-menu]',
		adminTopbarOptions : '[data-adminbar-menu-option]'
	}

	plugin.init = function()
	{
		plugin.bindEvents();
	}

	plugin.bindEvents = function()
	{
		$(document).ready(function(){
			plugin.toggleAdminMenuItems();
			plugin.toggleAdminBarItems();
		});
		$(document).on('change', $(plugin.selectors.showAdminSidebar), function(){
			plugin.toggleAdminMenuItems();
		});
		$(document).on('change', $(plugin.selectors.showAdminTopbar), function(){
			plugin.toggleAdminBarItems();
		});
	}

	plugin.toggleAdminMenuItems = function()
	{
		if ( $(plugin.selectors.showAdminSidebar).is(':checked') ){
			$(plugin.selectors.adminSidebarOptions).show();
			return;
		}
		$(plugin.selectors.adminSidebarOptions).hide();
	}

	plugin.toggleAdminBarItems = function()
	{
		if ( $(plugin.selectors.showAdminTopbar).is(':checked') ){
			$(plugin.selectors.adminTopbarOptions).show();
			return;
		}
		$(plugin.selectors.adminTopbarOptions).hide();
	}

	return plugin.init();
}