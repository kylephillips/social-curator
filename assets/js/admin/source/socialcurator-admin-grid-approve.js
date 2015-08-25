var SocialCurator = SocialCurator || {};

/**
* Approve a post in the grid
*/
SocialCurator.Approve = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.masonry = new SocialCurator.Masonry;
	plugin.formatter = new SocialCurator.Formatter;

	plugin.postID = ''; // Post ID for approval

	plugin.selectors = {
		approveButton : '[data-approve-post]'
	}

	plugin.init = function()
	{
		plugin.bindEvents();
	}

	plugin.bindEvents = function()
	{
		$(document).on('click', plugin.selectors.approveButton, function(e){
			e.preventDefault();
			$(this).attr('disabled', 'disabled');
			plugin.postID = $(this).attr('data-post-id');
			plugin.approvePost();
		});
	}

	plugin.approvePost = function()
	{
		SocialCurator.toggleLoading(true);
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				nonce : SocialCurator.jsData.nonce,
				action: SocialCurator.formActions.approvePost,
				post_id: plugin.postID
			},
			success: function(data){
				plugin.formatter.displayApproval(data);
				plugin.formatter.subtractUnmoderated();
				plugin.masonry.triggerMasonry();
				SocialCurator.toggleLoading(false);
			}
		});
	}

	return plugin.init();
}