var SocialCurator = SocialCurator || {};

/**
* Actions in default post columns view
*/
SocialCurator.PostColumns = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.button = ''; // active button

	plugin.selectors = {
		moderateButton : '[data-social-curator-moderate-select-button]',
		moerateSelect : '[data-social-curator-moderate-select]'
	}

	plugin.init = function()
	{
		plugin.bindEvents();
	}

	plugin.bindEvents = function()
	{
		$(document).on('click', plugin.selectors.moderateButton, function(e){
			e.preventDefault();
			plugin.button = $(this);
			$(this).attr('disabled', 'disabled').text(SocialCurator.localizedText.updating);
			plugin.moderatePost();
		});
	}

	plugin.moderatePost = function()
	{
		var post_id = $(plugin.button).attr('data-post-id');
		var status = $(plugin.button).siblings('[data-social-curator-moderate-select]').val();
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				nonce : SocialCurator.jsData.nonce,
				action: SocialCurator.formActions.updateStatus,
				post_id: post_id,
				status: status
			},
			success: function(data){
				$(plugin.button).attr('disabled', false).text(SocialCurator.localizedText.update);
				if ( status === 'trash' ){
					$(plugin.button).parents('tr').fadeOut('fast', function(){
						$(this).remove();
					});
				}
			}
		});
	}

	return plugin.init();
}