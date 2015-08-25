var SocialCurator = SocialCurator || {};

/**
* Trash a post in the grid
*/
SocialCurator.TrashPost = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.formatter = new SocialCurator.Formatter;
	plugin.masonry = new SocialCurator.Masonry;
	plugin.button = ''; // The active trash button

	plugin.selectors = {
		trashButton : '[data-trash-post]',
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
			$(this).attr('disabled', 'disabled');
			plugin.button = $(this);
			plugin.trashPost();
		});
	}

	plugin.trashPost = function()
	{
		SocialCurator.toggleLoading(true);
		var id = $(plugin.button).attr('data-post-id');
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				nonce : SocialCurator.jsData.nonce,
				action: SocialCurator.formActions.trashPost,
				post_id: id
			},
			success: function(){
				$(plugin.selectors.trashCount).text(parseInt($(plugin.selectors.trashCount).text()) + 1);
				SocialCurator.toggleLoading(false);
				plugin.updateGrid();
			}
		});
	}

	plugin.updateGrid = function()
	{
		if ( !$(plugin.button).parents('.social-curator-post-grid-single').hasClass('approved') ){
			plugin.formatter.subtractUnmoderated();
		}
		$(plugin.button).parents('.social-curator-post-grid-single').fadeOut('fast', function(){
			$('.social-curator-post-grid').masonry('remove', $(this));
			plugin.masonry.triggerMasonry();
		});
	}

	return plugin.init();
}
