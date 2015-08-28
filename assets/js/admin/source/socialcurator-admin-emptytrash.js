var SocialCurator = SocialCurator || {};

/**
* Empty Trash Functionality
*/
SocialCurator.EmptyTrash = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.modals = new SocialCurator.Modals;

	plugin.selectors = {
		trashButton : '[data-empty-social-trash]',
		trashCount : '[data-trash-count]'
	}

	plugin.init = function()
	{
		plugin.bindEvents();
	}

	plugin.bindEvents = function()
	{
		$(document).on('click', plugin.selectors.trashButton, function(e){
			e.preventDefault();
			plugin.emptyTrash();
		});
	}

	plugin.emptyTrash = function()
	{
		SocialCurator.toggleLoading(true);
		plugin.modals.closeModals();
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				nonce : SocialCurator.jsData.nonce,
				action: SocialCurator.formActions.emptyTrash
			},
			success: function(data){
				SocialCurator.toggleLoading(false);
				$(plugin.selectors.trashCount).text('0');
				$(plugin.selectors.trashButton).attr('disabled', false);
				
				if ( $('[data-filter-status]').val() === 'trash' ) {
					$('[data-post-grid]').find('.social-curator-post-grid-single').remove();
				}
			}
		});
	}

	return plugin.init();
}