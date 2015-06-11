jQuery(function($){

	$(document).ready(function(){
		var avatarUpdater = new SocialCuratorAvatarUpdater;
		avatarUpdater.init();
	});


	/**
	* Settings Page - Change the Default Avatar
	*/
	var SocialCuratorAvatarUpdater = function()
	{
		var plugin = this;
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
	}
	

});