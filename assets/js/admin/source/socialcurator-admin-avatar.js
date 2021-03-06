var SocialCurator = SocialCurator || {};

/**
* Settings Page - Change the Default Avatar
*/
SocialCurator.Avatar = function()
{
	var plugin = this;
	var $ = jQuery;
	
	plugin.button = '[data-choose-avatar-image]';

	plugin.init = function()
	{
		plugin.bindEvents();
	}

	// Bind Events
	plugin.bindEvents = function()
	{
		$(document).on('click', plugin.button, function(){
			plugin.showMediaLibrary();
		});
	}

	// Show the Media Library
	plugin.showMediaLibrary = function()
	{
		formfield = $('[data-fallback-avatar-field]').attr('name');
		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		return false;
	}

	window.send_to_editor = function(html) {
		imgurl = $('img',html).attr('src');
		$('[data-avatar-image]').find('img').attr('src', imgurl);
		$('[data-fallback-avatar-field]').val(imgurl);
		tb_remove();
	}

	return plugin.init();
}