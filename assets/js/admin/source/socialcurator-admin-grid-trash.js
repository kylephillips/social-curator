var SocialCurator = SocialCurator || {};

/**
* Restore and delete posts permanently when viewing the trash
*/
SocialCurator.Trash = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.postID = ''; // Post ID to update
	plugin.formatter = new SocialCurator.Formatter;
	plugin.masonry = new SocialCurator.Masonry;

	plugin.selectors = {
		restorePostButton : '[data-restore-post]',
		deletePostButton : '[data-permanent-delete-post]'
	}

	plugin.init = function()
	{
		plugin.bindEvents();
	}

	plugin.bindEvents = function()
	{
		$(document).on('click', plugin.selectors.restorePostButton, function(e){
			e.preventDefault();
			$(this).attr('disabled', 'disabled');
			plugin.postID = $(this).attr('data-post-id');
			plugin.restorePost();
		});
		$(document).on('click', plugin.selectors.deletePostButton, function(e){
			e.preventDefault();
			$(this).attr('disabled', 'disabled');
			plugin.postID = $(this).attr('data-post-id');
			plugin.deletePost();
		});
	}

	plugin.restorePost = function()
	{
		SocialCurator.toggleLoading(true);
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				nonce : SocialCurator.jsData.nonce,
				action: SocialCurator.formActions.restorePost,
				post_id: plugin.postID
			},
			success: function(data){
				plugin.formatter.addUnmoderated();
				plugin.masonry.removeGridItem(plugin.postID);
				SocialCurator.toggleLoading(false);
				$('[data-trash-count]').text(parseInt($('[data-trash-count]').text()) - 1);
			}
		});
	}

	plugin.deletePost = function()
	{
		SocialCurator.toggleLoading(true);
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				nonce : SocialCurator.jsData.nonce,
				action: SocialCurator.formActions.deletePost,
				post_id: plugin.postID
			},
			success: function(data){
				plugin.masonry.removeGridItem(plugin.postID);
				SocialCurator.toggleLoading(false);
				$('[data-trash-count]').text(parseInt($('[data-trash-count]').text()) - 1);
			}
		});
	}

	return plugin.init();
}